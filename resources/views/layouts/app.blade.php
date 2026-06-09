<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Toko Bu Nardi') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary:      #1e40af;
            --primary-dark: #1e3a8a;
            --accent:       #f59e0b;
            --success:      #10b981;
            --danger:       #ef4444;
            --warning:      #f59e0b;
            --bg:           #f1f5f9;
            --sidebar-w:    240px;
            --white:        #ffffff;
            --gray-100:     #f8fafc;
            --gray-200:     #e2e8f0;
            --gray-500:     #64748b;
            --gray-700:     #334155;
            --gray-900:     #0f172a;
            --radius:       10px;
            --shadow:       0 1px 3px rgba(0,0,0,.10), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md:    0 4px 6px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.06);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--gray-900); min-height: 100vh; display: flex; }

        /* SIDEBAR */
        .sidebar { 
            width: var(--sidebar-w); 
            background: var(--primary-dark); 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            display: flex; 
            flex-direction: column; 
            z-index: 100; 
            overflow-y: auto; 
            scrollbar-width: thin; 
            scrollbar-color: rgba(255,255,255,0.2) transparent; 
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        .sidebar-logo { padding: 20px 16px 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-logo .logo-icon { width: 42px; height: 42px; background: var(--accent); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 10px; }
        .sidebar-logo h2 { color: #fff; font-size: 15px; font-weight: 700; line-height: 1.3; }
        .sidebar-logo span { color: rgba(255,255,255,.5); font-size: 11px; }
        .nav-section { padding: 12px 0 4px; }
        .nav-section-label { padding: 0 16px 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.35); }
        .nav-item { display: flex; padding: 10px 16px; color: rgba(255,255,255,.75); text-decoration: none; font-size: 13.5px; font-weight: 500; align-items: center; gap: 10px; border-left: 3px solid transparent; transition: background .15s, color .15s; }
        .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,.12); color: #fff; border-left-color: var(--accent); font-weight: 600; }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-footer { margin-top: auto; padding: 14px 16px; border-top: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.4); font-size: 11px; }

        /* MAIN */
        .main-wrapper { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: var(--white); border-bottom: 1px solid var(--gray-200); padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; box-shadow: var(--shadow); }
        .topbar-title { font-size: 17px; font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .badge-store { background: var(--primary); color: #fff; font-size: 11.5px; font-weight: 600; padding: 4px 12px; border-radius: 50px; }
        .page-content { padding: 24px; flex: 1; }

        /* PAGE HEADER */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .page-header h1 { font-size: 22px; font-weight: 700; }
        .page-header .breadcrumb { font-size: 12px; color: var(--gray-500); margin-top: 2px; }

        /* CARD */
        .card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--gray-200); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .card-header h3 { font-size: 15px; font-weight: 700; }
        .card-body { padding: 20px; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--white); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); border: 1px solid var(--gray-200); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .stat-card .label { font-size: 12px; color: var(--gray-500); font-weight: 500; }
        .stat-card .value { font-size: 22px; font-weight: 800; margin-top: 2px; }
        .stat-card .sub { font-size: 11px; color: var(--gray-500); margin-top: 3px; }

        /* TABLE */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th { background: var(--gray-100); padding: 11px 14px; text-align: left; font-weight: 700; color: var(--gray-700); border-bottom: 2px solid var(--gray-200); white-space: nowrap; }
        tbody tr { border-bottom: 1px solid var(--gray-200); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--gray-100); }
        tbody td { padding: 11px 14px; color: var(--gray-700); vertical-align: middle; }

        /* BADGE */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 50px; font-size: 11.5px; font-weight: 600; gap: 4px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-info    { background: #cffafe; color: #155e75; }
        .badge-primary { background: #dbeafe; color: #1e40af; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 7px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: opacity .15s, transform .1s; white-space: nowrap; }
        .btn:hover { opacity: .88; transform: translateY(-1px); }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-danger  { background: var(--danger);  color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-outline { background: transparent; color: var(--primary); border: 1.5px solid var(--primary); }
        .btn-sm { padding: 5px 11px; font-size: 12px; }
        .btn-group { display: flex; gap: 6px; flex-wrap: wrap; }

        /* FORMS */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 600; color: var(--gray-700); }
        input[type=text], input[type=number], input[type=date], select, textarea, .flatpickr-input { padding: 9px 12px; border: 1.5px solid var(--gray-200); border-radius: 7px; font-size: 13.5px; color: var(--gray-900); outline: none; transition: border .15s; background: var(--white); font-family: inherit; width: 100%; }
        input:focus, select:focus, textarea:focus, .flatpickr-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
        .flatpickr-input[readonly] { background-color: var(--white) !important; cursor: pointer; }
        .input-readonly { background: var(--gray-100); color: var(--gray-500); }
        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 4px; }
        .is-invalid { border-color: var(--danger) !important; }

        /* ALERTS */
        .alert { padding: 12px 16px; border-radius: var(--radius); margin-bottom: 16px; font-size: 13.5px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }

        /* SEARCH BAR */
        .search-bar { position: relative; display: flex; align-items: center; }
        .search-bar i { position: absolute; left: 11px; color: var(--gray-500); font-size: 13px; }
        .search-bar input { padding-left: 34px; }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 50px 20px; color: var(--gray-500); }
        .empty-state i { font-size: 48px; margin-bottom: 12px; opacity: .3; display: block; }

        /* STOK */
        .stok-kritis { color: #ef4444; font-weight: 700; }
        .stok-aman   { color: #10b981; font-weight: 600; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrapper { margin-left: 0; }
        }
        @media print {
            .sidebar, .topbar, .page-header .btn, .no-print { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">🏪</div>
        <h2>{{ config('app.name', 'Toko Bu Nardi') }}</h2>
        <span>Sistem Inventaris Produk</span>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Produk</div>
        <a href="{{ route('produk.index') }}" class="nav-item {{ request()->routeIs('produk.*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> Data Produk
        </a>
        <a href="{{ route('masuk.index') }}" class="nav-item {{ request()->routeIs('masuk.*') ? 'active' : '' }}">
            <i class="fas fa-arrow-down" style="color:#10b981"></i> Barang Masuk
        </a>
        <a href="{{ route('keluar.index') }}" class="nav-item {{ request()->routeIs('keluar.*') ? 'active' : '' }}">
            <i class="fas fa-arrow-up" style="color:#f59e0b"></i> Barang Keluar
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-section-label">Inventaris</div>
        <a href="{{ route('aset.index') }}" class="nav-item {{ request()->routeIs('aset.*') ? 'active' : '' }}">
            <i class="fas fa-couch"></i> Inventaris Aset
        </a>
        <div class="nav-section-label">Rusak & Retur</div>
        <a href="{{ route('rusak.index') }}" class="nav-item {{ request()->routeIs('rusak.*') ? 'active' : '' }}">
            <i class="fas fa-triangle-exclamation" style="color:#ef4444"></i> Barang Rusak & Retur
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fas fa-file-chart-column"></i> Laporan Stok
        </a>
    </div>
    <div class="sidebar-footer">
        <i class="fas fa-code-branch"></i> MVP v1.0 &bull; Laravel
    </div>
</nav>

{{-- MAIN --}}
<div class="main-wrapper">
    <div class="topbar">
        <div class="topbar-title">@yield('title', 'Dashboard')</div>
        <div class="topbar-right">
            <span style="font-size:13px;color:#64748b">
                <i class="fas fa-calendar-day"></i> {{ now()->translatedFormat('d F Y') }}
            </span>
            @auth
            <span style="font-size:13px;color:#334155;font-weight:600">
                <i class="fas fa-user-circle" style="color:var(--primary)"></i> {{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
            @endauth
            <span class="badge-store"><i class="fas fa-store"></i> {{ config('app.name') }}</span>
        </div>
    </div>

    <div class="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('danger'))
        <div class="alert alert-danger"><i class="fas fa-trash"></i> {{ session('danger') }}</div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning"><i class="fas fa-triangle-exclamation"></i> {{ session('warning') }}</div>
        @endif

        @yield('content')

    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
// Auto-hide alerts
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

// Confirm delete (form submit)
function confirmDelete(formId, msg) {
    if (confirm(msg || 'Yakin ingin menghapus data ini?')) {
        document.getElementById(formId).submit();
    }
}

// Inisialisasi Flatpickr untuk semua input bertipe date
document.addEventListener("DOMContentLoaded", function() {
    flatpickr('input[type="date"]', {
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        locale: "id"
    });
});
</script>
@stack('scripts')
</body>
</html>
