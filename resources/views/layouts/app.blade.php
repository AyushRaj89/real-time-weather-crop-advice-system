<!DOCTYPE html>
{{-- File: resources/views/layouts/app.blade.php --}}
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CropCast') — Real-Time Weather & Crop Advisory</title>

    {{-- Google Fonts: Syne (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        /* ─── Design Tokens ─────────────────────────────── */
        :root {
            --bg-base:       #0a0f0d;
            --bg-card:       #111a15;
            --bg-card-2:     #162019;
            --bg-hover:      #1c2b21;
            --border:        #243328;
            --border-light:  #2e4035;

            --green-dim:     #2d6a4f;
            --green-mid:     #40916c;
            --green-bright:  #52b788;
            --green-glow:    #74c69d;
            --green-pale:    #b7e4c7;

            --amber:         #f4a261;
            --amber-dim:     #e76f51;
            --sky:           #48cae4;
            --red-alert:     #e63946;
            --gold:          #ffd166;

            --text-primary:  #e8f5e9;
            --text-secondary:#8aad96;
            --text-muted:    #4d6b57;

            --font-display:  'Syne', sans-serif;
            --font-body:     'DM Sans', sans-serif;

            --radius-sm:     6px;
            --radius-md:     12px;
            --radius-lg:     20px;
            --radius-xl:     28px;

            --shadow-card:   0 4px 24px rgba(0,0,0,0.5), 0 1px 2px rgba(0,0,0,0.3);
            --shadow-glow:   0 0 40px rgba(82,183,136,0.12);
            --transition:    all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── Reset & Base ──────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(64,145,108,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 30% at 80% 80%, rgba(82,183,136,0.04) 0%, transparent 50%);
        }

        /* ─── Scrollbar ─────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--green-dim); border-radius: 3px; }

        /* ─── Navbar ────────────────────────────────────── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 64px;
            background: rgba(10,15,13,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-glow));
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: 18px;
        }

        .navbar-name {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .navbar-name span { color: var(--green-bright); }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--green-bright);
            background: rgba(82,183,136,0.08);
        }

        .nav-link i { width: 15px; height: 15px; }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px 5px 5px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 100px;
        }

        .user-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--green-dim), var(--green-mid));
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
        }

        .user-name {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 0.8rem;
            font-family: var(--font-body);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-logout:hover {
            border-color: var(--amber-dim);
            color: var(--amber);
            background: rgba(231,111,81,0.08);
        }

        /* ─── Page Wrapper ──────────────────────────────── */
        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 2rem 4rem;
        }

        /* ─── Cards ─────────────────────────────────────── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition);
        }

        .card:hover { border-color: var(--border-light); }

        .card-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.25rem;
        }

        .card-title i { color: var(--green-bright); width: 16px; height: 16px; }

        /* ─── Buttons ────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--green-mid), var(--green-bright));
            color: #fff;
            box-shadow: 0 4px 15px rgba(64,145,108,0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--green-bright), var(--green-glow));
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(64,145,108,0.4);
        }

        .btn-secondary {
            background: var(--bg-card-2);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--green-mid);
            color: var(--green-bright);
            background: rgba(82,183,136,0.06);
        }

        .btn-danger {
            background: rgba(230,57,70,0.12);
            color: var(--red-alert);
            border: 1px solid rgba(230,57,70,0.25);
        }

        .btn-danger:hover {
            background: rgba(230,57,70,0.2);
            border-color: var(--red-alert);
        }

        .btn-sm { padding: 6px 14px; font-size: 0.8rem; border-radius: var(--radius-sm); }
        .btn i { width: 15px; height: 15px; }

        /* ─── Form Elements ──────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 0.9rem;
            transition: var(--transition);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 3px rgba(64,145,108,0.15);
        }

        .form-control::placeholder { color: var(--text-muted); }

        select.form-control { cursor: pointer; }

        .form-error {
            font-size: 0.8rem;
            color: var(--red-alert);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ─── Alerts / Flash ─────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: rgba(82,183,136,0.1); border: 1px solid rgba(82,183,136,0.3); color: var(--green-bright); }
        .alert-danger  { background: rgba(230,57,70,0.1);  border: 1px solid rgba(230,57,70,0.3);  color: #ff6b7a; }
        .alert-warning { background: rgba(244,162,97,0.1); border: 1px solid rgba(244,162,97,0.3); color: var(--amber); }
        .alert-info    { background: rgba(72,202,228,0.1); border: 1px solid rgba(72,202,228,0.3); color: var(--sky); }

        /* ─── Badge ──────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .badge-green   { background: rgba(82,183,136,0.15); color: var(--green-bright); border: 1px solid rgba(82,183,136,0.25); }
        .badge-amber   { background: rgba(244,162,97,0.15); color: var(--amber);        border: 1px solid rgba(244,162,97,0.25); }
        .badge-sky     { background: rgba(72,202,228,0.15); color: var(--sky);          border: 1px solid rgba(72,202,228,0.25); }
        .badge-red     { background: rgba(230,57,70,0.15);  color: #ff6b7a;             border: 1px solid rgba(230,57,70,0.25); }
        .badge-muted   { background: rgba(77,107,87,0.2);   color: var(--text-muted);   border: 1px solid var(--border); }

        /* ─── Table ──────────────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .data-table thead th {
            padding: 10px 16px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        .data-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }
        .data-table tbody tr:last-child { border-bottom: none; }
        .data-table tbody tr:hover { background: rgba(82,183,136,0.04); }
        .data-table tbody td { padding: 12px 16px; color: var(--text-secondary); vertical-align: middle; }
        .data-table tbody td:first-child { color: var(--text-primary); font-weight: 500; }

        /* ─── Page Header ────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-header h1 {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 2px;
        }

        /* ─── Divider ────────────────────────────────────── */
        hr { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }

        /* ─── Responsive ─────────────────────────────────── */
        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .page-wrapper { padding: 1rem 1rem 3rem; }
            .navbar-nav { display: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ─── Navbar ────────────────────────────────────────────────── --}}
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <div class="navbar-logo">🌾</div>
        <span class="navbar-name">Crop<span>Cast</span></span>
    </a>

    @auth
    <ul class="navbar-nav">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
        </li>
        @if(auth()->user()->isAdmin())
        <li>
            <a href="{{ route('admin.crops.index') }}"
               class="nav-link {{ request()->routeIs('admin.crops.*') ? 'active' : '' }}">
                <i data-lucide="leaf"></i> Crops
            </a>
        </li>
        <li>
            <a href="{{ route('admin.rules.index') }}"
               class="nav-link {{ request()->routeIs('admin.rules.*') ? 'active' : '' }}">
                <i data-lucide="sliders-horizontal"></i> Rules
            </a>
        </li>
        @endif
    </ul>

    <div class="navbar-user">
        <div class="user-chip">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span class="user-name">{{ auth()->user()->name }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i data-lucide="log-out" style="width:13px;height:13px;"></i>
                Logout
            </button>
        </form>
    </div>
    @endauth
</nav>

{{-- ─── Flash Messages ─────────────────────────────────────────── --}}
@if(session('success') || session('error'))
<div style="max-width:1280px;margin:0 auto;padding:1rem 2rem 0;">
    @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif
</div>
@endif

{{-- ─── Page Content ───────────────────────────────────────────── --}}
@yield('content')

<script>
    // Init Lucide icons
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
</script>

@stack('scripts')
</body>
</html>