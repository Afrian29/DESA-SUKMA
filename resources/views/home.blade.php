<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Sukma - Demo Profil Desa Modern</title>
    
    <!-- Fonts: Plus Jakarta Sans (Modern & Clean) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons: Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* --- CUSTOM THEME OVERRIDES --- */
        :root {
            /* Mengganti warna Primary Bootstrap default dengan Biru Tua PRD */
            --bs-primary: #0B2F5E;
            --bs-primary-rgb: 11, 47, 94;
            
            /* Font Family Global */
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --bs-body-color: #334155;
            
            /* Custom Colors */
            --color-accent: #FCA311;
            --color-accent-light: #ffb845;
            --color-surface: #F9FAFB;
        }

        html {
            font-size: 0.9rem; /* Scale down overall UI */
            scroll-behavior: smooth;
            scroll-padding-top: 25px; /* Offset for fixed navbar */
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: #fff;
            overflow-x: hidden;
            position: relative; /* Required for ScrollSpy */
        }

        /* --- UTILITIES & ACCENTS --- */
        .text-accent { color: var(--color-accent) !important; }
        .bg-accent { background-color: var(--color-accent) !important; }
        .bg-surface { background-color: var(--color-surface); }
        
        .btn-accent {
            background-color: var(--color-accent);
            color: var(--bs-primary);
            font-weight: 700;
            border: none;
            padding: 12px 30px;
            border-radius: 50px; /* Pill shape */
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(252, 163, 17, 0.3);
        }
        
        .btn-accent:hover {
            background-color: var(--color-accent-light);
            color: var(--bs-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(252, 163, 17, 0.4);
        }

        /* Override Bootstrap Button Primary */
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #092347 !important; /* Darker shade of #0B2F5E */
            border-color: #092347 !important;
        }

        .btn-outline-light-custom {
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            backdrop-filter: blur(4px);
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
        }

        .btn-outline-light-custom:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            border-color: white;
        }

        /* --- NAVBAR CUSTOM --- */
        .navbar {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .navbar-brand .logo-box {
            width: 40px;
            height: 40px;
            background-color: var(--bs-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            margin-right: 10px;
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            margin: 0 10px;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--bs-primary) !important;
        }
        
        /* Underline animation for nav */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: var(--color-accent);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 80%;
        }

        /* Fix conflict between Bootstrap caret and custom underline */
        .nav-link.dropdown-toggle::after {
            border: none !important;
            margin-left: 0 !important;
            vertical-align: baseline !important;
            content: '' !important; /* Ensure it's treated as our underline */
        }

        /* --- PREMIUM DROPDOWN --- */
        .dropdown-menu {
            display: block;
            visibility: hidden;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 10px;
            margin-top: 15px !important; /* Gap from navbar */
        }

        /* Show on hover for desktop */
        @media (min-width: 992px) {
            .nav-item.dropdown:hover .dropdown-menu {
                visibility: visible;
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #1e293b;
            transform: translateX(5px);
        }

        .dropdown-item.active {
            background-color: #f8f9fa;
            color: #1e293b;
            font-weight: 700;
        }

        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            min-height: 100vh;
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2832&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding-top: 80px; /* Offset for fixed navbar */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(11, 47, 94, 0.95) 0%, rgba(11, 47, 94, 0.6) 100%);
        }

        /* --- CARDS & STATS --- */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
            border-bottom: 4px solid var(--bs-primary);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.accent-border {
            border-bottom-color: var(--color-accent);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-blue-soft { background-color: #e0f2fe; color: var(--bs-primary); }
        .bg-yellow-soft { background-color: #fef3c7; color: var(--color-accent); }

        /* --- NEWS CARDS --- */
        .news-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .news-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .news-img-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .news-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-img-wrapper img {
            transform: scale(1.05);
        }

        /* --- ANIMATIONS --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Removing default Bootstrap outline on focus */
        .btn:focus, .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(11, 47, 94, 0.25);
        }

        /* --- INSTITUTION CARDS --- */
        .institution-card {
            border: 1px solid rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: white;
        }
        .institution-card:hover {
            border-color: var(--bs-primary);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(11, 47, 94, 0.1) !important;
        }
        .institution-card .icon-wrapper {
            transition: all 0.3s ease;
            background-color: var(--color-surface);
            color: var(--bs-primary);
        }
        .institution-card:hover .icon-wrapper {
            background-color: var(--bs-primary);
            color: white;
        }
        .institution-card:hover .icon-wrapper i {
            color: white !important; /* Force icon color change */
        }

        /* --- GROUP HOVER ANIMATIONS --- */
        .group-hover-card:hover .group-hover-scale {
            transform: scale(1.05);
        }
        .group-hover-card:hover .group-hover-spin {
            transform: translate(-50%, -50%) rotate(180deg);
            opacity: 0.5 !important;
        }
    </style>
</head>
<body tabindex="0">

    <!-- NAVBAR -->
    <nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo-bone-bolango.png') }}" alt="Logo Bone Bolango" height="40" class="me-2">
                <div class="d-flex flex-column">
                    <span class="fw-bold text-primary lh-1">Desa Sukma</span>
                    <span class="text-secondary text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Kabupaten Bone Bolango</span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#visi-misi">Visi & Misi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lembaga">Lembaga</a></li>
                    <li class="nav-item"><a class="nav-link" href="#perangkat">Aparat</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                        @auth
                            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary px-4 fw-bold d-flex align-items-center gap-2 border-end border-white border-opacity-25">
                                    <i data-lucide="layout-dashboard" style="width: 18px;"></i> Dashboard
                                </a>
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form-home').submit();"
                                   class="btn btn-primary px-3 d-flex align-items-center hover-bg-danger transition-colors"
                                   title="Logout">
                                    <i data-lucide="log-out" style="width: 18px;"></i>
                                </a>
                            </div>
                                <form id="logout-form-home" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <i data-lucide="log-in" style="width: 18px;"></i> Login
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="beranda" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container position-relative text-white z-1">
            <div class="row">
                <div class="col-lg-7 reveal active">
                    <div class="d-inline-flex align-items-center border border-white border-opacity-25 rounded-pill px-3 py-1 mb-4 bg-white bg-opacity-10 backdrop-blur-sm">
                        <span class="bg-warning rounded-circle d-inline-block me-2" style="width: 8px; height: 8px;"></span>
                        <small class="text-uppercase fw-bold ls-1" style="font-size: 0.7rem; letter-spacing: 1px;">Website Resmi Pemerintah Desa</small>
                    </div>
                    
                    <h1 class="display-3 fw-bold mb-4 lh-sm">
                        Mewujudkan Desa <br>
                        <span class="text-accent position-relative d-inline-block">
                            Mandiri & Maju
                            <!-- SVG Underline decoration -->
                            <svg class="position-absolute start-0 w-100" style="bottom: -5px; height: 10px; opacity: 0.7;" viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 50 10 100 5" stroke="#FCA311" stroke-width="3" fill="none" />
                            </svg>
                        </span>
                    </h1>
                    
                    <p class="lead mb-5 text-light opacity-75" style="max-width: 600px;">
                        Jendela informasi Desa Sukma. Media resmi untuk mengenal lebih dekat profil, visi-misi, dan perkembangan desa kami secara akurat dan terpercaya.
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="#" class="btn btn-accent btn-lg">
                            Jelajahi Desa
                        </a>
                        <a href="#" class="btn btn-outline-light-custom btn-lg d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="file-text" style="width: 20px;"></i> Layanan Online
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4 text-white opacity-50">
            <i data-lucide="arrow-down" class="animate-bounce"></i>
        </div>
    </section>

    <!-- STATISTICS (Floating Section) -->
    <section class="position-relative z-2" style="margin-top: -80px; margin-bottom: 80px;">
        <div class="container">
            <div class="row g-4">
                <!-- Stat 1 -->
                <div class="col-md-4 reveal">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Total Penduduk</small>
                                <h2 class="display-5 fw-bold text-primary mt-1 mb-0">2.450</h2>
                            </div>
                            <div class="icon-box bg-blue-soft">
                                <i data-lucide="users"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                        </div>
                        <small class="text-muted d-block text-end mt-2">Update: Jan 2025</small>
                    </div>
                </div>
                
                <!-- Stat 2 -->
                <div class="col-md-4 reveal">
                    <div class="stat-card accent-border">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Luas Wilayah</small>
                                <h2 class="display-5 fw-bold text-primary mt-1 mb-0">145 <span class="fs-5 text-muted">Ha</span></h2>
                            </div>
                            <div class="icon-box bg-yellow-soft">
                                <i data-lucide="map"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-accent" role="progressbar" style="width: 100%"></div>
                        </div>
                        <small class="text-muted d-block text-end mt-2">Wilayah Produktif</small>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="col-md-4 reveal">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Unit UMKM</small>
                                <h2 class="display-5 fw-bold text-primary mt-1 mb-0">32</h2>
                            </div>
                            <div class="icon-box bg-blue-soft">
                                <i data-lucide="shopping-bag"></i>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 60%"></div>
                        </div>
                        <small class="text-muted d-block text-end mt-2">Aktif Beroperasi</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SAMBUTAN KEPALA DESA -->
    <section class="py-5 bg-surface overflow-hidden">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                <!-- Image Column -->
                <div class="col-lg-6 position-relative reveal">
                    <!-- Decor elements -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-warning bg-opacity-10 rounded-4 transform-rotate" style="transform: rotate(-3deg);"></div>
                    
                    <img src="https://images.unsplash.com/photo-1556157382-97eda2d62296?q=80&w=2940&auto=format&fit=crop" class="img-fluid rounded-4 shadow-lg position-relative w-100 object-fit-cover" style="height: 500px;" alt="Kepala Desa">
                    
                    <div class="position-absolute bottom-0 start-0 m-4 p-4 bg-white bg-opacity-90 backdrop-blur rounded-3 shadow border-start border-4 border-primary" style="max-width: 80%;">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Kepala Desa</small>
                        <h4 class="fw-bold text-primary m-0">Bapak H. Sutrisno</h4>
                    </div>
                </div>

                <!-- Text Column -->
                <div class="col-lg-6 reveal">
                    <h2 class="display-6 fw-bold text-primary mb-4">Membangun Desa Dengan Hati, Melayani Dengan Integritas.</h2>
                    <p class="lead text-muted mb-4">
                        "Website ini adalah wujud komitmen kami terhadap transparansi dan pelayanan prima. Di era digital ini, Desa Sukma siap bertransformasi menjadi desa cerdas yang adaptif terhadap perubahan zaman."
                    </p>
                    
                    <div class="d-flex align-items-center gap-3 mb-5">
                        <div class="bg-accent rounded-pill" style="width: 5px; height: 50px;"></div>
                        <p class="fst-italic text-secondary m-0">"Gotong royong adalah kunci kemajuan kita bersama."</p>
                    </div>
                    
                    <a href="#" class="btn btn-link text-primary fw-bold text-decoration-none p-0 d-inline-flex align-items-center">
                        Baca Profil Lengkap <i data-lucide="arrow-right" class="ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI SECTION -->
    <!-- VISI & MISI SECTION (Redesigned) -->
    <!-- VISI & MISI SECTION (Redesigned) -->
    <section id="visi-misi" class="py-5 bg-primary position-relative overflow-hidden min-vh-100 d-flex align-items-center">
        <!-- Background Decor -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top right, rgba(252, 163, 17, 0.15), transparent 40%);"></div>
        
        <div class="container py-lg-5 position-relative z-1">
            <div class="row g-5">
                <!-- Visi Column -->
                <div class="col-lg-4 reveal">
                    <div class="pe-lg-4">
                        <small class="text-accent fw-bold text-uppercase ls-1 mb-2 d-block">Arah Pembangunan</small>
                        <h2 class="fw-bold text-white mb-4">Visi & Misi Desa</h2>
                        <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10" style="backdrop-filter: blur(10px);">
                            <i data-lucide="quote" class="text-accent mb-3" style="width: 32px;"></i>
                            <p class="lead text-white mb-0 fst-italic">
                                "Terwujudnya Desa Sukma yang Mandiri, Maju, dan Sejahtera Berlandaskan Gotong Royong."
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Misi Column -->
                <div class="col-lg-8 reveal">
                    <div class="row g-4">
                        <!-- Misi 1 -->
                        <div class="col-md-6">
                            <div class="stat-card h-100 border-0">
                                <div class="icon-box bg-blue-soft mb-3">
                                    <i data-lucide="shield-check"></i>
                                </div>
                                <h5 class="fw-bold text-primary">Tata Kelola Transparan</h5>
                                <p class="text-muted small mb-0">Mewujudkan pemerintahan desa yang akuntabel, terbuka, dan melayani masyarakat.</p>
                            </div>
                        </div>
                        <!-- Misi 2 -->
                        <div class="col-md-6">
                            <div class="stat-card h-100 border-0">
                                <div class="icon-box bg-yellow-soft mb-3">
                                    <i data-lucide="graduation-cap"></i>
                                </div>
                                <h5 class="fw-bold text-primary">SDM Unggul</h5>
                                <p class="text-muted small mb-0">Meningkatkan kualitas pendidikan dan kesehatan yang terjangkau bagi semua.</p>
                            </div>
                        </div>
                        <!-- Misi 3 -->
                        <div class="col-md-6">
                            <div class="stat-card h-100 border-0">
                                <div class="icon-box bg-yellow-soft mb-3">
                                    <i data-lucide="trending-up"></i>
                                </div>
                                <h5 class="fw-bold text-primary">Ekonomi Kerakyatan</h5>
                                <p class="text-muted small mb-0">Mengembangkan potensi UMKM dan pariwisata berbasis kearifan lokal.</p>
                            </div>
                        </div>
                        <!-- Misi 4 -->
                        <div class="col-md-6">
                            <div class="stat-card h-100 border-0">
                                <div class="icon-box bg-blue-soft mb-3">
                                    <i data-lucide="sprout"></i>
                                </div>
                                <h5 class="fw-bold text-primary">Infrastruktur Hijau</h5>
                                <p class="text-muted small mb-0">Pembangunan berkelanjutan yang memperhatikan kelestarian lingkungan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LEMBAGA DESA SECTION -->
    <section id="lembaga" class="py-5 bg-surface">
        <div class="container py-lg-5">
            <div class="text-center mb-5 reveal">
                <small class="text-accent fw-bold text-uppercase ls-1">Struktur & Mitra</small>
                <h2 class="fw-bold text-primary mt-1">Lembaga Desa</h2>
                <p class="text-muted mx-auto mt-2" style="max-width: 600px;">
                    Bersinergi membangun desa melalui berbagai lembaga kemasyarakatan yang aktif dan produktif.
                </p>
            </div>
            
            <div class="row g-4 justify-content-center">
                @forelse($institutions as $institution)
                <div class="col-md-6 col-lg-4 reveal fade-bottom" style="transition-delay: {{ $loop->index * 100 }}ms">
                    <div class="card h-100 border-0 shadow-sm hover-lift transition-all p-4 rounded-4 text-center group-hover-card bg-white">
                        <div class="mb-4 d-inline-block position-relative">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-4 transition-transform group-hover-scale">
                                <i data-lucide="{{ $institution->icon }}" class="text-primary" style="width: 40px; height: 40px;"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-primary mb-2">{{ $institution->abbr }}</h4>
                        <h6 class="text-dark fw-bold mb-3">{{ $institution->name }}</h6>
                        <p class="text-muted small mb-0">{{ $institution->description }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data lembaga desa.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- APARAT DESA SECTION -->
    <section id="perangkat" class="py-5 bg-white">
        <div class="container py-lg-5">
            <div class="text-center mb-5 reveal">
                <small class="text-accent fw-bold text-uppercase ls-1">Pemerintahan Desa</small>
                <h2 class="fw-bold text-primary mt-1">Aparat Desa</h2>
                <p class="text-muted mx-auto mt-2" style="max-width: 600px;">
                    Mengenal lebih dekat jajaran perangkat desa yang siap melayani kebutuhan administrasi dan pembangunan.
                </p>
            </div>
            
            <div class="row g-4 justify-content-center">
                @foreach($officials as $staff)
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 hover-lift transition-all bg-white group-hover-card">
                        <div class="mb-4 position-relative d-inline-block mx-auto">
                            <div class="rounded-circle p-1 bg-white border border-2 border-primary border-opacity-25 shadow-sm position-relative z-1 transition-transform group-hover-scale">
                                <img src="{{ $staff->photo ? asset($staff->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) . '&background=0B2F5E&color=fff' }}" alt="{{ $staff->name }}" class="rounded-circle object-fit-cover" style="width: 120px; height: 120px;">
                            </div>
                            <!-- Decorative Ring -->
                            <div class="position-absolute top-50 start-50 translate-middle border border-accent opacity-25 rounded-circle z-0 transition-all group-hover-spin" style="width: 135px; height: 135px; border-style: dashed !important;"></div>
                        </div>
                        
                        <h5 class="fw-bold text-primary mb-2 fs-6">{{ $staff->name }}</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold small ls-1 text-uppercase">{{ $staff->position }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- GALERI SECTION -->
    <section id="galeri" class="py-5 bg-surface position-relative overflow-hidden">
        <div class="container py-lg-5 position-relative z-1">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-end mb-5 gap-3 reveal">
                <div>
                    <small class="text-accent fw-bold text-uppercase ls-1">Dokumentasi</small>
                    <h2 class="fw-bold text-primary mt-1 mb-0">Galeri Kegiatan</h2>
                </div>
                <a href="#" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2">
                    Lihat Semua <i data-lucide="arrow-right" style="width: 18px;"></i>
                </a>
            </div>

            @if($galleries->count() > 0)
            <div id="galleryCarousel" class="carousel slide reveal fade-bottom" data-bs-ride="carousel">
                <div class="carousel-inner rounded-4 shadow-lg overflow-hidden">
                    @foreach($galleries as $gallery)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="position-relative" style="height: 500px;">
                            <img src="{{ asset($gallery->image) }}" class="d-block w-100 h-100 object-fit-cover" alt="{{ $gallery->title }}">
                            <div class="position-absolute bottom-0 start-0 w-100 p-5 bg-gradient-dark text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <span class="badge bg-warning text-dark mb-2">
                                                <i data-lucide="calendar" class="me-1" style="width: 14px;"></i> {{ $gallery->date ? \Carbon\Carbon::parse($gallery->date)->format('d M Y') : '-' }}
                                            </span>
                                            <h3 class="fw-bold mb-2">{{ $gallery->title }}</h3>
                                            <p class="lead mb-0 text-white-50">{{ $gallery->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">Belum ada foto galeri.</p>
            </div>
            @endif
        </div>
    </section>
    <!-- FOOTER -->
    <footer id="kontak" class="bg-primary text-white py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('images/logo-bone-bolango.png') }}" alt="Logo Bone Bolango" height="40" class="me-2">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-white lh-1 fs-5">Desa Sukma</span>
                            <span class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Kabupaten Bone Bolango</span>
                        </div>
                    </div>
                    <p class="text-white-50 mb-4">Website resmi Desa Sukma, Kecamatan Sukma, Kabupaten Bone Bolango. Media informasi dan transparansi publik.</p>
                    
                    <h6 class="fw-bold mb-3 text-accent">Kontak Kami</h6>
                    <p class="text-white-50 mb-1"><i data-lucide="map-pin" class="me-2" style="width: 16px;"></i> Jl. Raya Sukma No. 12, Bone Bolango</p>
                    <p class="text-white-50 mb-1"><i data-lucide="phone" class="me-2" style="width: 16px;"></i> (022) 123-4567</p>
                    <p class="text-white-50 mb-1"><i data-lucide="mail" class="me-2" style="width: 16px;"></i> admin@sukma.desa.id</p>
                </div>
                
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3 text-accent">Menu Utama</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#beranda" class="text-white-50 text-decoration-none hover-white">Beranda</a></li>
                        <li class="mb-2"><a href="#visi-misi" class="text-white-50 text-decoration-none hover-white">Visi & Misi</a></li>
                        <li class="mb-2"><a href="#lembaga" class="text-white-50 text-decoration-none hover-white">Lembaga</a></li>
                        <li class="mb-2"><a href="#perangkat" class="text-white-50 text-decoration-none hover-white">Aparat</a></li>
                        <li class="mb-2"><a href="#galeri" class="text-white-50 text-decoration-none hover-white">Galeri</a></li>
                    </ul>
                </div>

                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3 text-accent">Peta Lokasi</h6>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="height: 250px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127669.04951167664!2d123.1234567!3d0.5678901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMMKwMzQnMDQuNCJOIDEyM8KwMDcnMjQuNCJF!5e0!3m2!1sen!2sid!4v1635735600000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="border-top border-white border-opacity-10 mt-5 pt-4 text-center text-white-50 small">
                &copy; 2025 Pemerintah Desa Sukma. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Initialize Icons
        lucide.createIcons();

        // Custom ScrollSpy using IntersectionObserver
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section, footer');
            const navLinks = document.querySelectorAll('.nav-link');
            
            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -50% 0px', // Adjusted for better active state detection
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Remove active class from all links
                        navLinks.forEach(link => link.classList.remove('active'));
                        
                        // Add active class to corresponding link
                        const id = entry.target.getAttribute('id');
                        if (id) {
                            const activeLink = document.querySelector(`.nav-link[href="#${id}"]`);
                            if (activeLink) {
                                activeLink.classList.add('active');
                            }
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                observer.observe(section);
            });
        });

        // Navbar Scroll Effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-sm');
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                navbar.style.paddingTop = '0.5rem';
                navbar.style.paddingBottom = '0.5rem';
            } else {
                navbar.classList.remove('shadow-sm');
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.paddingTop = '1rem';
                navbar.style.paddingBottom = '1rem';
            }
        });

        // Simple Reveal Animation
        const reveals = document.querySelectorAll('.reveal');
        
        function revealOnScroll() {
            const windowHeight = window.innerHeight;
            const elementVisible = 150;
            
            reveals.forEach((reveal) => {
                const elementTop = reveal.getBoundingClientRect().top;
                if (elementTop < windowHeight - elementVisible) {
                    reveal.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', revealOnScroll);
        // Trigger once on load
        revealOnScroll();
    </script>
</body>
</html>
