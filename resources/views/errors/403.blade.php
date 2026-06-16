<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 – Akses Ditolak | Sayban Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            padding: 40px;
            max-width: 480px;
        }
        .icon-circle {
            width: 100px; height: 100px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 46px;
            color: #dc2626;
        }
        h1 { font-size: 72px; font-weight: 800; color: #1e293b; line-height: 1; margin-bottom: 8px; }
        h2 { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
        p  { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 28px; }
        .role-info {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13px;
            color: #475569;
            margin-bottom: 28px;
        }
        .role-info strong { color: #395e51; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary { background: #395e51; color: #fff; }
        .btn-primary:hover { background: #2f5546; transform: translateY(-1px); }
        .btn-ghost { background: #f1f5f9; color: #475569; margin-left: 8px; border: 1px solid #e2e8f0; }
        .btn-ghost:hover { background: #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle"><i class="bi bi-shield-x"></i></div>
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi Manager jika Anda merasa ini adalah kesalahan.</p>
        @auth
        <div class="role-info">
            Anda login sebagai <strong>{{ auth()->user()->name }}</strong>
            dengan role <strong>{{ auth()->user()->role_label }}</strong>.
        </div>
        @endauth
        <div>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-house"></i> Dashboard
            </a>
        </div>
    </div>
</body>
</html>
