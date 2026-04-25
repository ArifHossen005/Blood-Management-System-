<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blood Management System')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
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
        }

        * { font-family: 'Noto Sans Bengali', sans-serif; }

        body {
            background: #f4f6f9;
            min-height: 100vh;
        }

        /* ─── Sidebar ──────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--blood-red);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .sidebar-brand h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.3;
        }
        .sidebar-nav {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }
        .sidebar-nav .nav-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-nav a.active {
            background: rgba(220,20,60,0.15);
            color: var(--blood-red);
            border-left-color: var(--blood-red);
            font-weight: 600;
        }
        .sidebar-nav a i { font-size: 1.1rem; width: 22px; text-align: center; }

        /* ─── Main Content ─────────────────────────── */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ─── Top Bar ──────────────────────────────── */
        .topbar {
            background: #fff;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8e8e8;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .topbar .page-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: #1a1a2e;
        }
        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--blood-light);
        }

        /* ─── Cards ────────────────────────────────── */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: none;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: #888;
            margin-top: 4px;
        }

        .content-area { padding: 1.5rem; }

        /* ─── Table ────────────────────────────────── */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        .table-card .table { margin-bottom: 0; }
        .table-card .table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            border-bottom: 2px solid #dee2e6;
        }
        .table-card .table td { vertical-align: middle; font-size: 0.9rem; }

        /* ─── Badges ───────────────────────────────── */
        .badge-blood {
            background: var(--blood-red);
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 6px;
        }
        .badge-approved { background: #28a745; }
        .badge-temporary { background: #ffc107; color: #333; }
        .badge-rejected { background: #dc3545; }
        .badge-banned { background: #343a40; }

        /* ─── Donor Card ───────────────────────────── */
        .donor-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
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
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            margin-top: -35px;
            background: #fff;
        }
        .donor-card .blood-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.95);
            color: var(--blood-red);
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        /* ─── Modal ────────────────────────────────── */
        .profile-modal .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: none;
        }
        .profile-modal .modal-header-bg {
            background: linear-gradient(135deg, var(--blood-red), var(--blood-dark));
            padding: 2rem;
            text-align: center;
            color: #fff;
        }
        .profile-modal .modal-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            object-fit: cover;
        }
        .profile-modal .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .profile-modal .info-row:last-child { border-bottom: none; }
        .profile-modal .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
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
        .btn-blood {
            background: var(--blood-red);
            color: #fff;
            border: none;
            font-weight: 600;
        }
        .btn-blood:hover { background: var(--blood-dark); color: #fff; }
        .btn-blood-outline {
            border: 2px solid var(--blood-red);
            color: var(--blood-red);
            background: transparent;
            font-weight: 600;
        }
        .btn-blood-outline:hover { background: var(--blood-red); color: #fff; }

        /* ─── Status indicator ─────────────────────── */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
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
        // Sidebar toggle for mobile
        document.querySelectorAll('.sidebar-toggler').forEach(el => {
            el.addEventListener('click', () => document.querySelector('.sidebar').classList.toggle('active'));
        });
    </script>
    @stack('scripts')
</body>
</html>
