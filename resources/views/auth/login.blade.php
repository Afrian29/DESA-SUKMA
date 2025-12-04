<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Desa Sukma</title>
    
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
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --color-accent: #FCA311;
        }

        html {
            font-size: 0.9rem;
        }

        body {
            font-family: var(--bs-body-font-family);
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            /* Background Image with Overlay */
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2832&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        /* Dark Overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(11, 47, 94, 0.9) 0%, rgba(11, 47, 94, 0.7) 100%);
            z-index: 0;
        }

        /* Login Card - Solid & Clean */
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Header Styling */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrapper {
            width: 70px;
            height: 70px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 3px solid white;
        }

        /* Custom Input Group Wrapper */
        .custom-input-group {
            display: flex;
            align-items: center;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background-color: white;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .custom-input-group:focus-within {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 4px rgba(11, 47, 94, 0.1);
        }

        .custom-input-group.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .custom-input-group.is-invalid:focus-within {
            border-color: #dc3545;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
        }

        .custom-input-group.is-invalid .input-icon-wrapper {
            color: #dc3545;
        }

        .input-icon-wrapper {
            padding: 0 0 0 16px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .custom-input-group:focus-within .input-icon-wrapper {
            color: var(--bs-primary);
        }

        .custom-input-group .form-control {
            border: none;
            box-shadow: none;
            background: transparent;
            padding: 14px 16px;
            font-weight: 600;
            color: var(--bs-primary);
        }

        .custom-input-group .form-control::placeholder {
            color: #cbd5e1;
            font-weight: 500;
        }

        /* Button Styling */
        .btn-primary-custom {
            background-color: var(--bs-primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(11, 47, 94, 0.2);
        }

        .btn-primary-custom:hover {
            background-color: #092347;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(11, 47, 94, 0.3);
        }

        /* Animations */
        .reveal {
            opacity: 0;
            transform: scale(0.95);
            animation: popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes popIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>

    <div class="login-card reveal">
        <div class="login-header">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo-bone-bolango.png') }}" alt="Logo" height="40">
            </div>
            <h5 class="fw-bold text-primary mb-1">Selamat Datang Kembali!</h5>
            <p class="text-muted small">Masuk untuk mengelola data desa.</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase text-muted ls-1" style="font-size: 0.75rem;">Username</label>
                <div class="custom-input-group @error('username') is-invalid @enderror">
                    <div class="input-icon-wrapper">
                        <i data-lucide="user" style="width: 20px;"></i>
                    </div>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                </div>
                @error('username')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase text-muted ls-1" style="font-size: 0.75rem;">Password</label>
                <div class="custom-input-group @error('password') is-invalid @enderror">
                    <div class="input-icon-wrapper">
                        <i data-lucide="lock" style="width: 20px;"></i>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label small text-muted fw-semibold" for="remember">
                        Ingat Saya
                    </label>
                </div>
                <a href="#" class="text-decoration-none small fw-bold text-primary">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-primary-custom mb-4">
                Masuk Dashboard
            </button>

            <div class="text-center">
                <a href="/" class="text-decoration-none text-muted small d-inline-flex align-items-center hover-primary fw-semibold">
                    <i data-lucide="arrow-left" style="width: 14px; margin-right: 6px;"></i> Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
