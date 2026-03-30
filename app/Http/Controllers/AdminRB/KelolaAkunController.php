<?php

namespace App\Http\Controllers\AdminRB;

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
    /**
     * Tampilkan daftar akun OPD
     */
    public function index()
    {
        $akun = Pengguna::where('role', 'OPD')
            ->where('created_by', 'ADMIN_RB')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('adminrb.kelola-akun.index', compact('akun'));
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
        
        // Cari user berdasarkan email
        $existingUser = Pengguna::where('email', $email)->first();
        
        if ($existingUser) {
            return response()->json([
                'duplicate' => true,  // PASTIKAN INI BOOLEAN
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
            'duplicate' => false,  // PASTIKAN INI BOOLEAN
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

    /**
     * Tambah akun OPD baru
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_opd' => 'required|string|max:255',
                'email' => 'required|email|unique:pengguna,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // huruf kecil
                    'regex:/[A-Z]/',      // huruf besar
                    'regex:/[0-9]/',      // angka
                    'regex:/[@$!%*?&#]/',  // simbol
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
                'created_by' => 'ADMIN_RB',
                'unit_kerja' => $validated['nama_opd'], // Set unit_kerja sama dengan nama_opd
            ]);

            // Kirim email ke OPD (opsional, bisa di-skip dulu jika bermasalah)
            try {
                $this->sendPasswordEmail(Auth::user(), $akunBaru->email, $akunBaru->nama_opd, $passwordPlain);
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan proses
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

    /**
     * Hapus akun OPD
     */
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

    /**
     * Kirim email via Gmail API
     */
    private function sendPasswordEmail($admin, $userEmail, $userNama, $passwordPlain)
    {
        // Skip jika tidak ada token
        if (!$admin || !isset($admin->gmail_token)) {
            return;
        }

        $bladeHtml = view('emails.akun-opd', [
            'nama_opd' => $userNama,
            'email' => $userEmail,
            'password' => $passwordPlain
        ])->render();

        // Ubah CSS menjadi inline style
        $cssToInline = new CssToInlineStyles();
        $htmlInline = $cssToInline->convert($bladeHtml);

        // Kirim email via Gmail API
        $subject = 'Akun OPD Baru - Monitoring Bagor';
        $rawMessage = "From: {$admin->email}\r\n";
        $rawMessage .= "To: {$userEmail}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $htmlInline;

        $encodedMessage = base64_encode($rawMessage);
        $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

        $message = new Google_Service_Gmail_Message();
        $message->setRaw($encodedMessage);

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $tokenArray = json_decode($admin->gmail_token, true);
        $client->setAccessToken($tokenArray);

        $service = new Google_Service_Gmail($client);
        $service->users_messages->send('me', $message);
    }
}