{{--
    Shell layout untuk semua halaman selepas login.
    Sidebar + topbar + toast dikongsi di sini; halaman hanya membekalkan
    @section('title'), @section('breadcrumb'), @section('content'),
    dan @push('styles') / @push('scripts') untuk keperluan khusus halaman.
--}}
@php
    $shellUser = Auth::user();
    $shellDefaults = $shellUser->defaults;
    $shellInitial = strtoupper(substr($shellUser->user_email, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MyKvLog')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}">
    @stack('styles')
</head>
<body>
    <aside id="sidebar" aria-label="Navigasi utama">
        <div class="sidebar-logo">
            <span class="logo-icon" aria-hidden="true">📒</span>
            <span class="logo-text">My<span>Kv</span>Log</span>
        </div>

        <div class="nav-section-label">Menu</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           @if(request()->routeIs('dashboard')) aria-current="page" @endif>
            <span class="nav-icon" aria-hidden="true">🏠</span>
            Papan Pemuka
        </a>
        <a href="{{ route('logs.index') }}"
           class="nav-item {{ request()->routeIs('logs.index') ? 'active' : '' }}"
           @if(request()->routeIs('logs.index')) aria-current="page" @endif>
            <span class="nav-icon" aria-hidden="true">📋</span>
            Log Saya
        </a>
        <a href="{{ route('print') }}"
           class="nav-item {{ request()->routeIs('print') ? 'active' : '' }}"
           @if(request()->routeIs('print')) aria-current="page" @endif>
            <span class="nav-icon" aria-hidden="true">🖨️</span>
            Cetak
        </a>

        <div class="nav-divider"></div>
        <div class="nav-section-label">Akaun</div>
        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
           @if(request()->routeIs('profile.edit')) aria-current="page" @endif>
            <span class="nav-icon" aria-hidden="true">👤</span>
            Profil
        </a>

        <div class="sidebar-profile">
            <div class="profile-wrapper">
                <div class="avatar" aria-hidden="true">{{ $shellInitial }}</div>
                <div class="avatar-info">
                    <div class="name">{{ $shellUser->user_email }}</div>
                    <div class="role">{{ $shellDefaults->default_department ?? 'Pelajar KV' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="nav-icon" aria-hidden="true">🚪</span>
                    Daftar Keluar
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div id="main">
        <div id="topbar">
            <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                <button id="sidebar-toggle" onclick="toggleSidebar()" aria-label="Buka/tutup menu sisi">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <span class="topbar-breadcrumb">
                    <strong>@yield('breadcrumb', 'MyKvLog')</strong>
                </span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date-pill">📅 {{ date('d/m/Y') }}</span>
                <a href="{{ route('profile.edit') }}" class="avatar topbar-avatar" title="Profil" aria-label="Pergi ke profil">{{ $shellInitial }}</a>
            </div>
        </div>

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <div id="toast-container" aria-live="polite"></div>

    <script src="{{ asset('js/shell.js') }}"></script>
    @stack('scripts')
</body>
</html>
