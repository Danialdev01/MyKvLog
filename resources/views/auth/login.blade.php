<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log Masuk - MyInternLog</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --orange: #FF6B35;
            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
        }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            margin: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-icon { font-size: 2.5rem; }
        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-800);
        }
        .logo-text span { color: var(--orange); }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            border: 1.5px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-800);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .form-group input::placeholder { color: var(--gray-400); }
        .error-message {
            color: var(--orange);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .btn-submit {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            border: none;
            background: var(--orange);
            color: var(--white);
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            margin-top: 0.5rem;
            box-shadow: 0 4px 14px rgba(255,107,53,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: var(--gray-400);
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            border: 1.5px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-google:hover {
            border-color: var(--orange);
            background: rgba(255,107,53,0.02);
        }
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gray-500);
        }
        .form-footer a {
            color: var(--orange);
            font-weight: 600;
            text-decoration: none;
        }
        .form-footer a:hover { text-decoration: underline; }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        .remember-me input { accent-color: var(--orange); }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <div class="logo-icon">📒</div>
            <div class="logo-text">My<span>Kv</span>Log</div>
        </div>

        @if ($errors->any())
            <div style="background:rgba(255,107,53,0.05);border:1px solid rgba(255,107,53,0.3);border-radius:12px;padding:1rem;margin-bottom:1.5rem;">
                @foreach ($errors->all() as $error)
                    <div class="error-message">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('auth.google.redirect') }}" class="btn-google" style="text-decoration:none;">
            <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Teruskan dengan Google
        </a>

        <div class="divider">atau terus dengan emel</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Alamat Emel</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="anda@contoh.com" required autofocus autocomplete="username" />
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata laluan anda" required autocomplete="current-password" />
            </div>

            <div class="form-group">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember_me" />
                    Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn-submit">Daftar Masuk</button>
        </form>

        <div class="form-footer">
            Tiada akaun? <a href="{{ route('register') }}">Daftar percuma</a>
        </div>
    </div>
</body>
</html>