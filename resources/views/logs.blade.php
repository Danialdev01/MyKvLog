@php
$user = Auth::user();
$today = date('d/m/Y');
$monthYear = date('F Y');
$defaultSettings = $user->defaults;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyInternLog — My Logs</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --orange: #FF6B35;
            --orange-light: #FF8F5E;
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
            --sidebar-w: 260px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: var(--gray-50); color: var(--gray-800); overflow-x: hidden; }
        .syne { font-family: 'Figtree', sans-serif; }

        #sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        }
        #sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo .logo-icon { font-size: 1.6rem; }
        .sidebar-logo .logo-text { font-size: 1.15rem; font-weight: 800; color: var(--gray-800); }
        .sidebar-logo .logo-text span { color: var(--orange); }

        .nav-section-label {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--gray-400);
            padding: 18px 20px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px; margin: 2px 10px;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none; color: var(--gray-600);
            font-weight: 600; font-size: 0.9rem;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }
        .nav-item:hover { background: rgba(255,107,53,0.07); color: var(--gray-800); }
        .nav-item.active {
            background: rgba(255,107,53,0.1);
            color: var(--orange);
            border: 1px solid rgba(255,107,53,0.2);
        }
        .nav-item .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }
        .nav-item .nav-arrow { margin-left: auto; font-size: 0.7rem; opacity: 0; transition: opacity 0.15s; }
        .nav-item:hover .nav-arrow { opacity: 1; }

        .nav-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gray-200), transparent);
            margin: 12px 20px;
        }

        .sidebar-profile {
            margin-top: auto;
            padding: 16px;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }
        .profile-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: var(--white);
            border: 1px solid var(--gray-200);
        }
        .avatar {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--orange), var(--orange-light));
            color: var(--white); font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(255,107,53,0.3);
        }
        .avatar-info .name { font-size: 0.8rem; font-weight: 700; color: var(--gray-800); }
        .avatar-info .role { font-size: 0.65rem; color: var(--gray-400); }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            margin-top: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1.5px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-500);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            border-color: #EF4444;
            color: #EF4444;
            background: rgba(239,68,68,0.05);
        }
        .logout-btn .nav-icon { font-size: 1rem; }

        #main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
            position: relative; z-index: 1;
        }
        #main.expanded { margin-left: 0; }

        #topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        #sidebar-toggle {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,107,53,0.08); border: 1px solid var(--gray-200);
            cursor: pointer; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 4px;
            transition: background 0.15s;
        }
        #sidebar-toggle:hover { background: rgba(255,107,53,0.15); }
        #sidebar-toggle span {
            display: block; width: 16px; height: 2px;
            background: var(--orange); border-radius: 2px;
            transition: transform 0.25s, opacity 0.25s;
        }

        .topbar-breadcrumb { font-size: 0.8rem; color: var(--gray-400); margin-left: 14px; }
        .topbar-breadcrumb strong { color: var(--gray-800); font-weight: 700; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-date-pill {
            padding: 5px 14px; border-radius: 999px;
            background: rgba(255,107,53,0.08); border: 1px solid rgba(255,107,53,0.2);
            font-size: 0.75rem; color: var(--orange); font-weight: 700;
        }

        .page-content { padding: 28px 28px 60px; }

        .page-title { font-size: 1.4rem; font-weight: 800; color: var(--gray-800); margin-bottom: 6px; }
        .page-subtitle { font-size: 0.8rem; color: var(--gray-400); margin-bottom: 24px; }

        .cal-fullscreen {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            background: var(--gray-50);
            z-index: 10;
            padding: 80px 40px 40px;
        }
        .cal-fullscreen .cal-card {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
        }
        .cal-fullscreen .cal-card-header {
            flex-shrink: 0;
            margin-bottom: 16px;
        }
        .cal-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
        }
        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            flex-shrink: 0;
        }
        .cal-grid.days-grid {
            flex: 1;
            align-content: stretch;
        }
        .cal-day-name {
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 4px;
            background: var(--gray-50);
            border-right: 1px solid var(--gray-200);
            border-bottom: 1.5px solid var(--gray-200);
        }
        .cal-day-name:last-child { border-right: none; }

        .days-grid .cal-day {
            aspect-ratio: auto;
            min-height: 80px;
            border-radius: 0;
            border: none;
            border-right: 1px solid var(--gray-200);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            padding: 8px;
            font-size: 0.85rem;
            background: var(--white);
            position: relative;
        }
        .days-grid .cal-day:nth-child(7n) { border-right: none; }
        .days-grid .cal-day:nth-last-child(-n+7) { border-bottom: none; }

        .cal-day:hover { background: rgba(255,107,53,0.05); }
        .cal-day.empty { background: var(--gray-50); cursor: default; }
        .cal-day.empty:hover { background: var(--gray-50); }
        .cal-day.has-log { background: rgba(100,100,100,0.08); cursor: pointer; }
        .cal-day.has-log:hover { background: rgba(100,100,100,0.15); }
        .cal-day.today .day-num {
            background: var(--orange);
            color: var(--white);
            width: 26px; height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }
        .cal-day .day-num { line-height: 1; font-size: 0.85rem; font-weight: 600; }
        .cal-day .log-indicator {
            display: none;
        }
        .cal-day.has-log::after {
            content: '';
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--orange);
        }
        .cal-day.has-log.today::after { display: none; }

        .cal-legend {
            display: flex;
            gap: 20px;
            margin-top: 12px;
            padding: 10px 16px;
            background: var(--gray-50);
            border-radius: 8px;
            justify-content: center;
        }
        .cal-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
            color: var(--gray-500);
        }
        .cal-legend-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .cal-legend-dot.log { background: rgba(255,107,53,0.3); }
        .cal-legend-dot.today { background: var(--orange); }

        .cal-card {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }
        .cal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .cal-nav-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-600);
            font-size: 1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .cal-nav-btn:hover { border-color: var(--orange); color: var(--orange); }
        .cal-month-year {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gray-800);
        }
