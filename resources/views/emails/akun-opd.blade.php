<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akun OPD Baru</title>
    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; color: #1f2937; }
        .container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        .btn { display: inline-block; background-color: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        h2 { color: #2563eb; }
    </style>   
</head>
<body>
    <div class="container">
        <h2>Halo {{ $nama_opd }},</h2>
        <p>Akun Anda telah dibuat di aplikikasi SIMORGAN. Berikut detailnya:</p>
        <ul>
            <li>Email: <strong>{{ $email }}</strong></li>
            <li>Password: <strong>{{ $password }}</strong></li>
        </ul>
        <p>Silakan login dan ganti kata sandi (opsional) setelah login pertama.</p>
        <a href="{{ url('/') }}" class="btn">Login Sekarang</a>
        <p>Terima kasih.</p>
    </div>
</body>
</html>
