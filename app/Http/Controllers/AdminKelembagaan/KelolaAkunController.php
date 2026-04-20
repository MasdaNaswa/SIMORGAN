<?php

namespace App\Http\Controllers\AdminKelembagaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles; 

// Gmail API
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class KelolaAkunController extends Controller
{
    // Tampilkan daftar akun OPD dengan pagination
    public function index()
    {
        $akun = Pengguna::where('role', 'OPD')
            ->where('created_by', 'ADMIN_KELEMBAGAAN')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminkelembagaan.kelola-akun.index', compact('akun'));
    }

    /**
     * Cek apakah email sudah digunakan
     */
    public function checkEmail(Request $request)
    {
        try {
            $email = $request->get('email');
            
            if (!$email) {
                return response()->json([
                    'duplicate' => false,
                    'success' => false,
                    'message' => 'Email tidak boleh kosong'
                ], 400);
            }
            
            $existingUser = Pengguna::where('email', $email)->first();
            
            if ($existingUser) {
                return response()->json([
                    'duplicate' => true,
                    'success' => true,
                    'message' => 'Email ' . $email . ' sudah digunakan',
                    'existing_user' => [
                        'nama_opd' => $existingUser->nama_opd,
                        'email' => $existingUser->email,
                        'role' => $existingUser->role,
                        'created_by' => $existingUser->created_by,
                        'created_at' => $existingUser->created_at->format('d/m/Y H:i')
                    ]
                ]);
            }
            
            return response()->json([
                'duplicate' => false,
                'success' => true,
                'message' => 'Email tersedia'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'duplicate' => false,
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tambah akun OPD baru
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_opd' => 'required|string|max:255',
                'email' => 'required|email|unique:pengguna,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*?&#]/',
                ],
            ], [
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
                'email.unique' => 'Email sudah digunakan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $validated = $validator->validated();
            $passwordPlain = $validated['password'];

            // Simpan akun baru
            $akunBaru = Pengguna::create([
                'nama_opd' => $validated['nama_opd'],
                'email' => $validated['email'],
                'password' => Hash::make($passwordPlain),
                'role' => 'OPD',
                'created_by' => 'ADMIN_KELEMBAGAAN',
                'unit_kerja' => $validated['nama_opd'],
            ]);

            // Kirim email dengan auto refresh token
            $emailSent = $this->sendPasswordEmailWithAutoRefresh(Auth::user(), $akunBaru->email, $akunBaru->nama_opd, $passwordPlain);
            
            if (!$emailSent) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil ditambahkan, tetapi email password gagal dikirim. Silahkan cek koneksi Gmail Anda.',
                    'data' => $akunBaru,
                    'email_sent' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun OPD berhasil ditambahkan',
                'data' => $akunBaru,
                'email_sent' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah akun: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hapus akun OPD
    public function destroy($id)
    {
        try {
            $akun = Pengguna::findOrFail($id);
            
            if ($akun->role !== 'OPD') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya akun OPD yang bisa dihapus.'
                ], 403);
            }
            
            $akun->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Akun OPD berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }

    // =====================================================
    // FUNGSI UTAMA: Kirim email dengan auto refresh token
    // =====================================================
    private function sendPasswordEmailWithAutoRefresh($admin, $userEmail, $userNama, $passwordPlain)
    {
        try {
            // Cek apakah admin memiliki token
            if (!$admin || !isset($admin->gmail_token)) {
                \Log::warning('Admin Kelembagaan tidak memiliki token Gmail');
                return false;
            }

            // Decode token
            $tokenArray = json_decode($admin->gmail_token, true);
            if (!$tokenArray || !isset($tokenArray['access_token'])) {
                \Log::warning('Token Gmail Admin Kelembagaan tidak valid');
                return false;
            }

            // Inisialisasi Google Client
            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setAccessToken($tokenArray);
            $client->setAccessType('offline');
            $client->setIncludeGrantedScopes(true);

            // CEK DAN REFRESH TOKEN JIKA EXPIRED
            $tokenRefreshed = false;
            if ($client->isAccessTokenExpired()) {
                \Log::info('Token Gmail Admin Kelembagaan expired, mencoba refresh...');
                
                if (isset($tokenArray['refresh_token'])) {
                    try {
                        // Refresh dengan refresh_token
                        $client->fetchAccessTokenWithRefreshToken($tokenArray['refresh_token']);
                        $newToken = $client->getAccessToken();
                        
                        // Simpan token baru ke database
                        $admin->gmail_token = json_encode($newToken);
                        $admin->save();
                        
                        $tokenRefreshed = true;
                        \Log::info('Token Gmail Admin Kelembagaan berhasil direfresh');
                    } catch (\Exception $e) {
                        \Log::error('Gagal refresh token Admin Kelembagaan: ' . $e->getMessage());
                        // Hapus token yang bermasalah
                        $admin->gmail_token = null;
                        $admin->save();
                        return false;
                    }
                } else {
                    \Log::error('Tidak ada refresh_token untuk Admin Kelembagaan, perlu connect ulang');
                    // Hapus token yang tidak lengkap
                    $admin->gmail_token = null;
                    $admin->save();
                    return false;
                }
            }

            // Render view email
            $bladeHtml = view('emails.akun-opd', [
                'nama_opd' => $userNama,
                'email' => $userEmail,
                'password' => $passwordPlain
            ])->render();

            // Ubah CSS menjadi inline style
            $cssToInline = new CssToInlineStyles();
            $htmlInline = $cssToInline->convert($bladeHtml);

            // Buat service Gmail
            $service = new Google_Service_Gmail($client);

            // Buat pesan email
            $subject = 'Akun OPD Baru - Monitoring Bagor';
            $rawMessage = "From: {$admin->email}\r\n";
            $rawMessage .= "To: {$userEmail}\r\n";
            $rawMessage .= "Subject: {$subject}\r\n";
            $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
            $rawMessage .= $htmlInline;

            // Encode base64 URL-safe
            $encodedMessage = base64_encode($rawMessage);
            $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

            $message = new Google_Service_Gmail_Message();
            $message->setRaw($encodedMessage);

            // Kirim pesan
            $service->users_messages->send('me', $message);
            \Log::info('Email berhasil dikirim ke: ' . $userEmail . ($tokenRefreshed ? ' (token auto-refresh)' : ''));
            
            return true;
            
        } catch (\Google\Service\Exception $e) {
            // Error khusus Google API
            \Log::error('Google API Error saat kirim email Admin Kelembagaan: ' . $e->getMessage());
            
            // Cek jika error karena auth (401)
            if ($e->getCode() == 401) {
                // Hapus token yang tidak valid
                $admin->gmail_token = null;
                $admin->save();
                \Log::warning('Token Gmail Admin Kelembagaan tidak valid, sudah direset. User perlu connect ulang.');
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OPD dari Admin Kelembagaan: ' . $e->getMessage());
            return false;
        }
    }

    // =====================================================
    // FUNGSI TAMBAHAN: Cek status koneksi Gmail
    // =====================================================
    public function checkGmailStatus()
    {
        $user = Auth::user();
        
        if (!$user->gmail_token) {
            return response()->json([
                'connected' => false,
                'message' => 'Gmail belum terhubung'
            ]);
        }
        
        // Cek apakah token masih valid
        $tokenArray = json_decode($user->gmail_token, true);
        
        if (!$tokenArray || !isset($tokenArray['access_token'])) {
            return response()->json([
                'connected' => false,
                'message' => 'Token tidak valid'
            ]);
        }
        
        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($tokenArray);
        
        if ($client->isAccessTokenExpired()) {
            return response()->json([
                'connected' => false,
                'message' => 'Token expired, silahkan connect ulang'
            ]);
        }
        
        return response()->json([
            'connected' => true,
            'email' => $user->email,
            'message' => 'Gmail terhubung dengan baik'
        ]);
    }
}