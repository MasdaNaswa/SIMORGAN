<?php

namespace App\Http\Controllers\AdminPelayananPublik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Gmail API
use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class KelolaAkunController extends Controller
{
    // Tampilkan daftar akun OPD
    public function index()
    {
        $akun = Pengguna::where('role', 'OPD')
            ->where('created_by', 'ADMIN_PELAYANAN_PUBLIK')
            ->get();

        return view('adminpelayananpublik.kelola-akun.index', compact('akun'));
    }

    // CEK EMAIL
    public function checkEmail(Request $request)
    {
        $email = $request->get('email');
        
        $existingUser = Pengguna::where('email', $email)->first();
        
        if ($existingUser) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'nama_opd' => $existingUser->nama_opd,
                    'email' => $existingUser->email,
                    'role' => $existingUser->role,
                    'created_by' => $existingUser->created_by,
                    'created_at' => $existingUser->created_at ? $existingUser->created_at->format('d/m/Y H:i') : '-',
                ]
            ]);
        }
        
        return response()->json(['exists' => false]);
    }

    // Tambah akun OPD baru
    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

        $passwordPlain = $validated['password'];

        // Simpan akun baru
        $akunBaru = Pengguna::create([
            'nama_opd' => $validated['nama_opd'],
            'email' => $validated['email'],
            'password' => Hash::make($passwordPlain),
            'role' => 'OPD',
            'created_by' => Auth::user()->role,
        ]);

        // Kirim email password
        try {
            $this->sendPasswordEmail(Auth::user(), $akunBaru->email, $akunBaru->nama_opd, $passwordPlain);
        } catch (\Exception $e) {
            // Email gagal dikirim, tapi tetap lanjutkan
        }

        return response()->json([
            'success' => true
        ]);
    }

    // Hapus akun OPD
    public function destroy($id)
    {
        $akun = Pengguna::findOrFail($id);
        $akun->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // =====================================================
    // Fungsi kirim email password langsung di controller
    // =====================================================
    private function sendPasswordEmail($admin, $userEmail, $userNama, $passwordPlain)
    {
        if (!$admin->gmail_token) {
            throw new \Exception('Silakan hubungkan Gmail Anda terlebih dahulu.');
        }

        $tokenArray = json_decode($admin->gmail_token, true);
        if (!$tokenArray || !isset($tokenArray['access_token'])) {
            throw new \Exception('Token Gmail tidak valid. Silakan reconnect Gmail.');
        }

        $client = new Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($tokenArray);

        if ($client->isAccessTokenExpired() && isset($tokenArray['refresh_token'])) {
            $client->fetchAccessTokenWithRefreshToken($tokenArray['refresh_token']);
            $admin->gmail_token = json_encode($client->getAccessToken());
            $admin->save();
        }

        $service = new Google_Service_Gmail($client);

        $subject = 'Akun OPD Baru';
        $body = "Halo {$userNama},<br><br>
                 Akun Anda telah dibuat.<br>
                 <strong>Email:</strong> {$userEmail}<br>
                 <strong>Password:</strong> {$passwordPlain}<br>
                 Silakan login dan ubah password Anda.<br><br>
                 Terima kasih.";

        $rawMessage = "From: {$admin->email}\r\n";
        $rawMessage .= "To: {$userEmail}\r\n";
        $rawMessage .= "Subject: {$subject}\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $body;

        $encodedMessage = base64_encode($rawMessage);
        $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

        $message = new Google_Service_Gmail_Message();
        $message->setRaw($encodedMessage);

        try {
            $service->users_messages->send('me', $message);
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OPD: ' . $e->getMessage());
            throw new \Exception('Email tidak terkirim. Silakan cek koneksi Gmail.');
        }
    }
}