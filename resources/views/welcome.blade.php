@php
$year = date('Y');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyInternLog — Tell. Print. Smile.</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --orange: #FF6B35;
            --orange-light: #FF8F5E;
            --orange-dark: #E55A2B;
            --white: #F9FAFB;
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
            --card: #FFFFFF;
            --card2: #F3F4F6;
            --border: rgba(255,107,53,0.15);
        }
        .my-custom-element {
        /* This is the 'hidden' part (mobile-first approach) */
        display: none;
        }

        /* This is the 'md:' part (matches Tailwind's md breakpoint at 768px) */
        @media (min-width: 768px) {
        .my-custom-element {
            /* This is the 'block' part */
            display: block;
        }
        }
        * { box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; background-color: var(--gray-50); color: var(--gray-800); overflow-x: hidden; }
        .syne { font-family: 'Figtree', sans-serif; }

        nav {
            position: sticky; top: 0; z-index: 50;
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,0.95);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 14px;
            border-radius: 999px;
            border: 1.5px solid var(--orange);
            color: var(--orange);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .hero-title span.highlight {
            color: var(--orange);
            position: relative;
            display: inline-block;
        }
        .hero-title span.highlight::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0; right: 0;
            height: 4px;
            background: var(--gray-300);
            border-radius: 2px;
            transform: skewX(-6deg);
        }
        .btn-primary {
            background: var(--orange);
            color: var(--white);
            font-weight: 800;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            transition: transform 0.15s, box-shadow 0.15s;
            display: inline-flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 14px rgba(255,107,53,0.35);
            text-decoration: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
        }
        .btn-secondary {
            border: 2px solid var(--orange);
            color: var(--orange);
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 700;
            transition: border-color 0.15s, background 0.15s;
            text-decoration: none;
            background: transparent;
        }
        .btn-secondary:hover {
            border-color: var(--orange-dark);
            background: rgba(255,107,53,0.05);
        }
        .step-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .step-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(255,107,53,0.12); }
        .step-card .step-num {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            color: rgba(255,107,53,0.1);
            position: absolute;
            top: 12px; right: 20px;
        }
        .feat-card {
            background: var(--card);
            border: 1px solid rgba(255,107,53,0.12);
            border-radius: 20px;
            padding: 1.75rem;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .feat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(255,107,53,0.12); }
        .feat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: rgba(255,107,53,0.1);
            color: var(--orange);
        }
        .mockup-window {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .mockup-bar {
            background: var(--gray-100);
            padding: 10px 16px;
            display: flex; align-items: center; gap: 7px;
            border-bottom: 1px solid var(--gray-200);
        }
        .dot { width: 11px; height: 11px; border-radius: 50%; }
        .testi-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .float-tag {
            position: absolute;
            background: rgba(255,107,53,0.1);
            border: 1px solid rgba(255,107,53,0.3);
            color: var(--orange);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
            white-space: nowrap;
            animation: floatY 3s ease-in-out infinite;
        }
        @keyframes floatY {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .marquee-wrap { overflow: hidden; }
        .marquee-track {
            display: flex; gap: 2rem;
            animation: marquee 22s linear infinite;
            width: max-content;
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .marquee-item {
            display: flex; align-items: center; gap: 8px;
            color: var(--gray-400);
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .glow-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--orange), transparent);
            opacity: 0.3;
        }
        .section-label {
            font-size: 0.72rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--orange);
            font-weight: 700;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 1.5rem;
            width: 100%;
            max-width: 420px;
            margin: 1rem;
            transform: scale(0.95);
            transition: transform 0.25s;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .modal-overlay.active .modal-box { transform: scale(1); }
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: transparent;
            border: none;
            color: var(--gray-400);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            line-height: 1;
        }
        .modal-close:hover { color: var(--gray-600); }
        .modal-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-align: center;
            color: var(--gray-800);
        }
        .modal-subtitle {
            color: var(--gray-500);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.95rem;
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
        .form-group { margin-bottom: 1rem; }
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
            border-radius: 10px;
            border: 1px solid var(--gray-200);
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
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.35);
        }
        .modal-switch {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gray-500);
        }
        .modal-switch a {
            color: var(--orange);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .modal-switch a:hover { text-decoration: underline; }
        .hidden { display: none; }

        .text-muted { color: var(--gray-500); }
        .text-dark { color: var(--gray-800); }

        @media (max-width: 768px) {
            nav { position: relative; }
            .nav-desktop-links { display: none !important; }
            .mobile-nav-toggle { display: flex !important; }
            .hero-section { padding: 60px 0 80px !important; min-height: auto !important; }
            .hero-section .grid { grid-template-columns: 1fr !important; gap: 40px !important; }
            .hero-title { font-size: 2rem !important; }
            .hero-title span.highlight::after { height: 3px; }
            .hero-stats { justify-content: center !important; }
            .hero-stats > div { text-align: center; }
            .btn-primary, .btn-secondary { padding: 12px 24px; font-size: 0.9rem; }
            .float-tag { font-size: 0.6rem; padding: 3px 10px; }
            .mockup-window { margin: 0 auto; max-width: 340px; }
            .mockup-window .p-6 { padding: 14px !important; }
            .mockup-window .text-sm { font-size: 0.78rem !important; }

            .steps-section { padding: 60px 0 !important; }
            .steps-section h2 { font-size: 1.8rem !important; }
            .step-card { padding: 1.25rem !important; }
            .step-card .step-num { font-size: 2.5rem; }
            .step-card h3 { font-size: 1rem !important; }
            .step-card p { font-size: 0.78rem !important; }
            .step-card .text-3xl { font-size: 2rem !important; }

            .features-section { padding: 60px 0 !important; }
            .features-section h2 { font-size: 1.6rem !important; }
            .feat-card { padding: 1.25rem !important; }
            .feat-card h3 { font-size: 0.95rem !important; }
            .feat-card p { font-size: 0.78rem !important; }
            .feat-icon { width: 40px; height: 40px; font-size: 1.2rem; }

            .compare-section { padding: 60px 0 !important; }
            .compare-section h2 { font-size: 1.6rem !important; }
            .compare-card { padding: 1.25rem !important; }
            .compare-card .text-sm { font-size: 0.78rem !important; }
            .compare-card .text-lg { font-size: 1rem !important; }

            .testimonials-section { padding: 60px 0 !important; }
            .testimonials-section h2 { font-size: 1.6rem !important; }
            .testi-card { padding: 1.25rem !important; }
            .testi-card p { font-size: 0.78rem !important; }

            .cta-section { padding: 60px 0 !important; }
            .cta-section h2 { font-size: 1.6rem !important; }
            .cta-section .text-lg { font-size: 0.95rem !important; }
            .cta-section .btn-primary, .cta-section .btn-secondary { padding: 12px 20px; font-size: 0.85rem; }

            footer { padding: 40px 0 !important; }
            footer .flex-col { flex-direction: column !important; gap: 20px !important; text-align: center; }
            footer .flex-row { flex-direction: column !important; gap: 16px !important; }
            footer .text-xs { font-size: 0.7rem; }

            .modal-box { padding: 1.25rem !important; margin: 0.5rem !important; }
            .modal-title { font-size: 1.3rem !important; }
            .modal-subtitle { font-size: 0.82rem !important; }
            .form-group input { padding: 0.75rem 0.85rem; font-size: 0.9rem; }

            .marquee-wrap { padding: 12px 0; }
            .marquee-item { font-size: 0.75rem; }

            .section-label { font-size: 0.62rem; }
        }

        @media (max-width: 480px) {
            .hero-title { font-size: 1.7rem !important; }
            .hero-section .text-base { font-size: 0.85rem !important; }
            .float-tags-container { display: none; }
            .mockup-window { max-width: 300px; }
            .steps-grid { grid-template-columns: 1fr !important; }
            .features-grid { grid-template-columns: 1fr !important; }
            .compare-grid { grid-template-columns: 1fr !important; }
            .testimonials-grid { grid-template-columns: 1fr !important; }
            .cta-section .flex-col { gap: 12px !important; }
        }

        @media (min-width: 769px) {
            .mobile-nav-toggle { display: none !important; }
            .nav-mobile-menu { display: none !important; }
        }

        .nav-desktop-links { display: flex; }
        .mobile-nav-toggle { display: none; }
        .nav-mobile-menu { display: none; }
        .nav-mobile-menu.active { display: flex !important; flex-direction: column; position: absolute; top: 100%; left: 0; right: 0; background: white; border-bottom: 1px solid var(--gray-200); padding: 16px; gap: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <nav>
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span style="font-size:1.6rem;">📒</span>
                <span class="syne font-bold text-xl tracking-tight" style="font-weight:800;">
                    My<span style="color:var(--orange);">Kv</span>Log
                </span>
            </div>
            <div class="nav-desktop-links items-center gap-8 text-sm font-semibold" style="font-weight:600;">
                <a href="#how" class="hover:text-orange transition-colors" style="color:var(--gray-600);">Cara Berfungsi</a>
                <a href="#features" class="hover:text-orange transition-colors" style="color:var(--gray-600);">Ciri-ciri</a>
                <a href="#testimonials" class="hover:text-orange transition-colors" style="color:var(--gray-600);">Pelajar Beritahu</a>
            </div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-sm py-2.5 px-4 md:px-5 hidden md:inline-flex">Dashboard →</a>
                @else
                    <a href="#" onclick="openModal(); return false;" class="btn-primary text-sm py-2.5 px-4 md:px-5 hidden md:inline-flex">Bermula Percuma →</a>
                @endauth
            @endif
            <button class="mobile-nav-toggle" onclick="toggleMobileMenu()" style="background:none;border:none;cursor:pointer;padding:8px;border-radius:8px;" aria-label="Menu">
                <span style="font-size:1.4rem;color:var(--gray-600);">☰</span>
            </button>
        </div>
        <div id="mobileMenu" class="nav-mobile-menu md:hidden">
            <a href="#how" onclick="closeMobileMenu()" style="color:var(--gray-600);font-weight:600;font-size:0.9rem;text-decoration:none;padding:8px 0;">Cara Berfungsi</a>
            <a href="#features" onclick="closeMobileMenu()" style="color:var(--gray-600);font-weight:600;font-size:0.9rem;text-decoration:none;padding:8px 0;">Ciri-ciri</a>
            <a href="#testimonials" onclick="closeMobileMenu()" style="color:var(--gray-600);font-weight:600;font-size:0.9rem;text-decoration:none;padding:8px 0;">Pelajar Beritahu</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-sm py-2.5 px-4 text-center" style="margin-top:8px;">Dashboard →</a>
                @else
                    <a href="#" onclick="openModal(); closeMobileMenu(); return false;" class="btn-primary text-sm py-2.5 px-4 text-center" style="margin-top:8px;">Bermula Percuma →</a>
                @endauth
            @endif
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center pt-10 pb-20 overflow-hidden hero-section" style="background:linear-gradient(180deg, var(--white) 0%, var(--gray-50) 100%);">
        <div class="max-w-6xl mx-auto px-4 md:px-6 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center hero-section-grid">
                <div class="text-center lg:text-left">
                    <div class="badge mb-6 inline-flex">✨ Dikuasakan AI untuk Pelajar KV</div>

                    <h1 class="hero-title syne text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6" style="font-weight:800; color: var(--gray-800);">
                        Berhenti bersusah untuk<br>
                        <span class="highlight">Menulis Log Anda.</span><br>
                        Cuma Beritahu Kami.
                    </h1>

                    <p class="text-base md:text-lg mb-8" style="color:var(--gray-600); line-height:1.75;">
                        MyInternLog mendengar apa yang anda lakukan hari ini dan <strong style="color:var(--gray-800);">secara automatik menulis log latihan industri anda</strong> dalam ayat yang sempurna dan profesional — dengan gambar dan rajah termasuk. Selesai dalam beberapa minit.
                    </p>

                    <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                        <a href="#" onclick="openModal(); return false;" class="btn-primary">Mula Menulis Sekarang 🚀</a>
                        <a href="#how" class="btn-secondary">Lihat Cara Berfungsi</a>
                    </div>

                    <div class="mt-12 flex gap-8 flex-wrap justify-center lg:justify-start hero-stats">
                        <div>
                            <div class="syne text-2xl font-extrabold" style="color:var(--orange);font-weight:800;">500+</div>
                            <div class="text-xs" style="color:var(--gray-500);">Pelajar menggunakan</div>
                        </div>
                        <div>
                            <div class="syne text-2xl font-extrabold" style="color:var(--orange);font-weight:800;">3 mins</div>
                            <div class="text-xs" style="color:var(--gray-500);">Purata masa log</div>
                        </div>
                        <div>
                            <div class="syne text-2xl font-extrabold" style="color:var(--orange);font-weight:800;">100%</div>
                            <div class="text-xs" style="color:var(--gray-500);">Format sedia untuk CIDB</div>
                        </div>
                    </div>
                </div>

                <div class="my-custom-element">

                    <div class="relative">
                        <div class="float-tag" style="top:-18px;left:20px;animation-delay:0s;">✅ Auto-written</div>
                        <div class="float-tag" style="top:40px;right:-10px;animation-delay:1s;">📸 Photos added</div>
    
                        <div class="mockup-window" style="background:#192130;border:1px solid rgba(255,107,53,0.15);">
                            <div class="mockup-bar">
                                <div class="dot" style="background:#FF5F57;"></div>
                                <div class="dot" style="background:#FFBD2E;"></div>
                                <div class="dot" style="background:#28CA41;"></div>
                                <span class="ml-3 text-xs" style="color:#94A3B8;">MyInternLog — Penjana Log Harian</span>
                            </div>
                            <div class="p-6">
                                <div class="mb-5">
                                    <div class="text-xs mb-2" style="color:#94A3B8;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Apa yang anda buat hari ini?</div>
                                    <div class="rounded-xl p-4 text-sm leading-relaxed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,107,53,0.15);color:#94A3B8;">
                                        helped set up the server rack, labelled cables, and attended the morning briefing with the IT team...
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div style="width:8px;height:8px;border-radius:50%;background:var(--orange);animation:blink 0.6s ease infinite;"></div>
                                    <span class="text-xs" style="color:var(--orange);font-weight:700;">AI menulis log anda...</span>
                                </div>
                                <div class="rounded-xl p-4 text-sm leading-relaxed" style="background:rgba(255,107,53,0.05);border:1px solid rgba(255,107,53,0.2);color:#CBD5E1;">
                                    <strong style="color:var(--orange);">Log Entry — {{ now()->format('d/m/Y') }}</strong><br><br>
                                    On this date, I assisted the IT Department in the physical installation and organisation of the server rack within the data centre. Tasks performed included routing and labelling network cables according to the assigned colour-coding system to ensure clarity and ease of maintenance. Additionally, I participated in the morning briefing conducted by the IT supervisor, during which updates on the current network infrastructure project were discussed...
                                </div>
                                <div class="flex items-center justify-between mt-5">
                                    <div class="flex gap-2">
                                        <span class="text-xs px-3 py-1 rounded-lg" style="background:rgba(56,189,248,0.1);color:#38BDF8;border:1px solid rgba(56,189,248,0.2);">📸 2 Foto</span>
                                        <span class="text-xs px-3 py-1 rounded-lg" style="background:rgba(255,107,53,0.1);color:#FF9F7A;border:1px solid rgba(255,107,53,0.2);">📊 1 Rajah</span>
                                    </div>
                                    <button class="btn-primary text-xs py-2 px-4">🖨️ Cetak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="glow-line"></div>
    <div class="py-5 marquee-wrap" style="background:rgba(255,107,53,0.03);">
        <div class="marquee-track">
            @php
            $items = ['Pembantu Penulisan AI','Integrasi Foto','Rajah Auto','Cetak Sekali Klik','Format Log KV','Cepat & Mudah','Beritahu → Cetak → Tersenyum','Tiada Lagi Blok Penulis','Keputusan Profesional'];
            $items = array_merge($items, $items);
            @endphp
            @foreach($items as $item)
                <div class="marquee-item">
                    <span style="color:var(--orange);font-size:0.6rem;">◆</span>
                    {{ $item }}
                </div>
            @endforeach
        </div>
    </div>
    <div class="glow-line"></div>

    <section id="how" class="py-16 md:py-28 relative overflow-hidden steps-section" style="background:var(--white);">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="text-center mb-12 md:mb-16 reveal">
                <div class="section-label mb-3">Cara Berfungsi</div>
                <h2 class="syne text-4xl lg:text-5xl font-extrabold mb-4" style="font-weight:800; color: var(--gray-800);">
                    3 Langkah. Tiada Tekanan.
                </h2>
                <p style="color:var(--gray-500);max-width:480px;margin:0 auto;">Dari idea kasar ke entri buku log yang kemas dan boleh dicetak — dalam masa kurang dari 3 minit.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                $steps_data = [
                    ['01','📣','Beritahu MyInternLog','Cuma sebut atau taip apa yang anda buat hari ini dalam bahasa mudah. Tiada risau tatabahasa, tiada format — cuma idea mentah anda.', 'var(--orange)'],
                    ['02','🤖','AI Menulisnya','AI kami mengembangkan input anda menjadi entri log yang terperinci dan profesional dalam format latihan KV yang betul — serta-merta.', 'var(--gray-600)'],
                    ['03','🖨️','Cetak & Tersenyum','Tambah foto anda, dapat rajah yang dijana secara auto, kompil log mingguan anda, dan tekan Cetak. Selesai. Penyelia impressed.', 'var(--orange)'],
                ];
                @endphp
                @foreach($steps_data as $i => $step)
                <div class="step-card reveal" style="transition-delay: {{ $i * 0.12 }}s">
                    <div class="step-num text-center md:text-right">{{ $step[0] }}</div>
                    <div class="text-3xl md:text-4xl mb-4 text-center">{{ $step[1] }}</div>
                    <h3 class="syne text-xl font-bold mb-3" style="color:{{ $step[4] }};font-weight:700;">{{ $step[2] }}</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--gray-500);">{{ $step[3] }}</p>
                </div>
                @endforeach
            </div>

            <div class="hidden md:flex justify-center items-center gap-6 mt-8">
                @for ($i = 0; $i < 3; $i++)
                <div class="flex-1 h-px" style="background:linear-gradient(90deg,transparent,rgba(255,107,53,0.3),transparent);"></div>
                @if ($i < 2)
                <div style="color:var(--orange);font-size:1.5rem;font-weight:800;">→</div>
                @endif
                @endfor
            </div>
        </div>
    </section>

    <section id="features" class="py-16 md:py-28 features-section" style="background:var(--gray-50);">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="text-center mb-12 md:mb-16 reveal">
                <div class="section-label mb-3">Ciri-ciri</div>
                <h2 class="syne text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4" style="font-weight:800; color: var(--gray-800);">
                    Dibina untuk Pelajar KV,<br>oleh orang yang faham.
                </h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 features-grid">
                @php
                $features = [
                    ['🧠','Penulis Log AI','Tampal atau sebut hari anda dalam ayat yang pecah. MyInternLog menulis semula secara automatik menjadi bahasa Inggeris yang sempurna dan profesional.'],
                    ['📸','Integrasi Foto','Lampirkan foto tapak anda dan AI meletakkannya di sebelah entri log yang berkaitan.'],
                    ['📊','Rajah Auto','Perihalkan aliran kerja dan получите rajah yang kemas yang dijana secara auto untuk dilampirkan pada log anda. Tiada Visio diperlukan.'],
                    ['📋','Format CIDB/KV','Output sudah diformat pra untuk sepadan dengan struktur buku log latihan KV anda — sedia untuk dihantar.'],
                    ['📅','Kompil Mingguan','Entri dari minggu dikompil secara automatik menjadi satu log mingguan yang kemas dan bernombor halaman.'],
                    ['🖨️','Cetak Sekali Klik','Cetak terus dari pelayar dengan margin sempurna, header, dan nombor halaman. Tiada alat tambahan.'],
                ];
                @endphp
                @foreach($features as $i => $feat)
                <div class="feat-card reveal" style="transition-delay:{{ $i*0.08 }}s">
                    <div class="feat-icon">{{ $feat[0] }}</div>
                    <h3 class="syne font-bold text-lg mb-2" style="font-weight:700; color: var(--gray-800);">{{ $feat[1] }}</h3>
                    <p class="text-sm leading-relaxed" style="color:var(--gray-500);">{{ $feat[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 md:py-28 relative overflow-hidden compare-section" style="background:var(--white);">
        <div class="max-w-5xl mx-auto px-4 md:px-6">
            <div class="text-center mb-14 reveal">
                <div class="section-label mb-3">Perbezaannya</div>
                <h2 class="syne text-4xl font-extrabold" style="font-weight:800; color: var(--gray-800);">Sebelum & Selepas MyInternLog</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8 compare-grid">
                <div class="rounded-2xl p-6 compare-card" style="background:rgba(255,107,53,0.05);border:1.5px solid rgba(255,107,53,0.2);">
                    <div class="flex items-center gap-2 mb-5">
                        <span style="font-size:1.3rem;">😰</span>
                        <span class="syne font-bold text-lg" style="color:var(--orange);font-weight:700;">Without MyInternLog</span>
                    </div>
                    @php
                    $befores = [
                        'Menatap halaman kosong selama 20 minit',
                        '"Hari ini saya buat… erm… benda server"',
                        'Lupa apa yang anda buat 3 hari lalu',
                        'Hantar log 2 minggu lambat',
                        'Penyelia tandakan "Tidak cukup terperinci"',
                        'Miss kehendak foto lagi 📸❌',
                    ];
                    @endphp
                    @foreach($befores as $b)
                    <div class="flex items-start gap-3 mb-3">
                        <span style="color:var(--orange);margin-top:2px;">✗</span>
                        <span class="text-sm" style="color:var(--gray-600);">{{ $b }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="rounded-2xl p-6 compare-card" style="transition-delay:0.15s;background:rgba(255,107,53,0.08);border:1.5px solid rgba(255,107,53,0.3);">
                    <div class="flex items-center gap-2 mb-5">
                        <span style="font-size:1.3rem;">🎉</span>
                        <span class="syne font-bold text-lg" style="color:var(--orange);font-weight:700;">With MyInternLog</span>
                    </div>
                    @php
                    $afters = [
                        'Buka app → beritahu apa yang anda buat → siap',
                        'Ditulis dengan sempurna dalam kurang dari 3 minit',
                        'Entri harian auto-disimpan, sentiasa di landasan',
                        'Log mingguan dikompil secara automatik',
                        '"Butiran cemerlang!" — setiap penyelia',
                        'Foto dan rajah termasuk secara automatik 📸✅',
                    ];
                    @endphp
                    @foreach($afters as $a)
                    <div class="flex items-start gap-3 mb-3">
                        <span style="color:var(--orange);margin-top:2px;">✓</span>
                        <span class="text-sm" style="color:var(--gray-700);">{{ $a }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-16 md:py-28 testimonials-section" style="background:var(--gray-50);">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="text-center mb-16 reveal">
                <div class="section-label mb-3">Pelajar Beritahu</div>
                <h2 class="syne text-4xl font-extrabold" style="font-weight:800; color: var(--gray-800);">Pelajar KV Sebenar, Keputusan Sebenar 🎓</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                $testis = [
                    ['Hafizuddin R.', 'IT Networking, KV Ipoh', '⭐⭐⭐⭐⭐', 'Dulu makan masa 1 jam nak tulis log. Sekarang 3 minit je. Supervisor cakap log saya paling detail dalam batch! 🔥'],
                    ['Nurul Ain M.', 'Electrical Engineering, KV Pasir Mas', '⭐⭐⭐⭐⭐', 'The AI is so smart, I just said "fixed broken socket and labelled wires" and it wrote 3 paragraphs of perfect log. I cried (happy tears) 😭'],
                    ['Syafiq I.', 'Auto Mechanics, KV Temerloh', '⭐⭐⭐⭐⭐', 'The photo feature is amazing. I just upload pictures from my phone and they go right into the log next to the right task. Memang terbaik!'],
                ];
                @endphp
                @foreach($testis as $i => $t)
                <div class="testi-card reveal" style="transition-delay:{{ $i*0.12 }}s">
                    <div class="text-2xl mb-3">{{ $t[2] }}</div>
                    <p class="text-sm leading-relaxed mb-5" style="color:var(--gray-600);">"{{ $t[3] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold" style="background:rgba(255,107,53,0.1);color:var(--orange);">
                            {{ strtoupper(substr($t[0],0,1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold" style="color:var(--gray-800);">{{ $t[0] }}</div>
                            <div class="text-xs" style="color:var(--gray-400);">{{ $t[1] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="cta" class="py-20 md:py-32 relative overflow-hidden cta-section" style="background:linear-gradient(135deg, var(--orange) 0%, #FF8F5E 100%);">
        <div class="max-w-3xl mx-auto px-4 md:px-6 text-center relative z-10 reveal">
            <div class="text-4xl md:text-5xl mb-4 md:mb-6">🚀</div>
            <h2 class="syne text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 md:mb-6" style="font-weight:800; color: var(--white);">
                Penyelia Anda Akan<br>
                <span style="color:var(--white);">Sebenarnya Terpesona.</span>
            </h2>
            <p class="text-base md:text-lg mb-8" style="color:rgba(255,255,255,0.9);">
                Sertai 500+ pelajar KV yang berhenti takut hari log. Ia percuma untuk bermula — tiada kad kredit, tiada persediaan yang rumit.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 flex-col">
                <a href="#" onclick="openModal(); return false;" class="btn-primary text-lg py-4 px-8" style="background:var(--white);color:var(--orange);">📒 Mula Menulis Percuma</a>
                <a href="#how" class="btn-secondary text-lg py-4 px-8" style="border-color:var(--white);color:var(--white);">Tonton Demo →</a>
            </div>

            <p class="text-xs" style="color:rgba(255,255,255,0.8);">✓ Percuma untuk semua pelajar KV &nbsp;·&nbsp; ✓ Berfungsi di telefon &nbsp;·&nbsp; ✓ Output sedia cetak</p>
        </div>
    </section>

    <footer style="background:var(--gray-800);">
        <div class="max-w-6xl mx-auto px-6 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-2">
                    <span style="font-size:1.4rem;">📒</span>
                    <span class="syne font-bold text-lg" style="font-weight:800; color: var(--white);">
                        My<span style="color:var(--orange);">Kv</span>Log
                    </span>
                    <span class="text-xs ml-2" style="color:var(--gray-400);">Beritahu → Cetak → Tersenyum</span>
                </div>

                <div class="flex gap-8 text-sm" style="color:var(--gray-400);">
                    <a href="#" class="hover:text-white transition-colors">Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat</a>
                    <a href="#" class="hover:text-white transition-colors">Hubungi</a>
                    <a href="#" class="hover:text-white transition-colors">FAQ</a>
                </div>

                <div class="text-xs" style="color:var(--gray-400);">
                    © {{ $year }} MyInternLog. Dibuat dengan ❤️ untuk pelajar KV di Malaysia.
                </div>
            </div>
        </div>
    </footer>

    <div id="authModal" class="modal-overlay">
        <div class="modal-box relative">
            <button class="modal-close" onclick="closeModal()">&times;</button>

            <div id="loginForm">
                <div class="modal-title">Selamat Kembali! 👋</div>
                <div class="modal-subtitle">Daftar masuk untuk terus menulis log anda</div>

                <a href="{{ route('auth.google.redirect') }}" class="btn-google" style="text-decoration:none;">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Teruskan dengan Google
                </a>

                <div class="divider">atau terus dengan emel</div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label>Alamat Emel</label>
                        <input type="email" name="email" placeholder="anda@contoh.com" required />
                    </div>
                    <div class="form-group">
                        <label>Kata Laluan</label>
                        <input type="password" name="password" placeholder="Masukkan kata laluan anda" required />
                    </div>
                    <button type="submit" class="btn-submit">Daftar Masuk</button>
                </form>

                <div class="modal-switch">
                    Tiada akaun? <a onclick="showSignup()">Daftar percuma</a>
                </div>
            </div>

            <div id="signupForm" class="hidden">
                <div class="modal-title">Bermula Percuma! 🚀</div>
                <div class="modal-subtitle">Buat akaun anda dan mula menulis log</div>

                <a href="{{ route('auth.google.redirect') }}" class="btn-google" style="text-decoration:none;">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Teruskan dengan Google
                </a>

                <div class="divider">atau terus dengan emel</div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label>Nama Penuh</label>
                        <input type="text" name="name" placeholder="Nama penuh anda" required />
                    </div>
                    <div class="form-group">
                        <label>Alamat Emel</label>
                        <input type="email" name="email" placeholder="anda@contoh.com" required />
                    </div>
                    <div class="form-group">
                        <label>Kata Laluan</label>
                        <input type="password" name="password" placeholder="Buat kata laluan" required />
                    </div>
                    <button type="submit" class="btn-submit">Buat Akaun</button>
                </form>

                <div class="modal-switch">
                    Sudah ada akaun? <a onclick="showLogin()">Daftar masuk</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('authModal');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function showSignup() {
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
        }

        function showLogin() {
            signupForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        }

        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('active');
        }

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.remove('active');
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        @if(session('google_email_exists') || session('google_already_logged_in') || session('google_error'))
            window.addEventListener('load', function() {
                let icon = '⚠️';
                let title = 'Emel Sudah Daftar';
                let message = 'Emel ini sudah daftar dengan kata laluan. Sila daftar masuk dengan emel dan kata laluan instead.';

                @if(session('google_already_logged_in'))
                    icon = 'ℹ️';
                    title = 'Anda Sudah Daftar Masuk';
                    message = 'Anda sudah daftar masuk. Sila log keluar dulu untuk login dengan Google.';
                @elseif(session('google_error'))
                    icon = '❌';
                    title = 'Ralat Google';
                    message = '{{ session('google_error') }}';
                @endif

                const msgModal = document.createElement('div');
                msgModal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(8px);z-index:9999;display:flex;align-items:center;justify-content:center;';
                msgModal.innerHTML = '<div style="background:white;border-radius:20px;padding:2rem;max-width:360px;width:90%;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,0.15);"><div style="font-size:3rem;margin-bottom:12px;">' + icon + '</div><h3 style="font-size:1.2rem;font-weight:800;color:#1F2937;margin-bottom:8px;">' + title + '</h3><p style="color:#6B7280;font-size:0.9rem;margin-bottom:20px;">' + message + '</p><button onclick="this.parentElement.parentElement.remove();document.body.style.overflow=\'\'" style="background:#FF6B35;color:white;border:none;border-radius:10px;padding:12px 24px;font-weight:700;cursor:pointer;">Okay</button></div>';
                document.body.appendChild(msgModal);
                document.body.style.overflow = 'hidden';
            });
        @endif

        const reveals = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.12 });
        reveals.forEach(r => io.observe(r));

        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            });
        });
    </script>
</body>
</html>