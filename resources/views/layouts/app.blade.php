<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard') – Sayban Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #395e51;
            --primary-dark: #2f5546;
            --sidebar-bg: #0f172a;
            --sidebar-text: #e2e8f0;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --text-primary: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg);
            color: var(--text-primary);
        }

        /* ─── Sidebar ─── */
        .sidebar {
            width: 265px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 44px; height: 44px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(57,94,81,0.4);
            overflow: hidden;
        }

        .brand-text h5 { font-size: 15px; font-weight: 700; margin: 0; letter-spacing: -0.3px; }
        .brand-text p  { font-size: 11px; opacity: 0.55; margin: 0; }

        .sidebar-nav { padding: 14px 0 20px; flex: 1; }

        .nav-section-title {
            padding: 14px 20px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.35);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar-nav .nav-link i {
            width: 20px; font-size: 16px;
            text-align: center; flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.07);
            color: #fff; padding-left: 24px;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(57,94,81,0.18);
            color: #fff;
            border-left-color: var(--primary);
            font-weight: 600;
        }

        /* Role badge di sidebar bottom */
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .role-manager { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .role-admin   { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .role-kasir   { background: rgba(52,211,153,0.15); color: #34d399; }

        /* ─── Main content ─── */
        .main-content {
            margin-left: 265px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── Topbar ─── */
        .topbar {
            background: var(--surface);
            padding: 0 28px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0; z-index: 100;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }

        .topbar-title { font-size: 17px; font-weight: 650; color: var(--text-primary); }
        .topbar-actions { display: flex; align-items: center; gap: 14px; }

        .notif-btn {
            position: relative;
            color: var(--text-muted);
            font-size: 20px;
            text-decoration: none;
            transition: color 0.2s;
        }
        .notif-btn:hover { color: var(--primary); }
        .notif-badge {
            position: absolute;
            top: -4px; right: -6px;
            background: #ef4444;
            color: #fff;
            font-size: 10px; font-weight: 700;
            min-width: 17px; height: 17px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 3px;
        }

        .user-dropdown { position: relative; }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 5px 10px 5px 5px;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .user-chip:hover { border-color: var(--primary); }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 13px; font-weight: 700;
        }
        .avatar-manager { background: linear-gradient(135deg,#f59e0b,#d97706); }
        .avatar-admin   { background: linear-gradient(135deg,#395e51,#2f5546); }
        .avatar-kasir   { background: linear-gradient(135deg,#10b981,#059669); }

        .user-info .user-name  { font-size: 13px; font-weight: 600; }
        .user-info .user-role  { font-size: 10px; color: var(--text-muted); }

        .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 8px);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            min-width: 200px;
            z-index: 200;
            overflow: hidden;
        }
        .dropdown-menu-custom.show { display: block; }
        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            font-size: 13.5px;
            color: var(--text-primary);
            text-decoration: none;
            transition: background 0.15s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .dropdown-item-custom:hover { background: #f8fafc; }
        .dropdown-item-custom.danger { color: #ef4444; }
        .dropdown-item-custom.danger:hover { background: #fef2f2; }
        .dropdown-divider-custom { height: 1px; background: var(--border); }

        /* ─── Content area ─── */
        .content-area { padding: 28px 32px; flex: 1; }

        /* ─── Cards ─── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 15px;
        }
        .card-body { padding: 20px; }

        /* ─── Table ─── */
        .table-container {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .table { margin-bottom: 0; }
        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
            color: var(--text-muted);
            font-size: 11.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.6px;
            padding: 14px 16px; white-space: nowrap;
        }
        .table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 13.5px;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f8fafc; }

        /* ─── Badges ─── */
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-success  { background: #dcfce7; color: #15803d; }
        .badge-danger   { background: #fee2e2; color: #b91c1c; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-info     { background: #dcfce7; color: #395e51; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }

        /* ─── Stat cards ─── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            display: flex; align-items: center; gap: 18px;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.09); transform: translateY(-2px); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }
        .stat-info .label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .stat-info .value { font-size: 26px; font-weight: 700; color: var(--text-primary); line-height: 1; }

        /* ─── Alerts ─── */
        .alert { border: none; border-radius: 10px; padding: 13px 16px; font-size: 13.5px; }
        .alert-success { background: #f0fdf4; color: #15803d; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; }
        .alert-warning { background: #fffbeb; color: #92400e; }

        /* ─── Buttons ─── */
        .btn { border-radius: 8px; font-weight: 500; font-size: 13.5px; }
        .btn-primary   { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm        { padding: 5px 11px; font-size: 12px; }

        /* Override outline primary (Bootstrap) to match theme green */
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            background-color: transparent;
        }
        .btn-outline-primary:hover {
            color: #fff;
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary.active,
        .btn-outline-primary:active,
        .btn-outline-primary:focus {
            color: #fff;
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: none;
        }

        /* Ensure utility class text-primary uses our primary color */
        .text-primary { color: var(--primary) !important; }

        /* Ensure payment method buttons follow theme (higher specificity) */
        .btn.btn-payment-method {
            color: var(--primary);
            border-color: var(--primary);
            background-color: transparent;
        }
        .btn.btn-payment-method.active,
        .btn.btn-payment-method:active,
        .btn.btn-payment-method:focus {
            background-color: var(--primary) !important;
            color: #fff !important;
            border-color: var(--primary) !important;
            box-shadow: none !important;
        }

        /* ─── Form ─── */
        .form-label { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
        .form-control, .form-select {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13.5px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(57,94,81,0.1);
        }

        /* ─── Page header ─── */
        .page-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 24px;
        }
        .page-header-title h2 { font-size: 22px; font-weight: 700; margin-bottom: 3px; }
        .page-header-title p  { font-size: 13px; color: var(--text-muted); margin: 0; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .content-area { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $user   = auth()->user();
    $role   = $user->role;
    $isManager = $user->isManager();
    $isAdmin   = $user->isAdmin();
    $isKasir   = $user->isKasir();
    $unread = \App\Models\StockNotifikasi::where('dibaca', false)->count();
    $avatarClass = match($role) { 'manager' => 'avatar-manager', 'admin' => 'avatar-admin', default => 'avatar-kasir' };
    $roleClass   = match($role) { 'manager' => 'role-manager', 'admin' => 'role-admin', default => 'role-kasir' };
    $roleIcon    = match($role) { 'manager' => '👑', 'admin' => '📦', default => '🧾' };
@endphp

<!-- Sidebar -->
    <aside class="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><img src="{{ asset('images/sayban-banner.jpeg') }}" alt="Sayban Mart" style="width:100%;height:100%;object-fit:cover;"></div>
        <div class="brand-text">
            <h5>SYA'BAN MART</h5>
            <p>Sistem Manajemen Toko</p>
        </div>
    </a>

    <nav class="sidebar-nav">
        {{-- Dashboard – semua role --}}
        <div class="nav-section-title">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        </a>

        {{-- Master Data – Manager & Admin --}}
        @if($isManager || $isAdmin)
        <div class="nav-section-title">Master Data</div>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i><span>Data Supplier</span>
        </a>
        <a href="{{ route('barangs.index') }}" class="nav-link {{ request()->routeIs('barangs.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i><span>Data Barang</span>
        </a>
        @endif

        {{-- Barang (read) untuk Kasir --}}
        @if($isKasir)
        <div class="nav-section-title">Referensi</div>
        <a href="{{ route('barangs.index') }}" class="nav-link {{ request()->routeIs('barangs.index') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i><span>Daftar Barang</span>
        </a>
        @endif

        {{-- Transaksi Penjualan – Manager & Kasir --}}
        {{-- Manajemen Stok – Manager & Admin --}}
        @if($isManager || $isKasir || $isAdmin)
        <div class="nav-section-title">Transaksi</div>
        @if($isManager || $isKasir)
        <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
            <i class="bi bi-cart3"></i><span>Transaksi Penjualan</span>
        </a>
        @endif
        @if($isManager || $isAdmin)
        <a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i><span>Manajemen Stok</span>
        </a>
        @endif
        @endif

        {{-- Stock Opname & Notifikasi – Manager & Admin --}}
        @if($isManager || $isAdmin)
        <div class="nav-section-title">Stok Opname</div>
        <a href="{{ route('stock-opname.index') }}" class="nav-link {{ request()->routeIs('stock-opname.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i><span>Stock Opname</span>
        </a>
        <a href="{{ route('notifikasi.index') }}" class="nav-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
            <i class="bi bi-bell{{ $unread > 0 ? '-fill' : '' }}"></i>
            <span>Notifikasi Stok</span>
            @if($unread > 0)
                <span class="ms-auto badge" style="background:#ef4444;color:#fff;padding:2px 7px;border-radius:12px;font-size:10px;">{{ $unread }}</span>
            @endif
        </a>
        @endif

        {{-- Laporan --}}
        <div class="nav-section-title">Laporan</div>
        @if($isManager || $isKasir)
        <a href="{{ route('laporan.penjualan') }}" class="nav-link {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i><span>Laporan Penjualan</span>
        </a>
        <a href="{{ route('laporan.cetak-laporan') }}" class="nav-link {{ request()->routeIs('laporan.cetak-laporan') ? 'active' : '' }}">
            <i class="bi bi-printer"></i><span>Cetak Laporan</span>
        </a>
        @endif
        @if($isManager || $isAdmin)
        <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
            <i class="bi bi-archive"></i><span>Laporan Stok</span>
        </a>
        @endif
    </nav>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-actions">
            @if($isManager || $isAdmin)
            <a href="{{ route('notifikasi.index') }}" class="notif-btn">
                <i class="bi bi-bell"></i>
                @if($unread > 0)
                    <span class="notif-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                @endif
            </a>
            @endif

            <div class="user-dropdown" id="userDropdownWrap">
                <div class="user-chip" id="userChip" onclick="toggleDropdown()">
                    <div class="user-avatar {{ $avatarClass }}">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">{{ $user->role_label }}</div>
                    </div>
                    <i class="bi bi-chevron-down ms-1" style="font-size:11px;color:#94a3b8;"></i>
                </div>

                <div class="dropdown-menu-custom" id="userDropdown">
                    <div style="padding:12px 16px 8px;border-bottom:1px solid #f1f5f9;">
                        <div style="font-size:13px;font-weight:700;">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#64748b;">{{ $user->email }}</div>
                        <span class="role-badge {{ $roleClass }} mt-1" style="display:inline-flex;">{{ $roleIcon }} {{ $user->role_label }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item-custom danger">
                            <i class="bi bi-box-arrow-right"></i> Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="content-area">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleDropdown() {
    document.getElementById('userDropdown').classList.toggle('show');
}
// Tutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('userDropdownWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('userDropdown').classList.remove('show');
    }
});
</script>
@stack('scripts')
</body>
</html>
