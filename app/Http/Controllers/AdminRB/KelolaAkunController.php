<?php

namespace App\Http\Controllers\AdminRB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
            ->where('created_by', 'ADMIN_RB')
            ->get();

        return view('adminrb.kelola-akun.index', compact('akun'));
    }

      public function checkEmail(Request $request)
    {
        $email = $request->get('email');
        
        // Cari user berdasarkan email
        $existingUser = Pengguna::where('email', $email)->first();
        
        if ($existingUser) {
            return response()->json([
                'duplicate' => true,
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
            'duplicate' => false
        ]);
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
                'regex:/[a-z]/',      // huruf kecil
                'regex:/[A-Z]/',      // huruf besar
                'regex:/[0-9]/',      // angka
                'regex:/[@$!%*?&#]/',  // simbol
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
            'created_by' => Auth::user()->role, // otomatis isi role admin yang login
        ]);

        // Kirim email ke OPD pakai Gmail API admin yang login
        $this->sendPasswordEmail(Auth::user(), $akunBaru->email, $akunBaru->nama_opd, $passwordPlain);

        return back()->with('success', "Akun OPD berhasil ditambahkan dan email dikirim.");
    }

    // Hapus akun OPD
    public function destroy($id)
    {
        $akun = Pengguna::findOrFail($id);
        if ($akun->role !== 'OPD') {
            return back()->with('error', 'Hanya akun OPD yang bisa dihapus.');
        }
        $akun->delete();
        return back()->with('success', 'Akun OPD berhasil dihapus.');
    }

    // =====================================================
    // Fungsi untuk kirim email lewat Gmail API
    // =====================================================
    private function sendPasswordEmail($admin, $userEmail, $userNama, $passwordPlain)
{
    $bladeHtml = view('emails.akun-opd', [
        'nama_opd' => $userNama,
        'email' => $userEmail,
        'password' => $passwordPlain
    ])->render();

    // Ubah CSS menjadi inline style supaya Gmail bisa tampil warna dan style
    $cssToInline = new CssToInlineStyles();
    $htmlInline = $cssToInline->convert($bladeHtml);

    // Kirim email via Gmail API
    $subject = 'Akun OPD Baru';
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
