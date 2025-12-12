<!DOCTYPE html>
<html>
<head>
    <title>OTP Code</title>
</head>
<body>
    <h2>{{ $type === 'forgot' ? 'Reset Password' : 'Verifikasi Akun' }}</h2>

    <p>
        Kode OTP Anda adalah:
    </p>
    <h3><strong>{{ $otp }}</strong></h3>

    @if($type === 'forgot')
        <p>Gunakan kode ini untuk mereset password akun Anda.</p>
    @else
        <p>Gunakan kode ini untuk memverifikasi akun baru Anda.</p>
    @endif

    <p>Kode berlaku 5 menit.</p>

</body>
</html>