.cal-legend {
            display: flex;
            gap: 20px;
            margin-top: 12px;
            padding: 10px 16px;
            background: var(--gray-50);
            border-radius: 8px;
            justify-content: center;
        }
        .cal-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
            color: var(--gray-500);
        }
        .cal-legend-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .cal-legend-dot.log { background: rgba(255,107,53,0.3); }
        .cal-legend-dot.today { background: var(--orange); }

        .cal-card {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
        }
        .cal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .cal-nav-btn {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1.5px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-600);
            font-size: 1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .cal-nav-btn:hover { border-color: var(--orange); color: var(--orange); }
        .cal-month-year {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gray-800);
        }
        .cal-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            color: var(--gray-500);
        }
        .cal-legend-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }
        .cal-legend-dot.log { background: #9CA3AF; border: 1.5px solid #6B7280; }
        .cal-legend-dot.today { background: transparent; border: 2px solid var(--orange); }

        .log-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .log-modal-overlay.active { display: flex; }
        .log-modal {
            background: var(--white);
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .log-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            background: var(--white);
            border-radius: 20px 20px 0 0;
        }
        .log-modal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gray-800);
        }
        .log-modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            background: var(--white);
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .log-modal-close:hover { background: var(--gray-50); }
        .log-modal-body { padding: 24px; }
        .log-modal-section { margin-bottom: 20px; }
        .log-modal-section:last-child { margin-bottom: 0; }
        .log-modal-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-400);
            margin-bottom: 6px;
        }
        .log-modal-value {
            font-size: 0.95rem;
            color: var(--gray-800);
            line-height: 1.6;
            background: var(--gray-50);
            padding: 12px 16px;
            border-radius: 10px;
        }
        .log-modal-actions {
            display: flex;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid var(--gray-200);
            position: sticky;
            bottom: 0;
            background: var(--white);
            border-radius: 0 0 20px 20px;
        }
        .log-modal-btn {
            flex: 1;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .log-modal-btn.print {
            background: var(--orange);
            color: white;
        }
        .log-modal-btn.print:hover { background: var(--orange-light); }
        .log-modal-btn.edit {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }
        .log-modal-btn.edit:hover { background: var(--gray-200); }

        .log-list-card {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .log-list-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .log-list-title { font-weight: 700; font-size: 0.95rem; color: var(--gray-800); }
        .log-count-badge {
            background: rgba(255,107,53,0.1);
            color: var(--orange);
            font-size: 0.65rem;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255,107,53,0.2);
        }
        .log-entry {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.15s;
            text-decoration: none;
        }
        .log-entry:last-child { border-bottom: none; }
        .log-entry:hover { background: rgba(255,107,53,0.03); }
        .log-day-badge {
            min-width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(255,107,53,0.1);
            color: var(--orange);
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .log-entry-info { flex: 1; min-width: 0; }
        .log-entry-summary {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 3px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .log-entry-meta { font-size: 0.72rem; color: var(--gray-400); }
        .log-entry-status {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(255,107,53,0.1);
            color: var(--orange);
            border: 1px solid rgba(255,107,53,0.2);
            white-space: nowrap;
        }
        .log-entry-date { font-size: 0.72rem; color: var(--gray-400); margin-left: 8px; }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--gray-400);
        }
        .empty-state-icon { font-size: 3rem; margin-bottom: 12px; }
        .empty-state-text { font-size: 0.9rem; font-weight: 600; margin-bottom: 6px; color: var(--gray-500); }
        .empty-state-sub { font-size: 0.78rem; }

        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 99;
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); box-shadow: none; }
            #sidebar.open { transform: translateX(0); box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
            #main { margin-left: 0 !important; }
            .page-content { padding: 12px 10px 60px; }
            #topbar { padding: 10px 12px; }
            .topbar-breadcrumb { display: none; }
            .cal-card { padding: 16px; border-radius: 14px; }
            .cal-container { border-radius: 10px; }
            .cal-day-name { font-size: 0.58rem; padding: 8px 2px; }
            .days-grid .cal-day { min-height: 50px; padding: 6px; font-size: 0.75rem; }
            .days-grid .cal-day .day-num { font-size: 0.75rem; }
            .days-grid .cal-day.today .day-num { width: 22px; height: 22px; }
            .days-grid .cal-day::after { width: 5px; height: 5px; bottom: 4px; right: 4px; }
            .cal-grid { gap: 0; }
            .cal-fullscreen-desktop { display: block; }
            .cal-fullscreen-desktop .cal-card { height: auto; }
            .cal-fullscreen-desktop .page-title { font-size: 1.1rem; }
            .cal-fullscreen-desktop .page-subtitle { font-size: 0.72rem; margin-bottom: 16px; }
            .page-title { font-size: 1.1rem; }
            .page-subtitle { font-size: 0.72rem; margin-bottom: 16px; }
            .profile-wrapper { padding: 8px 10px; }
            .avatar { width: 34px; height: 34px; font-size: 0.75rem; }
            .avatar-info .name { font-size: 0.72rem; }
            .avatar-info .role { font-size: 0.6rem; }
            .logout-btn { padding: 8px 12px; font-size: 0.75rem; }
            .nav-item { padding: 9px 16px; font-size: 0.82rem; }
            .nav-section-label { font-size: 0.55rem; padding: 14px 16px 4px; }
            .nav-divider { margin: 8px 16px; }
            .sidebar-logo { padding: 18px 16px 14px; }
            .cal-legend { gap: 12px; margin-top: 10px; padding: 8px 12px; }
            .cal-legend-item { font-size: 0.65rem; }
            .cal-legend-dot { width: 8px; height: 8px; }
        }

        @media (min-width: 769px) {
            .page-title, .page-subtitle { display: none; }
            .cal-fullscreen-desktop { display: block; }
            .cal-fullscreen-desktop .cal-card { height: calc(100vh - 120px); }
        }

        .bg-blob {
            position: fixed; border-radius: 50%;
            filter: blur(90px); opacity: 0.04; pointer-events: none; z-index: 0;
        }
    </style>
