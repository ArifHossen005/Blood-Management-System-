<!DOCTYPE html>
<html lang="{{ $supportedLocales[$appLocale]['html_lang'] ?? $appLocale }}" data-theme="{{ $appTheme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('ui.app.full_name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --blood-red: #DC143C;
            --blood-dark: #8B0000;
            --blood-light: #FFE4E8;
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: #16213e;
            --sidebar-active: #DC143C;
            --card-shadow: 0 2px 12px rgba(0,0,0,0.08);

            /* theme-aware */
            --body-bg: #f4f6f9;
            --card-bg: #ffffff;
            --card-border: #e8e8e8;
            --text-primary: #212529;
            --text-secondary: #555;
            --text-muted: #6c757d;
            --table-head-bg: #f8f9fa;
            --table-row-hover: rgba(0,0,0,0.03);
            --input-bg: #ffffff;
            --input-border: #ced4da;
            --divider: #f0f0f0;
        }

        [data-theme="dark"] {
            --blood-light: #3a1a24;
            --sidebar-bg: #0b0d15;
            --sidebar-hover: #151826;
            --card-shadow: 0 2px 12px rgba(0,0,0,0.4);

            --body-bg: #0f1117;
            --card-bg: #1a1d26;
            --card-border: #2a2d38;
            --text-primary: #e4e6eb;
            --text-secondary: #b0b4be;
            --text-muted: #8b90a0;
            --table-head-bg: #212533;
            --table-row-hover: rgba(255,255,255,0.04);
            --input-bg: #252834;
            --input-border: #3a3d48;
            --divider: #2a2d38;
        }

        * { font-family: 'Noto Sans Bengali', system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }

        body {
            background: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 0.2s, color 0.2s;
        }

        /* ─── Sidebar ──────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.25rem;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: var(--blood-red); border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .sidebar-brand h5 { margin: 0; font-weight: 700; font-size: 1rem; line-height: 1.3; }
        .sidebar-nav { padding: 1rem 0; list-style: none; margin: 0; }
        .sidebar-nav .nav-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.4); font-weight: 600;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.7); text-decoration: none;
            font-size: 0.9rem; transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-nav a.active {
            background: rgba(220,20,60,0.15); color: var(--blood-red);
            border-left-color: var(--blood-red); font-weight: 600;
        }
        .sidebar-nav a i { font-size: 1.1rem; width: 22px; text-align: center; }

        /* ─── Main Content ─────────────────────────── */
        .main-content { margin-left: 260px; min-height: 100vh; }

        /* ─── Top Bar ──────────────────────────────── */
        .topbar {
            background: var(--card-bg);
            color: var(--text-primary);
            padding: 0.85rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--card-border);
            position: sticky; top: 0; z-index: 999;
        }
        .topbar .page-title { font-weight: 700; font-size: 1.15rem; color: var(--text-primary); }
        .topbar .user-info { display: flex; align-items: center; gap: 10px; }
        .topbar .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            object-fit: cover; border: 2px solid var(--blood-light);
        }
        .topbar-btn {
            background: transparent; border: 1px solid var(--card-border);
            color: var(--text-primary);
            width: 36px; height: 36px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: background-color 0.15s, border-color 0.15s;
        }
        .topbar-btn:hover { background: var(--table-row-hover); }
        .topbar-btn i { font-size: 1rem; }

        /* ─── Cards ────────────────────────────────── */
        .stat-card {
            background: var(--card-bg); color: var(--text-primary);
            border-radius: 12px; padding: 1.25rem;
            box-shadow: var(--card-shadow); border: none;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }

        .content-area { padding: 1.5rem; }

        /* ─── Table ────────────────────────────────── */
        .table-card {
            background: var(--card-bg); color: var(--text-primary);
            border-radius: 12px; box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        .table-card .table { margin-bottom: 0; color: var(--text-primary); }
        .table-card .table th {
            background: var(--table-head-bg);
            font-weight: 600; font-size: 0.82rem;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--card-border);
        }
        .table-card .table td { vertical-align: middle; font-size: 0.9rem; border-color: var(--card-border); }
        .table-card .table.table-hover > tbody > tr:hover > * {
            background: var(--table-row-hover);
            color: var(--text-primary);
        }

        /* ─── Badges ───────────────────────────────── */
        .badge-blood {
            background: var(--blood-red); color: #fff;
            font-weight: 700; font-size: 0.85rem;
            padding: 4px 10px; border-radius: 6px;
        }
        .badge-approved { background: #28a745; }
        .badge-temporary { background: #ffc107; color: #333; }
        .badge-rejected { background: #dc3545; }
        .badge-banned { background: #343a40; }

        /* ─── Donor Card ───────────────────────────── */
        .donor-card {
            background: var(--card-bg); color: var(--text-primary);
            border-radius: 14px; box-shadow: var(--card-shadow);
            overflow: hidden; transition: all 0.3s;
            cursor: pointer; border: 2px solid transparent;
        }
        .donor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(220,20,60,0.15);
            border-color: var(--blood-light);
        }
        .donor-card .card-header-bg {
            height: 60px;
            background: linear-gradient(135deg, var(--blood-red), var(--blood-dark));
        }
        .donor-card .donor-avatar {
            width: 70px; height: 70px; border-radius: 50%;
            border: 3px solid var(--card-bg); object-fit: cover;
            margin-top: -35px; background: var(--card-bg);
        }
        .donor-card .blood-badge {
            position: absolute; top: 10px; right: 10px;
            background: rgba(255,255,255,0.95); color: var(--blood-red);
            font-weight: 800; padding: 4px 10px;
            border-radius: 8px; font-size: 0.9rem;
        }

        /* ─── Modal ────────────────────────────────── */
        .profile-modal .modal-content {
            border-radius: 16px; overflow: hidden; border: none;
            background: var(--card-bg); color: var(--text-primary);
        }
        .profile-modal .modal-header-bg {
            background: linear-gradient(135deg, var(--blood-red), var(--blood-dark));
            padding: 2rem; text-align: center; color: #fff;
        }
        .profile-modal .modal-avatar {
            width: 100px; height: 100px; border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3); object-fit: cover;
        }
        .profile-modal .info-row {
            display: flex; align-items: center; gap: 12px;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--divider);
        }
        .profile-modal .info-row:last-child { border-bottom: none; }
        .profile-modal .info-icon {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }

        /* ─── Responsive ───────────────────────────── */
        .sidebar-toggler { display: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggler { display: block; }
        }

        /* ─── Btn ──────────────────────────────────── */
        .btn-blood { background: var(--blood-red); color: #fff; border: none; font-weight: 600; }
        .btn-blood:hover { background: var(--blood-dark); color: #fff; }
        .btn-blood-outline {
            border: 2px solid var(--blood-red); color: var(--blood-red);
            background: transparent; font-weight: 600;
        }
        .btn-blood-outline:hover { background: var(--blood-red); color: #fff; }

        /* ─── Status dot ───────────────────────────── */
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            display: inline-block; margin-right: 6px;
        }
        .status-dot.available { background: #28a745; }
        .status-dot.unavailable { background: #dc3545; }

        /* ─── Alert ────────────────────────────────── */
        .alert-blood {
            background: var(--blood-light);
            border: 1px solid rgba(220,20,60,0.2);
            color: var(--blood-dark);
            border-radius: 10px;
        }
        [data-theme="dark"] .alert-blood { color: #ffb3be; }

        /* ─── Dark-mode overrides for Bootstrap bits ───────── */
        [data-theme="dark"] .text-muted { color: var(--text-muted) !important; }
        [data-theme="dark"] .bg-light { background-color: var(--table-head-bg) !important; color: var(--text-primary) !important; }
        [data-theme="dark"] .border-top, [data-theme="dark"] .border-bottom { border-color: var(--card-border) !important; }
        [data-theme="dark"] hr { border-color: var(--card-border); opacity: 1; }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-primary);
            border-color: var(--input-border);
        }
        [data-theme="dark"] .form-control::placeholder { color: var(--text-muted); }
        [data-theme="dark"] .form-control:disabled,
        [data-theme="dark"] .form-control[readonly] { background-color: var(--table-head-bg); color: var(--text-muted); }
        [data-theme="dark"] .form-check-input { background-color: var(--input-bg); border-color: var(--input-border); }
        [data-theme="dark"] .form-check-input:checked { background-color: var(--blood-red); border-color: var(--blood-red); }
        [data-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg); color: var(--text-primary);
            border-color: var(--card-border);
        }
        [data-theme="dark"] .dropdown-item { color: var(--text-primary); }
        [data-theme="dark"] .dropdown-item:hover,
        [data-theme="dark"] .dropdown-item:focus { background-color: var(--table-row-hover); color: var(--text-primary); }
        [data-theme="dark"] .dropdown-divider { border-color: var(--card-border); }
        [data-theme="dark"] .page-link {
            background-color: var(--card-bg); color: var(--text-primary);
            border-color: var(--card-border);
        }
        [data-theme="dark"] .page-item.active .page-link { background-color: var(--blood-red); border-color: var(--blood-red); color: #fff; }
        [data-theme="dark"] .page-item.disabled .page-link { background-color: var(--card-bg); color: var(--text-muted); }
        [data-theme="dark"] .list-group-item { background-color: var(--card-bg); color: var(--text-primary); border-color: var(--card-border); }
        [data-theme="dark"] .modal-content { background-color: var(--card-bg); color: var(--text-primary); }
        [data-theme="dark"] .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
        [data-theme="dark"] code { color: #e99ca8; background: transparent; }
        [data-theme="dark"] .alert-success { background-color: #123a1f; color: #b7e5c2; border-color: #1d5e33; }
        [data-theme="dark"] .alert-danger { background-color: #3f1419; color: #f5b7bd; border-color: #66202a; }
        [data-theme="dark"] .alert-warning { background-color: #3a2d0a; color: #ffe08a; border-color: #5e491a; }
        [data-theme="dark"] .alert-info { background-color: #0f2a3a; color: #9fd4ea; border-color: #1a4a66; }
    </style>
    @stack('styles')
</head>
<body>
    @auth
        @include('layouts.sidebar')
    @endauth

    <div class="@auth main-content @endauth">
        @auth
            @include('layouts.topbar')
        @endauth

        <div class="@auth content-area @endauth">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.sidebar-toggler').forEach(el => {
            el.addEventListener('click', () => document.querySelector('.sidebar').classList.toggle('active'));
        });
    </script>
    @stack('scripts')
</body>
</html>
