<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SiPinjam</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2f7ea1;
            --secondary: #4a9dc3;
            --dark: #1e293b;
            --white: #ffffff;
            --blur-strength: 14px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background-image: url("{{ asset('images/Me.png') }}");
            background-size: 500px; background-position: center;
            background-attachment: fixed; background-repeat: no-repeat;
            background-color: #dbeef7; padding: 30px 16px;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: rgba(30, 41, 59, 0.35); z-index: 0;
        }
        .login-wrapper {
            position: relative; z-index: 1;
            width: 100%; max-width: 430px;
            animation: fadeUp 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(var(--blur-strength));
            -webkit-backdrop-filter: blur(var(--blur-strength));
            border-radius: 28px; padding: 46px 44px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.18);
            border: 1px solid rgba(255,255,255,0.6);
        }
        .card-header { text-align: center; margin-bottom: 28px; }
        .card-logo {
            width: 56px; height: 56px;
            background-image: url("{{ asset('images/logo.png') }}");
            background-size: 100%; background-position: 50% 60%;
            background-repeat: no-repeat; margin: 0 auto 14px;
        }
        .icon-wrap {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(47,126,161,0.1);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .icon-wrap i { font-size: 1.6rem; color: var(--primary); }
        .card-header h2 {
            font-size: 1.2rem; font-weight: 700;
            color: var(--dark); margin-bottom: 8px;
        }
        .card-header p { font-size: 0.85rem; color: #64748b; line-height: 1.6; }

        /* Step indicator */
        .steps {
            display: flex; align-items: center; justify-content: center;
            gap: 0; margin-bottom: 28px;
        }
        .step {
            display: flex; flex-direction: column; align-items: center; gap: 6px;
        }
        .step-circle {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 600;
            background: #e2e8f0; color: #94a3b8;
            transition: all 0.3s;
        }
        .step.active .step-circle {
            background: var(--primary); color: white;
        }
        .step.done .step-circle {
            background: #22c55e; color: white;
        }
        .step-label {
            font-size: 0.7rem; color: #94a3b8; font-weight: 500;
            white-space: nowrap;
        }
        .step.active .step-label { color: var(--primary); }
        .step.done .step-label { color: #22c55e; }
        .step-line {
            width: 48px; height: 2px;
            background: #e2e8f0; margin-bottom: 18px;
            transition: background 0.3s;
        }
        .step-line.done { background: #22c55e; }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 0.85rem; font-weight: 600;
            color: #334155; margin-bottom: 8px;
        }
        .form-group label span { color: #ef4444; }
        .input-wrap { position: relative; }
        .input-wrap .prefix-icon {
            position: absolute; left: 15px; top: 50%;
            transform: translateY(-50%); color: #94a3b8;
            font-size: 0.95rem; pointer-events: none; transition: color 0.2s;
        }
        .input-wrap input {
            width: 100%; padding: 13px 16px 13px 44px;
            border: 2px solid #e2e8f0; border-radius: 12px;
            font-family: 'Poppins', sans-serif; font-size: 0.93rem;
            color: var(--dark); background: white; outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(47,126,161,0.1);
        }
        .input-wrap:focus-within .prefix-icon { color: var(--primary); }

        /* OTP input */
        .otp-wrap {
            display: flex; gap: 10px; justify-content: center;
        }
        .otp-wrap input {
            width: 52px; height: 56px;
            text-align: center; font-size: 1.3rem; font-weight: 600;
            border: 2px solid #e2e8f0; border-radius: 12px;
            font-family: 'Poppins', sans-serif; color: var(--dark);
            outline: none; padding: 0;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .otp-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(47,126,161,0.1);
        }

        .toggle-pass {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #94a3b8;
            font-size: 0.95rem; padding: 4px;
            transition: color 0.2s;
        }
        .toggle-pass:hover { color: var(--primary); }
        .input-wrap input.has-toggle { padding-right: 44px; }

        .btn-primary {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white; border: none; border-radius: 12px;
            font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 600;
            cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px rgba(47,126,161,0.35);
            display: flex; align-items: center; justify-content: center; gap: 9px;
            margin-top: 6px;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(47,126,161,0.4); }
        .btn-primary:active { transform: scale(0.98); }

        .resend-wrap {
            text-align: center; margin-top: 16px;
            font-size: 0.83rem; color: #64748b;
        }
        .resend-wrap a {
            color: var(--primary); text-decoration: none; font-weight: 500;
        }
        .resend-wrap a:hover { text-decoration: underline; }

        .alert-success {
            background: #f0fdf4; border: 1px solid #bbf7d0;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; color: #15803d;
            font-size: 0.85rem; display: flex; align-items: center; gap: 8px;
        }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; color: #dc2626;
            font-size: 0.85rem; display: flex; align-items: center; gap: 8px;
        }

        .back-link-wrap { text-align: center; margin-top: 22px; }
        .back-link {
            display: inline-flex; align-items: center; gap: 7px;
            color: #64748b; font-size: 0.80rem; font-weight: 500;
            text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary); }

        .to-home {
            display: block; text-align: center; margin-top: 16px;
            color: rgba(255,255,255,0.75); font-size: 0.85rem;
            text-decoration: none; transition: color 0.2s;
        }
        .to-home:hover { color: white; }

        /* Panel tiap step */
        .step-panel { display: none; }
        .step-panel.active { display: block; }

        /* Success screen */
        .success-screen { text-align: center; padding: 10px 0; }
        .success-screen .check-circle {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(34,197,94,0.12);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
        }
        .success-screen .check-circle i { font-size: 2rem; color: #22c55e; }
        .success-screen h3 { font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .success-screen p { font-size: 0.85rem; color: #64748b; margin-bottom: 24px; line-height: 1.6; }

        @media (max-width: 480px) {
            .card { padding: 36px 22px; }
            .otp-wrap input { width: 44px; height: 50px; font-size: 1.1rem; }
            .step-line { width: 32px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="card">

        <div class="card-header">
            <div class="card-logo"></div>
            <h2 id="main-title">Lupa Kata Sandi</h2>
            <p id="main-desc">Masukkan email terdaftar untuk menerima kode verifikasi.</p>
        </div>

        <!-- Step Indicator -->
        <div class="steps" id="step-indicator">
            <div class="step active" id="s1">
                <div class="step-circle">1</div>
                <span class="step-label">Email</span>
            </div>
            <div class="step-line" id="line1"></div>
            <div class="step" id="s2">
                <div class="step-circle">2</div>
                <span class="step-label">Verifikasi</span>
            </div>
            <div class="step-line" id="line2"></div>
            <div class="step" id="s3">
                <div class="step-circle">3</div>
                <span class="step-label">Sandi Baru</span>
            </div>
        </div>

        <!-- Alert placeholder -->
        <div id="alert-box" style="display:none;"></div>

        <!-- STEP 1: Input Email -->
        <div class="step-panel active" id="panel-1">
            <form id="form-email">
                <div class="form-group">
                    <label>Alamat Email <span>*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope prefix-icon"></i>
                        <input type="email" id="input-email" placeholder="contoh: ormawa@gmail.com" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Kode Verifikasi
                </button>
            </form>
        </div>

        <!-- STEP 2: Input OTP -->
        <div class="step-panel" id="panel-2">
            <p style="text-align:center; font-size:0.83rem; color:#64748b; margin-bottom:20px;">
                Kode dikirim ke <strong id="email-display" style="color:var(--dark);"></strong>
            </p>
            <form id="form-otp">
                <div class="form-group">
                    <label style="text-align:center; display:block;">Masukkan Kode OTP <span>*</span></label>
                    <div class="otp-wrap">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit" inputmode="numeric">
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="margin-top:16px;">
                    <i class="fas fa-check"></i> Verifikasi Kode
                </button>
            </form>
            <div class="resend-wrap">
                Tidak menerima kode?
                <a href="#" id="resend-btn" onclick="resendOtp(event)">Kirim ulang</a>
            </div>
        </div>

        <!-- STEP 3: Reset Password -->
        <div class="step-panel" id="panel-3">
            <form id="form-reset">
                <div class="form-group">
                    <label>Kata Sandi Baru <span>*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input type="password" id="new-password" placeholder="Minimal 8 karakter" required class="has-toggle">
                        <button type="button" class="toggle-pass" onclick="togglePass('new-password', 'eye1')" aria-label="Tampilkan">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Kata Sandi <span>*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-lock prefix-icon"></i>
                        <input type="password" id="confirm-password" placeholder="Ulangi kata sandi baru" required class="has-toggle">
                        <button type="button" class="toggle-pass" onclick="togglePass('confirm-password', 'eye2')" aria-label="Tampilkan">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-key"></i> Simpan Kata Sandi Baru
                </button>
            </form>
        </div>

        <!-- STEP 4: Sukses -->
        <div class="step-panel" id="panel-success">
            <div class="success-screen">
                <div class="check-circle">
                    <i class="fas fa-check"></i>
                </div>
                <h3>Kata Sandi Berhasil Diubah!</h3>
                <p>Kata sandi baru kamu sudah tersimpan. Silakan masuk dengan kata sandi baru.</p>
                <a href="{{ route('landingpage.pilih-login') }}" class="btn-primary" style="text-decoration:none;">
                    <i class="fas fa-right-to-bracket"></i> Masuk Sekarang
                </a>
            </div>
        </div>

        <div class="back-link-wrap" id="back-wrap">
            <a href="{{ route('landingpage.pilih-login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke halaman masuk
            </a>
        </div>

    </div>

    <a href="{{ url('/') }}" class="to-home">
        <i class="fas fa-house"></i> Kembali ke Beranda
    </a>
</div>

<script>
    let currentStep = 1;
let verifiedEmail = '';

function showAlert(type, msg) {
    const box = document.getElementById('alert-box');
    box.style.display = 'flex';
    box.className = type === 'success' ? 'alert-success' : 'alert-error';
    box.innerHTML = `<i class="fas fa-${type === 'success' ? 'circle-check' : 'circle-exclamation'}"></i> ${msg}`;
    setTimeout(() => { box.style.display = 'none'; }, 4000);
}

function goStep(step) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + step).classList.add('active');
    const titles = {
        1: ['Lupa Kata Sandi', 'Masukkan email terdaftar untuk menerima kode verifikasi.'],
        2: ['Verifikasi OTP', 'Masukkan 6 digit kode yang dikirim ke email kamu.'],
        3: ['Buat Kata Sandi Baru', 'Kata sandi baru harus berbeda dari sebelumnya.'],
    };
    if (titles[step]) {
        document.getElementById('main-title').textContent = titles[step][0];
        document.getElementById('main-desc').textContent  = titles[step][1];
    }
    for (let i = 1; i <= 3; i++) {
        const el = document.getElementById('s' + i);
        el.classList.remove('active', 'done');
        if (i < step)  el.classList.add('done');
        if (i === step) el.classList.add('active');
    }
    for (let i = 1; i <= 2; i++) {
        document.getElementById('line' + i).classList.toggle('done', i < step);
    }
    currentStep = step;
}

// STEP 1: Kirim OTP ke server
document.getElementById('form-email').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('input-email').value.trim();
    const btn = this.querySelector('button[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

    fetch('{{ route("password.send-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            verifiedEmail = email;
            document.getElementById('email-display').textContent = email;
            showAlert('success', data.message);
            setTimeout(() => goStep(2), 800);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(() => showAlert('error', 'Terjadi kesalahan. Coba lagi.'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Kode Verifikasi';
    });
});

// OTP auto-focus
const otpInputs = document.querySelectorAll('.otp-digit');
otpInputs.forEach((inp, idx) => {
    inp.addEventListener('input', () => {
        inp.value = inp.value.replace(/\D/g, '');
        if (inp.value && idx < otpInputs.length - 1) otpInputs[idx + 1].focus();
    });
    inp.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !inp.value && idx > 0) otpInputs[idx - 1].focus();
    });
});

// STEP 2: Verifikasi OTP ke server
document.getElementById('form-otp').addEventListener('submit', function(e) {
    e.preventDefault();
    const otp = Array.from(otpInputs).map(i => i.value).join('');
    if (otp.length < 6) { showAlert('error', 'Masukkan 6 digit kode OTP.'); return; }

    const btn = this.querySelector('button[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';

    fetch('{{ route("password.verify-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: verifiedEmail, otp })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => goStep(3), 800);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(() => showAlert('error', 'Terjadi kesalahan. Coba lagi.'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Verifikasi Kode';
    });
});

// Kirim ulang OTP
function resendOtp(e) {
    e.preventDefault();
    otpInputs.forEach(i => i.value = '');
    otpInputs[0].focus();

    fetch('{{ route("password.send-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: verifiedEmail })
    })
    .then(r => r.json())
    .then(data => showAlert(data.success ? 'success' : 'error', data.message))
    .catch(() => showAlert('error', 'Gagal mengirim ulang. Coba lagi.'));
}

// STEP 3: Reset password ke server
document.getElementById('form-reset').addEventListener('submit', function(e) {
    e.preventDefault();
    const np = document.getElementById('new-password').value;
    const cp = document.getElementById('confirm-password').value;
    if (np.length < 8) { showAlert('error', 'Kata sandi minimal 8 karakter.'); return; }
    if (np !== cp)     { showAlert('error', 'Konfirmasi kata sandi tidak cocok.'); return; }

    const btn = this.querySelector('button[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch('{{ route("password.reset") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: verifiedEmail, password: np, password_confirmation: cp })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('step-indicator').style.display = 'none';
            document.getElementById('main-title').textContent = '';
            document.getElementById('main-desc').textContent  = '';
            document.getElementById('back-wrap').style.display = 'none';
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            document.getElementById('panel-success').classList.add('active');
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(() => showAlert('error', 'Terjadi kesalahan. Coba lagi.'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-key"></i> Simpan Kata Sandi Baru';
    });
});

function togglePass(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>