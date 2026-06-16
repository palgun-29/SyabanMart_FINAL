<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – SYA'BAN MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8f9fa;
            overflow: hidden;
        }

        /* Left Panel – Logo & Branding */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #395e51 0%, #395e51 50%, #395e51 100%),
                url('{{ asset('images/logo-dashboard.png') }}') no-repeat center / cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .brand-banner-image {
            width: 100%;
            max-width: 420px;
            border-radius: 24px;
            box-shadow: 0 18px 36px rgba(0,0,0,0.22);
            border: 1px solid rgba(255,255,255,0.22);
            margin-bottom: 24px;
        }
        .brand-banner-image img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 24px;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .logo-container {
            position: relative;
            z-index: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .brand-logo-image {
            width: 140px;
            height: 140px;
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .brand-logo-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-logo-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
        }

        .left-panel h1 {
            font-size: 36px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            line-height: 1.2;
            margin: 0;
        }

        .left-panel .subtitle {
            font-size: 15px;
            color: rgba(255,255,255,0.75);
            line-height: 1.5;
            max-width: 280px;
        }

        .features-simple {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 32px;
            width: 100%;
            max-width: 300px;
        }

        .feature-item-simple {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            padding: 8px 0;
        }

        .feature-icon-simple {
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Right Panel – Form */
        .right-panel {
            width: 440px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            box-shadow: -5px 0 30px rgba(0,0,0,0.08);
        }

        .login-header {
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-group-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            pointer-events: none;
            z-index: 5;
        }

        .form-control {
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #395e51;
            background: white;
            box-shadow: 0 0 0 3px rgba(57,94,81,0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 16px;
            z-index: 5;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #395e51;
        }

        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .form-check-input {
            border-radius: 4px;
            width: 16px;
            height: 16px;
            margin-right: 6px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #25eb88;
            border-color: #25eb88;
        }

        .btn-login {
            background: linear-gradient(135deg, #395e51 0%, #2f5546 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            transition: all 0.25s ease;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(57,94,81,0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider-section {
            text-align: center;
            margin: 28px 0;
            position: relative;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        .divider-section::before,
        .divider-section::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 43%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-section::before { left: 0; }
        .divider-section::after { right: 0; }

        /* Demo accounts */
        .demo-accounts {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .demo-btn {
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 11px 13px;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 11px;
            transition: all 0.15s ease;
            font-family: 'Inter', sans-serif;
            text-align: left;
            width: 100%;
        }

        .demo-btn:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
            transform: translateX(2px);
        }

        .demo-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .demo-label {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
        }

        .demo-email {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 12px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger-custom {
            background: #fef2f2;
            color: #b91c1c;
        }

        .alert-success-custom {
            background: #f0fdf4;
            color: #15803d;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                padding: 40px 28px;
                justify-content: flex-start;
                padding-top: 60px;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <!-- Left Panel - Logo & Branding -->
    <div class="left-panel">
        <div class="logo-container">
            <div class="brand-banner-image">
                <img src="{{ asset('images/logo-dashboard.png') }}" alt="Sayban Mart">
            </div>
            <h1 class="subtitle">Sistem Manajemen Toko Terpadu</h1>

            <!-- <div class="features-simple">
                <div class="feature-item-simple">
                    <span class="feature-icon-simple">🔒</span>
                    <span>Akses aman dengan role berbeda</span>
                </div>
                <div class="feature-item-simple">
                    <span class="feature-icon-simple">📊</span>
                    <span>Dashboard real-time</span>
                </div>
                <div class="feature-item-simple">
                    <span class="feature-icon-simple">📦</span>
                    <span>Manajemen stok terintegrasi</span>
                </div>
            </div-->
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="right-panel">
        <div class="login-header">
            <h2>Masuk ke Akun</h2>
        </div>

        @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert-custom alert-danger-custom">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first('email') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-group-icon">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group-icon">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="password"
                        class="form-control"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required>
                    <span class="toggle-password" onclick="togglePwd()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="remember-forgot">
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: #475569;">
                    <input type="checkbox" name="remember" class="form-check-input" style="margin: 0;">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right" style="margin-right: 8px;"></i>Masuk
            </button>
        </form>

        <!--div class="divider-section">Akun Demo</div>

        <div class="demo-accounts">
            <button type="button" class="demo-btn" onclick="fillLogin('manager@mart.com','password123')">
                <div class="demo-icon" style="background:#fef9c3;color:#d97706;">👑</div>
                <div>
                    <div class="demo-label">Manager</div>
                    <div class="demo-email">manager@mart.com</div>
                </div>
            </button>
            <button type="button" class="demo-btn" onclick="fillLogin('admin@mart.com','password123')">
                <div class="demo-icon" style="background:#dcfce7;color:#395e51;">📦</div>
                <div>
                    <div class="demo-label">Admin / Gudang</div>
                    <div class="demo-email">admin@mart.com</div>
                </div>
            </button>
            <button type="button" class="demo-btn" onclick="fillLogin('kasir@mart.com','password123')">
                <div class="demo-icon" style="background:#dcfce7;color:#16a34a;">🧾</div>
                <div>
                    <div class="demo-label">Kasir</div>
                    <div class="demo-email">kasir@mart.com</div>
                </div>
            </button>
        </div-->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePwd() {
            const p = document.getElementById('password');
            const i = document.getElementById('eyeIcon');
            if (p.type === 'password') {
                p.type = 'text';
                i.className = 'bi bi-eye-slash';
            } else {
                p.type = 'password';
                i.className = 'bi bi-eye';
            }
        }

        function fillLogin(email, pwd) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pwd;
            document.getElementById('email').focus();
        }
    </script>
</body>
</html>
