<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Reset Kata Sandi</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:30px;">
    <div style="max-width:500px; margin:auto; background:#fff; border-radius:10px; padding:30px;">
        <h2 style="color:#2c7a7b;">Reset Kata Sandi - SiPinjam</h2>
        <p>Halo, <strong>{{ $user->name }}</strong>!</p>
        <p>Gunakan kode OTP berikut untuk mereset kata sandi kamu:</p>
        <div style="font-size:32px; font-weight:bold; letter-spacing:8px; color:#2c7a7b; margin:20px 0;">
            {{ $otp }}
        </div>
        <p>Kode ini berlaku selama <strong>5 menit</strong>.</p>
        <p>Jika kamu tidak merasa melakukan permintaan ini, abaikan email ini.</p>
        <hr>
        <small style="color:#999;">SiPinjam — Sistem Peminjaman Ruangan</small>
    </div>
</body>
</html>