</head>
<body>
    <div class="bg-blob" style="width:500px;height:500px;background:var(--orange);top:-100px;left:-100px;"></div>
    <div class="bg-blob" style="width:400px;height:400px;background:#FF8F5E;bottom:-80px;right:-80px;"></div>

    <aside id="sidebar">
        <div class="sidebar-logo">
            <span class="logo-icon">📒</span>
            <span class="logo-text">My<span>Kv</span>Log</span>
        </div>

        <div class="nav-section-label">Menu</div>

        <a href="{{ route('dashboard') }}" class="nav-item">
            <span class="nav-icon">🏠</span>
            Papan Pemuka
            <span class="nav-arrow">→</span>
        </a>
        <a href="{{ route('logs.index') }}" class="nav-item active">
            <span class="nav-icon">📋</span>
            Log Saya
            <span class="nav-arrow">→</span>
        </a>
        <a href="{{ route('print') }}" class="nav-item">
            <span class="nav-icon">🖨️</span>
            Cetak
            <span class="nav-arrow">→</span>
        </a>

        <div class="nav-divider"></div>
        <div class="nav-section-label">Akaun</div>
        <a href="{{ route('profile.edit') }}" class="nav-item">
            <span class="nav-icon">👤</span>
            Profil
            <span class="nav-arrow">→</span>
        </a>

        <div class="sidebar-profile">
            <div class="profile-wrapper">
                <div class="avatar">{{ strtoupper(substr($user->user_email, 0, 1)) }}</div>
                <div class="avatar-info">
                    <div class="name">{{ $user->user_email }}</div>
                    <div class="role">{{ $defaultSettings->default_department ?? 'Pelajar KV' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="nav-icon">🚪</span>
                    Daftar Keluar
                </button>
            </form>
        </div>
    </aside>

    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <div id="main">
        <div id="topbar">
            <div style="display:flex;align-items:center;gap:8px;">
                <button id="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <span class="topbar-breadcrumb">
                    <strong>Log Saya</strong>
                </span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date-pill">📅 {{ $today }}</span>
                <div class="avatar" style="cursor:pointer;width:32px;height:32px;font-size:0.8rem;">{{ strtoupper(substr($user->user_email, 0, 1)) }}</div>
            </div>
        </div>

        <div class="page-content cal-fullscreen-desktop">
                <h1 class="page-title">📋 Log Saya</h1>
                <p class="page-subtitle">Jejak perjalanan latihan industri anda — {{ $logs->count() }} entri keseluruhan</p>

                @php
                $currentMonth = $selectedMonth ?? date('m');
                $currentYear = $selectedYear ?? date('Y');
                $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                $daysInMonth = date('t', $firstDayOfMonth);
                $firstDayWeek = date('w', $firstDayOfMonth);
                $monthName = date('F', $firstDayOfMonth);
                $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
                $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
                $todayDate = date('Y-m-d');
                @endphp

                <div class="cal-card" style="height: calc(100vh - 180px);">
                    <div class="cal-card-header">
                        <button class="cal-nav-btn" onclick="window.location.href='?month={{ $prevMonth }}&year={{ $prevYear }}'">←</button>
                        <span class="cal-month-year">{{ $monthName }} {{ $currentYear }}</span>
                        <button class="cal-nav-btn" onclick="window.location.href='?month={{ $nextMonth }}&year={{ $nextYear }}'">→</button>
                    </div>

                    <div class="cal-container">
                        <div class="cal-grid">
                            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                                <div class="cal-day-name">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div class="cal-grid days-grid">
                            @for ($i = 0; $i < $firstDayWeek; $i++)
                                <div class="cal-day empty"></div>
                            @endfor

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                $dayPadded = str_pad($day, 2, '0', STR_PAD_LEFT);
                                $monthPadded = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
                                $dateStr = "{$currentYear}-{$monthPadded}-{$dayPadded}";
                                $hasLog = isset($logDates) && in_array($dateStr, $logDates);
                                $isToday = $dateStr === $todayDate;
                                $logEntry = $logsByDate[$dateStr] ?? null;
                                @endphp
                                <div class="cal-day{{ $hasLog ? ' has-log' : '' }}{{ $isToday ? ' today' : '' }}"
                                    @if($hasLog && $logEntry)
                                    data-log-id="{{ $logEntry->log_id }}"
                                    data-date="{{ $dateStr }}"
                                    onclick="openLogModal('{{ $logEntry->log_id }}', '{{ $dateStr }}')"
                                    @endif
                                    title="{{ $hasLog ? 'Entri log wujud' : 'Tiada entri' }}">
                                    <span class="day-num">{{ $day }}</span>
                                    <span class="log-indicator"></span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="cal-legend">
                        <div class="cal-legend-item">
                            <div class="cal-legend-dot log"></div>
                            <span>Ada entri log</span>
                        </div>
                        <div class="cal-legend-item">
                            <div class="cal-legend-dot today"></div>
                            <span>Hari ini</span>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script>
        let sidebarOpen = window.innerWidth >= 769;

        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth < 769) {
                sb.classList.toggle('open');
                overlay.style.display = sb.classList.contains('open') ? 'block' : 'none';
            } else {
                sidebarOpen = !sidebarOpen;
                sb.classList.toggle('collapsed', !sidebarOpen);
                main.classList.toggle('expanded', !sidebarOpen);
            }
        }

        function closeSidebar() {
            const sb = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sb.classList.remove('open');
            overlay.style.display = 'none';
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 769) {
                document.getElementById('sidebar-overlay').style.display = 'none';
                document.getElementById('sidebar').classList.remove('open');
                if (sidebarOpen) {
                    document.getElementById('sidebar').classList.remove('collapsed');
                    document.getElementById('main').classList.remove('expanded');
                }
            }
        });
    </script>

    <div class="log-modal-overlay" id="logModalOverlay" onclick="closeLogModal(event)">
        <div class="log-modal" id="logModal">
            <div class="log-modal-header">
                <span class="log-modal-title" id="logModalTitle">Log Hari #1</span>
                <button class="log-modal-close" onclick="closeLogModal()">✕</button>
            </div>
            <div class="log-modal-body" id="logModalBody">
            </div>
            <div class="log-modal-actions">
                <button class="log-modal-btn print" onclick="printLogEntry()">🖨️ Cetak</button>
                <button class="log-modal-btn edit" onclick="editLogEntry()">✏️ Edit</button>
            </div>
        </div>
    </div>

    <script>
        let currentLogId = null;
        let currentLogDate = null;

        function openLogModal(logId, dateStr) {
            currentLogId = logId;
            currentLogDate = dateStr;
            const dayNum = dateStr.split('-')[2].replace(/^0/, '');
            document.getElementById('logModalTitle').textContent = `Log Hari #${dayNum}`;
            document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray-400);">Memuatkan...</div>';
            document.getElementById('logModalOverlay').classList.add('active');

            fetch(`/logs/${logId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.log) {
                    const log = data.log;
                    document.getElementById('logModalBody').innerHTML = `
                        <div class="log-modal-section">
                            <div class="log-modal-label">Tarikh</div>
                            <div class="log-modal-value">${log.log_date}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Jabatan</div>
                            <div class="log-modal-value">${log.log_location || '-'}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Lokasi</div>
                            <div class="log-modal-value">${log.log_place || '-'}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Aktiviti Hari Ini</div>
                            <div class="log-modal-value">${log.log_summary || '-'}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Pengetahuan & Pembelajaran</div>
                            <div class="log-modal-value">${log.log_knowledge || '-'}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Alat Digunakan</div>
                            <div class="log-modal-value">${log.log_tools || '-'}</div>
                        </div>
                        <div class="log-modal-section">
                            <div class="log-modal-label">Nota Tambahan</div>
                            <div class="log-modal-value">${log.log_note || '-'}</div>
                        </div>
                    `;
                } else {
                    document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:#EF4444;">Ralat memuatkan data log.</div>';
                }
            })
            .catch(() => {
                document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:#EF4444;">Ralat memuatkan data log.</div>';
            });
        }

        function closeLogModal(e) {
            if (!e || e.target === document.getElementById('logModalOverlay')) {
                document.getElementById('logModalOverlay').classList.remove('active');
            }
        }

        function printLogEntry() {
            if (currentLogDate) {
                window.location.href = `/logs/print/${currentLogDate}`;
            }
        }

        function editLogEntry() {
            if (currentLogDate) {
                window.location.href = `/dashboard?edit=${currentLogDate}`;
            }
        }
    </script>
</body>
</html>