<?php

namespace App\Http\Controllers\AdminPelayananPublik;

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
            ->where('created_by', 'ADMIN_PELAYANAN_PUBLIK')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination 10 data per halaman

        return view('adminpelayananpublik.kelola-akun.index', compact('akun'));
    }

    // CEK EMAIL
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
                    'data' => [
                        'nama_opd' => $existingUser->nama_opd,
                        'email' => $existingUser->email,
                        'role' => $existingUser->role,
                        'created_by' => $existingUser->created_by,
                        'created_at' => $existingUser->created_at ? $existingUser->created_at->format('d/m/Y H:i') : '-',
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
                'created_by' => Auth::user()->role,
                'unit_kerja' => $validated['nama_opd'],
            ]);

            // Kirim email password menggunakan view
            try {
                $this->sendPasswordEmail(Auth::user(), $akunBaru->email, $akunBaru->nama_opd, $passwordPlain);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun OPD berhasil ditambahkan',
                'data' => $akunBaru
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
    // Fungsi kirim email password menggunakan view
    // =====================================================
    private function sendPasswordEmail($admin, $userEmail, $userNama, $passwordPlain)
    {
        // Pastikan admin sudah connect Gmail
        if (!$admin || !isset($admin->gmail_token)) {
            \Log::warning('Admin tidak memiliki token Gmail');
            return;
        }

        // Render view email
        $bladeHtml = view('emails.akun-opd', [
            'nama_opd' => $userNama,
            'email' => $userEmail,
            'password' => $passwordPlain
        ])->render();

        // Ubah CSS menjadi inline style untuk kompatibilitas email client
        $cssToInline = new CssToInlineStyles();
        $htmlInline = $cssToInline->convert($bladeHtml);

        // Ambil token
        $tokenArray = json_decode($admin->gmail_token, true);
        if (!$tokenArray || !isset($tokenArray['access_token'])) {
            \Log::warning('Token Gmail tidak valid');
            return;
        }

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($tokenArray);

        // Refresh token jika expired
        if ($client->isAccessTokenExpired() && isset($tokenArray['refresh_token'])) {
            $client->fetchAccessTokenWithRefreshToken($tokenArray['refresh_token']);
            $admin->gmail_token = json_encode($client->getAccessToken());
            $admin->save();
        }

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
        try {
            $service->users_messages->send('me', $message);
            \Log::info('Email berhasil dikirim ke: ' . $userEmail);
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OPD: ' . $e->getMessage());
            throw new \Exception('Email tidak terkirim. Silakan cek koneksi Gmail.');
        }
    }
}