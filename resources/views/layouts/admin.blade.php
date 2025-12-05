<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Desa Sukma</title>

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons: Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bs-primary: #0B2F5E;
            --bs-primary-rgb: 11, 47, 94;
            --color-accent: #FCA311;
            --color-surface: #F1F5F9; /* Light Gray Blue */
            --sidebar-width: 90px; /* Compact Sidebar */
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--color-surface);
            color: #334155;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background-color: white;
            border-right: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            align-items: center; /* Center items */
            padding: 2rem 0;
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }

        .sidebar-brand {
            margin-bottom: 3rem;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .nav-item {
            margin-bottom: 1rem;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .nav-link {
            color: #94a3b8;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            position: relative;
        }

        .nav-link:hover {
            color: var(--bs-primary);
            background-color: rgba(11, 47, 94, 0.05);
        }

        .nav-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 8px 20px rgba(11, 47, 94, 0.25);
        }

        /* Tooltip style for nav links */
        .nav-link::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(10px);
            background: #1e293b;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            pointer-events: none;
            z-index: 1001;
        }

        .nav-link:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(15px);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0 2rem 2rem 2rem;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 999;
            background-color: rgba(241, 245, 249, 0.85); /* var(--color-surface) with opacity */
            backdrop-filter: blur(8px);
            padding-top: 2rem;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .main-content {
                margin-left: 70px;
                padding: 0 1rem 1rem 1rem;
            }
            .top-header {
                padding-top: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }
            .top-header > div {
                width: 100%;
                justify-content: space-between;
                display: flex;
                align-items: center;
            }
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Utilities */
        .text-primary { color: var(--bs-primary) !important; }
        .bg-primary { background-color: var(--bs-primary) !important; }
        .text-accent { color: var(--color-accent) !important; }
        
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover {
            background-color: #092347;
            border-color: #092347;
        }

        /* Dropdown Item Style to match Sidebar */
        .dropdown-item {
            color: #94a3b8;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            color: var(--bs-primary);
            background-color: rgba(11, 47, 94, 0.05);
        }
        .dropdown-item.active, .dropdown-item:active {
            color: var(--bs-primary);
            background-color: rgba(11, 47, 94, 0.1);
            font-weight: 600;
        }
        /* Custom Profile Tabs */
        .profile-tabs {
            background-color: white;
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: center; /* Center align items */
            gap: 0.5rem;
            overflow-x: auto;
            scrollbar-width: none; /* Hide scrollbar Firefox */
            margin-bottom: 1.5rem;
        }
        .profile-tabs::-webkit-scrollbar {
            display: none; /* Hide scrollbar Chrome/Safari */
        }
        .profile-tab-link {
            color: #64748b;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            background: transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center; /* Center content within the full-width tab */
            gap: 0.5rem;
            font-size: 0.95rem;
            flex: 1; /* Make tabs fill available space */
        }
        .profile-tab-link:hover {
            color: var(--bs-primary);
            background-color: rgba(11, 47, 94, 0.05);
        }
        .profile-tab-link.active {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(11, 47, 94, 0.15);
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/logo-bone-bolango.png') }}" alt="Logo" height="40">
        </a>

        <div class="nav flex-column w-100">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i data-lucide="layout-grid" style="width: 22px;"></i>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('penduduk.index') }}" class="nav-link {{ request()->routeIs('penduduk.*') ? 'active' : '' }}" title="Data Penduduk">
                    <i data-lucide="users" style="width: 22px;"></i>
                </a>
            </div>

            <div class="nav-item dropend">
                <a href="#" class="nav-link {{ request()->routeIs('mutasi.*') ? 'active' : '' }}" title="Data Mutasi" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-lucide="arrow-left-right" style="width: 22px;"></i>
                </a>
                <ul class="dropdown-menu border-0 shadow-lg p-2 rounded-2" style="margin-left: 10px;">
                    <li><a class="dropdown-item rounded-1 small py-2 {{ request()->routeIs('mutasi.create') ? 'active' : '' }}" href="{{ route('mutasi.create') }}">Lapor Mutasi</a></li>
                    <li><a class="dropdown-item rounded-1 small py-2 {{ request()->routeIs('mutasi.index') ? 'active' : '' }}" href="{{ route('mutasi.index') }}">Lihat Tabel Mutasi</a></li>
                </ul>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.profile.index') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" title="Profil Desa">
                    <i data-lucide="building-2" style="width: 22px;"></i>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" title="Lihat Website" target="_blank">
                    <i data-lucide="globe" style="width: 22px;"></i>
                </a>
            </div>
        </div>

        <div class="mt-auto nav-item">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="nav-link text-danger" title="Logout">
                <i data-lucide="log-out" style="width: 22px;"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div>
                <h4 class="fw-bold mb-0 text-primary">@yield('header-title', 'Dashboard')</h4>
                <p class="text-secondary small mb-0">@yield('header-subtitle', 'Welcome back, ' . (Auth::user()->name ?? 'Admin'))</p>
            </div>

            <div class="d-flex align-items-center gap-3">
                @yield('header-action')
                
                <div class="user-profile">
                <button class="btn btn-light rounded-circle p-2 shadow-sm me-2 text-secondary">
                    <i data-lucide="bell" style="width: 20px;"></i>
                </button>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle gap-3" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-md-block">
                            <span class="d-block fw-bold text-primary small">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <span class="d-block text-secondary" style="font-size: 0.7rem;">Administrator</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=FCA311&color=fff" alt="Avatar" class="avatar">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-2 mt-3 p-2">
                        <li><a class="dropdown-item rounded-1" href="#">Profile</a></li>
                        <li><a class="dropdown-item rounded-1" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger rounded-1" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
