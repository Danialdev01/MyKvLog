@php
$user = Auth::user();
$today = date('d/m/Y');
$dayNum = date('j');
$monthYear = date('F Y');
$year = date('Y');
$recentLogs = $user->logs()->orderBy('log_day', 'desc')->limit(5)->get();
$totalLogs = $user->logs()->count();
$defaultSettings = $user->defaults;
$internshipPeriod = $defaultSettings->default_internship_period ?? 90;
$currentDay = $totalLogs + 1;
$completionPercent = min(100, round(($totalLogs / $internshipPeriod) * 100));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyInternLog — Dashboard</title>
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
        html { height: 100%; overflow-y: auto; }
        body { font-family: 'Figtree', sans-serif; background: var(--gray-50); color: var(--gray-800); min-height: 100%; }
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
            min-height: 100%;
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
            position: relative; z-index: 1;
            padding-bottom: 60px;
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

        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; margin-bottom: 28px; }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 16px; padding: 18px 20px;
            position: relative; overflow: hidden;
            transition: transform 0.18s, box-shadow 0.18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255,107,53,0.1); }
        .stat-card .stat-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gray-500); margin-bottom: 6px; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-sub { font-size: 0.7rem; color: var(--gray-400); margin-top: 4px; }
        .stat-card .stat-glow {
            position: absolute; bottom: -20px; right: -20px;
            width: 80px; height: 80px; border-radius: 50%;
            filter: blur(28px); opacity: 0.15;
        }

        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-title { font-size: 1.1rem; font-weight: 700; color: var(--gray-800); }
        .section-tag {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--orange);
            background: rgba(255,107,53,0.1); border: 1px solid rgba(255,107,53,0.2);
            padding: 3px 10px; border-radius: 999px;
        }

        .form-card {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .form-card-header {
            padding: 14px 16px 12px;
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,107,53,0.02);
            border-bottom: 1px solid var(--gray-100);
        }
        .form-card-body { padding: 24px; }

        .field-label {
            display: block; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--gray-600); margin-bottom: 7px;
        }
        .field-required { color: var(--orange); margin-left: 3px; }

        .input-base {
            width: 100%;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--gray-800);
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
            resize: none;
        }
        .input-base::placeholder { color: var(--gray-400); }
        .input-base:focus {
            border-color: var(--orange);
            background: rgba(255,107,53,0.02);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .input-base:hover:not(:focus) { border-color: var(--gray-300); }

        select.input-base {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }
        select.input-base option { background: var(--white); color: var(--gray-800); }

        .upload-zone {
            border: 2px dashed rgba(255,107,53,0.25);
            border-radius: 14px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
            background: rgba(255,107,53,0.02);
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: var(--orange); background: rgba(255,107,53,0.05);
        }
        .upload-zone input[type="file"] { display: none; }
        .upload-icon { font-size: 2rem; margin-bottom: 8px; }
        .upload-zone p { font-size: 0.8rem; color: var(--gray-500); }
        .upload-zone p strong { color: var(--orange); }

        #preview-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(90px,1fr));
            gap: 10px; margin-top: 14px;
        }
        .preview-thumb {
            position: relative; border-radius: 10px; overflow: hidden;
            aspect-ratio: 1; background: var(--gray-100);
            border: 1px solid var(--gray-200);
        }
        .preview-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .preview-thumb .remove-img {
            position: absolute; top: 4px; right: 4px;
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(255,107,53,0.9); color: #fff;
            font-size: 0.65rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
        }

        .char-counter { font-size: 0.68rem; color: var(--gray-400); text-align: right; margin-top: 4px; }
        .char-counter.warn { color: var(--orange); }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }

        .btn-lime {
            background: var(--orange); color: var(--white);
            font-weight: 800;
            border-radius: 12px; padding: 13px 28px;
            font-size: 0.9rem; cursor: pointer; border: none;
            transition: transform 0.15s, box-shadow 0.15s;
            display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(255,107,53,0.35);
        }
        .btn-lime:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,53,0.4); }

        .btn-ghost {
            background: transparent; color: var(--gray-600);
            font-weight: 700;
            border-radius: 12px; padding: 13px 22px;
            font-size: 0.9rem; cursor: pointer;
            border: 1.5px solid var(--gray-200);
            transition: border-color 0.15s, color 0.15s, background 0.15s;
            display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none;
        }
        .btn-ghost:hover { border-color: var(--orange); color: var(--orange); background: rgba(255,107,53,0.05); }

        .btn-sky {
            background: rgba(255,107,53,0.1); color: var(--orange);
            font-weight: 700;
            border-radius: 12px; padding: 13px 22px;
            font-size: 0.9rem; cursor: pointer;
            border: 1.5px solid rgba(255,107,53,0.25);
            transition: background 0.15s, border-color 0.15s;
            display: inline-flex; align-items: center; gap: 8px;
            text-decoration: none;
        }
        .btn-sky:hover { background: rgba(255,107,53,0.15); border-color: var(--orange); }

        .log-item {
            background: var(--card);
            border: 1px solid var(--gray-200);
            border-radius: 14px; padding: 14px 18px;
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 10px;
            transition: transform 0.15s, border-color 0.15s;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .log-item:hover { transform: translateX(4px); border-color: rgba(255,107,53,0.3); }
        .log-day-badge { font-weight: 800; font-size: 1.1rem; min-width: 40px; text-align: center; color: var(--orange); }
        .log-divider { width: 1px; height: 36px; background: var(--gray-200); flex-shrink: 0; }
        .log-summary { font-size: 0.85rem; font-weight: 600; color: var(--gray-800); margin-bottom: 2px; }
        .log-date { font-size: 0.7rem; color: var(--gray-400); }
        .log-status {
            margin-left: auto; font-size: 0.65rem; font-weight: 700;
            padding: 3px 10px; border-radius: 999px;
            background: rgba(255,107,53,0.1); color: var(--orange);
            border: 1px solid rgba(255,107,53,0.2);
            white-space: nowrap;
        }

        .ai-panel {
            background: linear-gradient(135deg, rgba(255,107,53,0.05), rgba(255,107,53,0.02));
            border: 1px solid rgba(255,107,53,0.15);
            border-radius: 16px; padding: 18px 20px;
            margin-top: 24px;
        }
        .ai-btn-small {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,107,53,0.1);
            border: 1px solid rgba(255,107,53,0.25);
            color: var(--orange);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .ai-btn-small:hover {
            background: rgba(255,107,53,0.2);
            border-color: var(--orange);
        }
        .ai-btn-small:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .input-with-ai { position: relative; }
        .input-with-ai .input-base { padding-right: 80px; }
        .ai-btn-small.loading::after {
            content: '';
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1.5px solid var(--orange);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 4px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .ai-btn-small.ai-active {
            background: var(--orange);
            color: var(--white);
        }

        .ai-panel-header { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }

        .progress-wrap { background: var(--gray-100); border-radius: 999px; height: 6px; }
        .progress-bar { height: 6px; border-radius: 999px; background: var(--orange); transition: width 1.2s ease; }

        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 99;
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); box-shadow: none; }
            #sidebar.open { transform: translateX(0); box-shadow: 4px 0 15px rgba(0,0,0,0.1); }
            #main { margin-left: 0 !important; }
            .page-content { padding: 12px 10px 60px; }
            .form-grid { grid-template-columns: 1fr !important; gap: 14px; }
            #topbar { padding: 10px 12px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .topbar-breadcrumb { display: none; }
            .stat-card { padding: 14px 14px; }
            .stat-card .stat-value { font-size: 1.6rem; }
            .stat-card .stat-label { font-size: 0.6rem; }
            .stat-card .stat-sub { font-size: 0.62rem; }
            .section-title { font-size: 0.9rem !important; }
            .section-header { margin-bottom: 12px; }
            .form-card { border-radius: 14px; }
            .form-card-body { padding: 16px; }
            .form-card-header { padding: 10px 14px 8px; }
            .field-label { font-size: 0.65rem; }
            .input-base { padding: 10px 12px; font-size: 0.85rem; }
            .input-base::placeholder { font-size: 0.8rem; }
            textarea.input-base { rows: 3 !important; }
            .btn-lime, .btn-ghost, .btn-sky { padding: 10px 16px; font-size: 0.8rem; }
            .upload-zone { padding: 20px 14px; }
            .upload-icon { font-size: 1.6rem; }
            .upload-zone p { font-size: 0.72rem; }
            .log-item { padding: 10px 12px; gap: 10px; }
            .log-day-badge { font-size: 0.9rem; min-width: 32px; }
            .log-summary { font-size: 0.78rem; }
            .log-date { font-size: 0.62rem; }
            .log-status { font-size: 0.58rem; padding: 2px 8px; }
            .step-dots { display: none; }
            .ai-panel { padding: 14px; }
            .ai-panel-header { margin-bottom: 8px; }
            .ai-panel-header span { font-size: 0.8rem; }
            .ai-panel p { font-size: 0.75rem; }
            .char-counter { font-size: 0.62rem; }
            #ai-output { font-size: 0.75rem !important; }
            .right-col { display: none !important; }
            .section-tag { font-size: 0.58rem; padding: 2px 8px; }
            .main-cols { gap: 16px; }
            .profile-wrapper { padding: 8px 10px; }
            .avatar { width: 34px; height: 34px; font-size: 0.75rem; }
            .avatar-info .name { font-size: 0.72rem; }
            .avatar-info .role { font-size: 0.6rem; }
            .logout-btn { padding: 8px 12px; font-size: 0.75rem; }
            .form-card-header span { font-size: 1.1rem; }
            .form-card-header div { font-size: 0.82rem; }
            .form-card-header div div { font-size: 0.65rem; }
            .nav-item { padding: 9px 16px; font-size: 0.82rem; }
            .nav-section-label { font-size: 0.55rem; padding: 14px 16px 4px; }
            .nav-divider { margin: 8px 16px; }
            .sidebar-logo { padding: 18px 16px 14px; }
        }

        @media (max-width: 480px) {
            .stat-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .stat-card .stat-value { font-size: 1.4rem; }
            .stat-card { padding: 12px; }
            .page-content { padding: 10px 8px 60px; }
            .form-card-body { padding: 14px; }
            .btn-lime, .btn-ghost, .btn-sky { width: 100%; justify-content: center; }
            div[style*="display:flex;gap:12px"] { flex-direction: column; }
            .section-header { flex-direction: column; align-items: flex-start; gap: 6px; }
            .section-tag { align-self: flex-start; }
        }

        @media (min-width: 769px) and (max-width: 1100px) {
            .main-cols { grid-template-columns: 1fr !important; }
            .right-col { display: flex !important; }
            .right-col > div { width: 100%; }
        }

        @media (min-width: 1400px) {
            .main-cols { grid-template-columns: 1fr 380px; }
            .form-card-body { padding: 28px; }
        }

        .step-dots {
            display: flex; gap: 6px; align-items: center;
            margin-bottom: 20px;
        }
        .step-dot {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 800;
            border: 2px solid var(--gray-200);
            color: var(--gray-400);
        }
        .step-dot.done { background: var(--orange); color: var(--white); border-color: var(--orange); }
        .step-dot.active { border-color: var(--orange); color: var(--orange); }
        .step-line { flex: 1; height: 1px; background: var(--gray-200); }

        .bg-blob {
            position: fixed; border-radius: 50%;
            filter: blur(90px); opacity: 0.04; pointer-events: none; z-index: 0;
        }

        .reveal { opacity:0; transform:translateY(20px); transition:opacity .5s ease,transform .5s ease; }
        .reveal.in { opacity:1; transform:translateY(0); }

        .ai-hint {
            position: absolute; bottom: 10px; right: 12px;
            display: flex; align-items: center; gap: 5px;
            font-size: 0.65rem; color: var(--gray-400); font-weight: 600;
            pointer-events: none;
        }
        .ai-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--orange);
            animation: pulse 1.6s ease-in-out infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.75)} }
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

        <a href="{{ route('dashboard') }}" class="nav-item active">
            <span class="nav-icon">🏠</span>
            Papan Pemuka
            <span class="nav-arrow">→</span>
        </a>
        <a href="{{ route('logs.index') }}" class="nav-item">
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
                    <strong>Papan Pemuka</strong>
                    <span style="margin:0 6px;opacity:0.4;">/</span>
                    Entri Log Baru
                </span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date-pill text-xs md:text-sm">📅 {{ $today }}</span>
                <div class="avatar" style="cursor:pointer;width:32px;height:32px;font-size:0.8rem;" title="Profile">{{ strtoupper(substr($user->user_email, 0, 1)) }}</div>
            </div>
        </div>

        <div class="page-content">
            <div class="stat-grid reveal in">
                <div class="stat-card">
                    <div class="stat-label">Hari</div>
                    <div class="stat-value" style="color:var(--orange);">{{ $currentDay }}</div>
                    <div class="stat-sub">daripada {{ $internshipPeriod }}-hari latihan industri</div>
                    <div class="stat-glow" style="background:var(--orange);"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Log Selesai</div>
                    <div class="stat-value" style="color:var(--gray-700);">{{ $totalLogs }}</div>
                    <div class="stat-sub">entri selesai</div>
                    <div class="stat-glow" style="background:var(--gray-400);"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Minggu Ini</div>
                    <div class="stat-value" style="color:var(--orange);">{{ $user->logs()->where('log_date', '>=', now()->startOfWeek())->count() }}</div>
                    <div class="stat-sub">entri minggu ini</div>
                    <div class="stat-glow" style="background:var(--orange);"></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Pematuhan</div>
                    <div class="stat-value" style="color:var(--orange);">{{ $completionPercent }}%</div>
                    <div class="stat-sub">kemajuan ke matlamat</div>
                    <div class="stat-glow" style="background:var(--orange);"></div>
                    <div class="progress-wrap" style="margin-top:10px;">
                        <div class="progress-bar" style="width:{{ $completionPercent }}%;"></div>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 340px;gap:22px;" class="main-cols">
                <div>
                    <div class="section-header reveal in">
                        <div>
                            <div class="section-title" style="font-size:1rem;">✍️ Entri Log Hari Ini</div>
                            <div style="font-size:0.7rem;color:var(--gray-400);margin-top:2px;">{{ $monthYear }} · Isi aktiviti harian anda</div>
                        </div>
                        <span class="section-tag">Day {{ $currentDay }}</span>
                    </div>

                    <div class="form-card reveal in">
                        <div class="form-card-header">
                            <span style="font-size:1.3rem;">📝</span>
                            <div>
                                <div style="font-weight:700;font-size:0.95rem;color:var(--gray-800);">Entri Log Baru</div>
                                <div style="font-size:0.7rem;color:var(--gray-400);">Semua ruangan bertanda <span style="color:var(--orange);">*</span> diperlukan</div>
                            </div>
                        </div>
                        <div class="form-card-body">
                            <form id="logForm" method="POST" action="/logs" enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid" style="margin-bottom:20px;">
                                    <div>
                                        <label class="field-label" for="day-number">
                                            Hari Latihan Industri <span class="field-required">*</span>
                                        </label>
                                        <div style="position:relative;">
                                            <input type="number" id="day-number" name="log_day" class="input-base" placeholder="e.g. {{ $currentDay }}" min="1" max="365" value="{{ $currentDay }}" required />
                                            <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:0.75rem;color:var(--gray-400);">/ {{ $internshipPeriod }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="field-label" for="log-date">
                                            Tarikh <span class="field-required">*</span>
                                        </label>
                                        <input type="date" id="log-date" name="log_date" class="input-base" value="{{ date('Y-m-d') }}" required onchange="checkExistingLog()" />
                                    </div>
                                </div>

                                <div style="margin-bottom:20px;" class="input-with-ai">
                                    <label class="field-label" for="log-location">Jabatan/Unit/Bahagian</label>
                                    <input type="text" id="log-location" name="log_location" class="input-base" placeholder="e.g. IT Department, Electrical Lab" value="{{ $defaultSettings->default_department ?? '' }}" />
                                </div>

                                <div style="margin-bottom:20px;" class="input-with-ai">
                                    <label class="field-label" for="log-place">Lokasi Tempat</label>
                                    <input type="text" id="log-place" name="log_place" class="input-base" placeholder="e.g. Aras 2, Blok A, Kuala Lumpur" value="{{ $defaultSettings->default_location ?? '' }}" />
                                </div>

                                 <div style="margin-bottom:20px;" class="input-with-ai">
                                      <label class="field-label" for="activities">
                                          Aktiviti Hari Ini <span class="field-required">*</span>
                                      </label>
                                      <div style="position:relative;">
                                          <textarea id="activities" name="log_summary" class="input-base" rows="4" placeholder="Cuma beritahu kami apa yang anda buat.." required oninput="countChars(this,'act-count',800)"></textarea>
                                          <button type="button" class="ai-btn-small" style="top:12px;right:12px;transform:none;" onclick="generateField('log_summary', this)">✨ Guna AI</button>
                                          <div class="ai-hint">
                                              <div class="ai-dot"></div>
                                              AI akan kembangkan ini
                                          </div>
                                      </div>
                                      <div class="char-counter" id="act-count">0 / 800 aksara</div>
                                 </div>

                                 <div style="margin-bottom:20px;" class="input-with-ai">
                                      <label class="field-label" for="log-knowledge">Pengetahuan & Pembelajaran</label>
                                      <textarea id="log-knowledge" name="log_knowledge" class="input-base" rows="2" placeholder="Apa yang anda pelajari hari ini? Apa-apa kemahiran atau pandangan baru?"></textarea>
                                      <button type="button" class="ai-btn-small" onclick="generateField('log_knowledge', this)">✨ Guna AI</button>
                                 </div>

                                 <div style="margin-bottom:24px;" class="input-with-ai">
                                      <label class="field-label" for="log-tools">Alat Digunakan</label>
                                      <input type="text" id="log-tools" name="log_tools" class="input-base" placeholder="contoh: Cable tester, punch down tool, label printer (pisahkan dengan koma)" />
                                      <button type="button" class="ai-btn-small mt-2" onclick="generateField('log_tools', this)">✨ Guna AI</button>
                                 </div>

                                 <div style="margin-bottom:24px;" class="input-with-ai">
                                      <label class="field-label" for="log-note">Nota Tambahan</label>
                                      <textarea id="log-note" name="log_note" class="input-base" rows="2" placeholder="Apa-apa nota atau pemerhatian tambahan..."></textarea>
                                      <button type="button" class="ai-btn-small" onclick="generateField('log_note', this)">✨ Guna AI</button>
                                 </div>

                                <div style="margin-bottom:24px;">
                                    <label class="field-label">
                                        📸 Foto / Bukti <span style="color:var(--gray-400);font-weight:400;text-transform:none;letter-spacing:0;">(opsyen tapi disyorkan)</span>
                                    </label>
                                    <div class="upload-zone" id="upload-zone" onclick="document.getElementById('file-input').click()" ondragover="dragOver(event)" ondrop="dropFiles(event)" ondragleave="dragLeave(event)">
                                        <input type="file" id="file-input" multiple accept="image/*" onchange="handleFiles(this.files)"/>
                                        <div class="upload-icon">📂</div>
                                        <p><strong>Klik untuk muat naik</strong> atau seret & letak foto di sini</p>
                                        <p style="margin-top:4px;font-size:0.7rem;">PNG, JPG, WEBP · Maks 5MB setiap satu · sehingga 10 gambar</p>
                                    </div>
                                    <div id="preview-grid"></div>
                                    <div id="file-count" style="font-size:0.72rem;color:var(--gray-400);margin-top:8px;"></div>
                                </div>

                                <div class="ai-panel" id="ai-panel" style="display:none;">
                                    <div class="ai-panel-header">
                                        <div class="ai-dot" style="width:8px;height:8px;"></div>
                                        <span style="font-weight:700;font-size:0.9rem;color:var(--orange);">AI sedang menulis log anda...</span>
                                    </div>
                                    <div style="font-size:0.82rem;color:var(--gray-600);line-height:1.7;" id="ai-output">
                                        Menganalisis aktiviti anda dan mengembangkan ke format log profesional...
                                    </div>
                                    <div class="progress-wrap" style="margin-top:12px;">
                                        <div class="progress-bar" id="ai-progress" style="width:0%;"></div>
                                    </div>
                                </div>

                                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:4px;">
                                    <button type="submit" id="submit-btn" class="btn-lime">
                                        💾 Simpan
                                    </button>
                                    </button>
                                    <button type="button" id="generateBtn" class="btn-sky" onclick="generateWithAI()">
                                        🤖 Siapkan guna AI
                                    </button>
                                    <button type="button" class="btn-ghost" onclick="clearForm()">
                                        🗑️ Padam
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:20px;" class="right-col hidden md:flex">
                    <div class="form-card reveal in" style="transition-delay:0.1s;">
                        <div class="form-card-header">
                            <span>💡</span>
                            <div style="font-weight:700;font-size:0.9rem;color:var(--gray-800);">Tips Penulisan</div>
                        </div>
                        <div style="padding:16px 18px;">
                            @php
                            $tips = [
                                ['✅','Tulis dengan perkataan mudah — AI menjaga tatabahasa'],
                                ['📸','Tambah foto untuk setiap tugas yang anda siapkan'],
                                ['🔧','Senaraikan SEMUA alat, termasuk yang asas'],
                                ['📅','Log setiap hari — jangan tunggu hingga minggu hadapan'],
                                ['🎯','Masukkan apa yang anda pelajari, bukan hanya apa yang anda buat'],
                            ];
                            @endphp
                            @foreach($tips as $tip)
                            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">
                                <span style="font-size:0.85rem;flex-shrink:0;">{{ $tip[0] }}</span>
                                <span style="font-size:0.78rem;color:var(--gray-600);line-height:1.5;">{{ $tip[1] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="reveal in" style="transition-delay:0.2s;">
                        <div class="section-header" style="margin-bottom:12px;">
                            <div class="section-title" style="font-size:0.95rem;">📋 Log Terkini</div>
                            <a href="#" style="font-size:0.72rem;color:var(--orange);font-weight:700;text-decoration:none;">Lihat semua →</a>
                        </div>

                        @forelse($recentLogs as $log)
                        <a href="{{ route('dashboard') }}?edit={{ $log->log_date->format('Y-m-d') }}" class="log-item" style="text-decoration:none;color:inherit;display:block;">
                            <div class="log-day-badge">D{{ $log->log_day }}</div>
                            <div class="log-divider"></div>
                            <div style="flex:1;min-width:0;">
                                <div class="log-summary" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($log->log_summary, 50) }}</div>
                                <div class="log-date">{{ $log->log_date->format('d/m/Y') }}</div>
                            </div>
                            <div class="log-status">✓ {{ ucfirst($log->log_status) }}</div>
                        </a>
                        @empty
                        <div class="log-item">
                            <div class="log-summary">Belum ada log. Mula tulis entri pertama anda!</div>
                        </div>
                        @endforelse
                    </div>

                    <div class="reveal in" style="transition-delay:0.4s;">
                        <div style="background:linear-gradient(135deg,rgba(255,107,53,0.08),rgba(255,107,53,0.03));border:1px solid rgba(255,107,53,0.2);border-radius:16px;padding:18px 20px;text-align:center;">
                            <div style="font-size:2rem;margin-bottom:8px;">🖨️</div>
                            <div style="font-weight:700;font-size:0.9rem;margin-bottom:4px;color:var(--gray-800);">Sedia untuk Cetak?</div>
                            <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:14px;">Kompil log minggu ini menjadi PDF yang sedia untuk dihantar</div>
                            <button class="btn-lime" style="width:100%;justify-content:center;">Cetak Log Mingguan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const existingLogDates = @json($existingLogDates ?? []);
        let currentEditingLogId = null;

        // Check for edit parameter on page load
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const editDate = params.get('edit');
            if (editDate && existingLogDates.includes(editDate)) {
                const dateInput = document.getElementById('log-date');
                dateInput.value = editDate;
                checkExistingLog();
            }
        });

        function checkExistingLog() {
            const dateInput = document.getElementById('log-date');
            const selectedDate = dateInput.value;
            
            if (existingLogDates.includes(selectedDate)) {
                fetch(`/logs/${selectedDate}/edit`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.log) {
                        const log = data.log;
                        currentEditingLogId = log.log_id;
                        
                        document.getElementById('day-number').value = log.log_day;
                        document.getElementById('log-location').value = log.log_location || '';
                        document.getElementById('log-place').value = log.log_place || '';
                        document.getElementById('activities').value = log.log_summary || '';
                        document.getElementById('log-knowledge').value = log.log_knowledge || '';
                        document.getElementById('log-tools').value = log.log_tools || '';
                        document.getElementById('log-note').value = log.log_note || '';
                        
                        countChars(document.getElementById('activities'), 'act-count', 800);
                        
                        const submitBtn = document.getElementById('submit-btn');
                        submitBtn.textContent = '✏️ Kemaskini';
                        submitBtn.onclick = function() { updateLog(selectedDate); };
                        
                        const form = document.getElementById('logForm');
                        form.action = '#';
                        form.dataset.editing = 'true';
                    }
                });
            } else {
                resetToNewEntry();
            }
        }

        function resetToNewEntry() {
            currentEditingLogId = null;
            const form = document.getElementById('logForm');
            form.action = '{{ route("logs.store") }}';
            form.dataset.editing = 'false';
            
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.textContent = '💾 Simpan';
            submitBtn.onclick = null;
            delete submitBtn.onclick;
        }

        function updateLog(dateStr) {
            const formData = new FormData();
            formData.append('log_day', document.getElementById('day-number').value);
            formData.append('log_summary', document.getElementById('activities').value);
            formData.append('log_location', document.getElementById('log-location').value);
            formData.append('log_place', document.getElementById('log-place').value);
            formData.append('log_knowledge', document.getElementById('log-knowledge').value);
            formData.append('log_tools', document.getElementById('log-tools').value);
            formData.append('log_note', document.getElementById('log-note').value);
            
            fetch(`/logs/${dateStr}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Log berjaya dikemaskini!');
                    window.location.reload();
                } else {
                    alert('Ralat: ' + (data.error || 'Gagal mengemaskini log'));
                }
            })
            .catch(err => {
                alert('Ralat: ' + err.message);
            });
        }

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

        function checkCols() {
            const cols = document.querySelector('.main-cols');
            if (!cols) return;
            if (window.innerWidth < 1100) {
                cols.style.gridTemplateColumns = '1fr';
            } else {
                cols.style.gridTemplateColumns = '1fr 340px';
            }
        }
        checkCols();
        window.addEventListener('resize', checkCols);

        function countChars(el, counterId, max) {
            const counter = document.getElementById(counterId);
            const len = el.value.length;
            counter.textContent = `${len} / ${max} aksara`;
            counter.classList.toggle('warn', len > max * 0.85);
            if (len > max) el.value = el.value.slice(0, max);
        }

        let uploadedFiles = [];

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (uploadedFiles.length >= 10) return;
                if (!file.type.startsWith('image/')) return;
                if (file.size > 5 * 1024 * 1024) { alert(`${file.name} is too large (max 5MB)`); return; }
                uploadedFiles.push(file);
                const reader = new FileReader();
                reader.onload = e => addPreview(e.target.result, uploadedFiles.length - 1);
                reader.readAsDataURL(file);
            });
            updateFileCount();
        }

        function addPreview(src, idx) {
            const grid = document.getElementById('preview-grid');
            const div = document.createElement('div');
            div.className = 'preview-thumb';
            div.id = `thumb-${idx}`;
            div.innerHTML = `<img src="${src}" alt="preview"/><div class="remove-img" onclick="removeFile(${idx})">✕</div>`;
            grid.appendChild(div);
        }

        function removeFile(idx) {
            uploadedFiles.splice(idx, 1);
            const grid = document.getElementById('preview-grid');
            grid.innerHTML = '';
            uploadedFiles.forEach((f, i) => {
                const reader = new FileReader();
                reader.onload = e => addPreview(e.target.result, i);
                reader.readAsDataURL(f);
            });
            updateFileCount();
        }

        function updateFileCount() {
            const el = document.getElementById('file-count');
            el.textContent = uploadedFiles.length ? `📸 ${uploadedFiles.length} photo${uploadedFiles.length > 1 ? 's' : ''} added` : '';
        }

        function dragOver(e) { e.preventDefault(); document.getElementById('upload-zone').classList.add('dragover'); }
        function dragLeave(e) { document.getElementById('upload-zone').classList.remove('dragover'); }
        function dropFiles(e) { e.preventDefault(); dragLeave(e); handleFiles(e.dataTransfer.files); }

        function saveDraft() {
            const btn = event.target;
            btn.textContent = '✅ Draft Saved!';
            btn.style.color = 'var(--orange)';
            setTimeout(() => { btn.textContent = '💾 Save Draft'; btn.style.color = ''; }, 2000);
        }

        function clearForm() {
            if (!confirm('Padam semua ruangan?')) return;
            document.getElementById('logForm').reset();
            uploadedFiles = [];
            document.getElementById('preview-grid').innerHTML = '';
            document.getElementById('file-count').textContent = '';
            document.getElementById('act-count').textContent = '0 / 800 aksara';
            document.getElementById('ai-panel').style.display = 'none';
        }

        function generateWithAI() {
            const summary = document.getElementById('activities').value.trim();
            const day = document.getElementById('day-number').value;
            const location = document.getElementById('log-location').value.trim();
            const place = document.getElementById('log-place').value.trim();
            const tools = document.getElementById('log-tools').value.trim();
            const knowledge = document.getElementById('log-knowledge').value.trim();
            const note = document.getElementById('log-note').value.trim();

            const hasAnyInput = summary || location || place || tools || knowledge || note;

            if (!hasAnyInput) {
                document.getElementById('aiPromptModal').style.display = 'flex';
                document.getElementById('aiPromptInput').focus();
                return;
            }

            const btn = document.getElementById('generateBtn');
            const aiPanel = document.getElementById('ai-panel');
            const aiOutput = document.getElementById('ai-output');
            const aiProgress = document.getElementById('ai-progress');

            btn.disabled = true;
            btn.textContent = '⏳ Menjana...';
            aiPanel.style.display = 'block';
            aiOutput.textContent = 'Menghantar log anda ke AI...';
            aiProgress.style.width = '20%';

            const jobScope = '{{ $defaultSettings->default_job_scope ?? '' }}';

            const fieldsToFill = [];
            const emptyFields = [];

            if (!summary) emptyFields.push('log_summary');
            else fieldsToFill.push({ field: 'log_summary', value: summary });

            if (!location) emptyFields.push('log_location');
            else fieldsToFill.push({ field: 'log_location', value: location });

            if (!place) emptyFields.push('log_place');
            else fieldsToFill.push({ field: 'log_place', value: place });

            if (!knowledge) emptyFields.push('log_knowledge');
            else fieldsToFill.push({ field: 'log_knowledge', value: knowledge });

            if (!note) emptyFields.push('log_note');
            else fieldsToFill.push({ field: 'log_note', value: note });

            const referenceSummary = summary || location || place || knowledge || note || '';
            const primaryRef = summary || (location + ' ' + place);

            let fillPromises = [];
            let fillCount = 0;

            const fillField = (fieldName, value) => {
                return new Promise((resolve) => {
                    fetch('/ai/generate-field', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            field: fieldName,
                            log_day: day,
                            log_location: location || 'Jabatan',
                            log_place: place || '',
                            job_scope: jobScope,
                            log_summary: value || summary || referenceSummary,
                            log_knowledge: knowledge || '',
                            log_tools: tools || '',
                            log_note: note || '',
                            reference: primaryRef,
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.content) {
                            const idMap = {
                                'log_summary': 'activities',
                                'log_knowledge': 'log-knowledge',
                                'log_tools': 'log-tools',
                                'log_note': 'log-note'
                            };
                            const inputId = idMap[fieldName] || fieldName;
                            const input = document.getElementById(inputId);
                            if (input) {
                                input.value = data.content;
                                if (fieldName === 'log_summary') countChars(input, 'act-count', 800);
                            }
                        }
                        resolve();
                    })
                    .catch(() => resolve());
                });
            };

            if (emptyFields.length > 0) {
                aiOutput.textContent = `Mengisi ${emptyFields.length} ruangan kosong...`;

                const sequentialFill = async () => {
                    for (const fieldName of emptyFields) {
                        const refValue = summary || knowledge || note || location || 'aktiviti latihan';
                        await fillField(fieldName, refValue);
                        fillCount++;
                        aiProgress.style.width = `${20 + (fillCount / emptyFields.length) * 60}%`;
                    }

                    if (tools) {
                        aiOutput.textContent = 'Memperbaiki alat yang digunakan...';
                        await fillField('log_tools', tools);
                    }

                    aiProgress.style.width = '100%';
                    aiOutput.textContent = '✅ Log berjaya disediakan!';
                    btn.textContent = '✅ Selesai!';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.textContent = '🤖 Siapkan guna AI';
                    }, 2000);
                };

                sequentialFill();
            } else {
                aiOutput.textContent = 'Memperbaiki semua ruangan...';
                aiProgress.style.width = '50%';

                fetch('/ai/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        log_summary: summary,
                        log_day: day,
                        log_location: location,
                        log_place: place,
                        log_tools: tools,
                        log_knowledge: knowledge,
                        job_scope: jobScope,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    aiProgress.style.width = '100%';
                    if (data.success && data.content) {
                        document.getElementById('activities').value = data.content;
                        countChars(document.getElementById('activities'), 'act-count', 800);
                        aiOutput.textContent = '✅ Log berjaya diperbaiki!';
                        btn.textContent = '✅ Selesai!';
                        setTimeout(() => {
                            btn.disabled = false;
                            btn.textContent = '🤖 Siapkan guna AI';
                        }, 2000);
                    } else {
                        throw new Error(data.error || 'Penjanaan gagal');
                    }
                })
                .catch(err => {
                    aiOutput.textContent = 'Ralat: ' + err.message;
                    aiOutput.style.color = '#EF4444';
                    btn.disabled = false;
                    btn.textContent = '🤖 Cuba Lagi';
                });
            }
        }

        function closeAiPromptModal() {
            document.getElementById('aiPromptModal').style.display = 'none';
            document.getElementById('aiPromptInput').value = '';
        }

        function submitAiPrompt() {
            const userInput = document.getElementById('aiPromptInput').value.trim();
            if (!userInput) {
                alert('Sila jelaskan apa yang anda buat hari ini.');
                return;
            }

            closeAiPromptModal();

            const btn = document.getElementById('generateBtn');
            const aiPanel = document.getElementById('ai-panel');
            const aiOutput = document.getElementById('ai-output');
            const aiProgress = document.getElementById('ai-progress');

            btn.disabled = true;
            btn.textContent = '⏳ Menjana...';
            aiPanel.style.display = 'block';
            aiOutput.textContent = 'Menghantar ke AI...';
            aiProgress.style.width = '20%';

            const day = document.getElementById('day-number').value;
            const location = document.getElementById('log-location').value.trim() || 'Jabatan';
            const place = document.getElementById('log-place').value.trim() || '';
            const jobScope = '{{ $defaultSettings->default_job_scope ?? '' }}';

            const fields = ['log_summary', 'log_knowledge', 'log_tools', 'log_note'];
            let currentIndex = 0;

            const fillNextField = () => {
                if (currentIndex >= fields.length) {
                    aiProgress.style.width = '100%';
                    aiOutput.textContent = '✅ Log berjaya dijana!';
                    btn.textContent = '✅ Selesai!';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.textContent = '🤖 Siapkan guna AI';
                    }, 2000);
                    return;
                }

                const fieldName = fields[currentIndex];
                const aiOutput_for_field = aiOutput;

                fetch('/ai/generate-field', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        field: fieldName,
                        log_day: day,
                        log_location: location,
                        log_place: place,
                        job_scope: jobScope,
                        log_summary: userInput,
                        log_knowledge: '',
                        log_tools: '',
                        log_note: '',
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.content) {
                        const idMap = {
                            'log_summary': 'activities',
                            'log_knowledge': 'log-knowledge',
                            'log_tools': 'log-tools',
                            'log_note': 'log-note'
                        };
                        const inputId = idMap[fieldName] || fieldName;
                        const input = document.getElementById(inputId);
                        if (input) {
                            input.value = data.content;
                            if (fieldName === 'log_summary') countChars(input, 'act-count', 800);
                        }
                    }
                    currentIndex++;
                    aiProgress.style.width = `${20 + (currentIndex / fields.length) * 60}%`;
                    aiOutput.textContent = `Mengisi ${fields[currentIndex] ? fields[currentIndex].replace('log_', '') : 'tools'}...`;
                    fillNextField();
                })
                .catch(() => {
                    currentIndex++;
                    fillNextField();
                });
            };

            fetch('/ai/generate-field', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    field: 'log_location',
                    log_day: day,
                    log_location: '',
                    log_place: place,
                    job_scope: jobScope,
                    log_summary: userInput,
                    log_knowledge: '',
                    log_tools: '',
                    log_note: '',
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.content) {
                    document.getElementById('log-location').value = data.content;
                }
                aiProgress.style.width = '30%';
                fillNextField();
            })
            .catch(() => {
                aiProgress.style.width = '30%';
                fillNextField();
            });
        }

        function generateField(fieldName, btnElement) {
            const day = document.getElementById('day-number').value;
            const location = document.getElementById('log-location').value.trim();
            const place = document.getElementById('log-place').value.trim();
            const jobScope = '{{ $defaultSettings->default_job_scope ?? '' }}';
            const summary = document.getElementById('activities').value.trim();
            const knowledge = document.getElementById('log-knowledge').value.trim();
            const tools = document.getElementById('log-tools').value.trim();
            const note = document.getElementById('log-note').value.trim();

            btnElement.disabled = true;
            btnElement.classList.add('loading');
            btnElement.textContent = '✨ Guna AI';

            fetch('/ai/generate-field', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    field: fieldName,
                    log_day: day,
                    log_location: location,
                    log_place: place,
                    job_scope: jobScope,
                    log_summary: summary,
                    log_knowledge: knowledge,
                    log_tools: tools,
                    log_note: note,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.content) {
                    const idMap = {
                        'log_summary': 'activities',
                        'log_knowledge': 'log-knowledge',
                        'log_tools': 'log-tools',
                        'log_note': 'log-note'
                    };
                    const inputId = idMap[fieldName] || fieldName;
                    const input = document.getElementById(inputId);
                    if (input) {
                        input.value = data.content;
                        if (fieldName === 'log_summary') countChars(input, 'act-count', 800);
                    }
                    btnElement.classList.add('ai-active');
                    btnElement.textContent = '✅ Selesai';
                    setTimeout(() => {
                        btnElement.disabled = false;
                        btnElement.classList.remove('loading', 'ai-active');
                        btnElement.textContent = '✨ Guna AI';
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Penjanaan gagal');
                }
            })
            .catch(err => {
                alert('Ralat: ' + err.message);
                btnElement.disabled = false;
                btnElement.classList.remove('loading');
                btnElement.textContent = '✨ Guna AI';
            });
        }

        function confirmSubmission() {
            const btn = document.getElementById('generateBtn');
            const aiOutput = document.getElementById('ai-output');

            if (btn.dataset.generated === 'true' && aiOutput.textContent && !aiOutput.textContent.includes('Sending') && !aiOutput.textContent.includes('Error')) {
                document.getElementById('activities').value = aiOutput.textContent;
            }
            return true;
        }

        document.getElementById('logForm').addEventListener('submit', function(e) {
            const summary = document.getElementById('activities').value.trim();
            if (!summary) {
                e.preventDefault();
                alert('Sila masukkan apa yang anda buat hari ini.');
                return;
            }

            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            for (let i = 0; i < uploadedFiles.length; i++) {
                formData.append('images[]', uploadedFiles[i]);
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Menyimpan...';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(data => Promise.reject(data));
                }
                return res.json();
            })
            .then(data => {
                if (data.success || data.id) {
                    uploadedFiles = [];
                    form.reset();
                    document.getElementById('preview-grid').innerHTML = '';
                    document.getElementById('file-count').textContent = '';
                    document.getElementById('ai-panel').style.display = 'none';
                    alert('Log berjaya disimpan!');
                }
            })
            .catch(err => {
                alert('Ralat: ' + (err.message || err.error || 'Gagal menyimpan log'));
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
});
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const editDate = urlParams.get('edit');
            if (editDate) {
                loadLogForEdit(editDate);
            }
        });

        function loadLogForEdit(date) {
            fetch(`/logs/${date}/edit`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.log) {
                    const log = data.log;
                    document.getElementById('day-number').value = log.log_day || '';
                    document.getElementById('log-date').value = log.log_date || '';
                    document.getElementById('log-location').value = log.log_location || '';
                    document.getElementById('log-place').value = log.log_place || '';
                    document.getElementById('activities').value = log.log_summary || '';
                    document.getElementById('log-knowledge').value = log.log_knowledge || '';
                    document.getElementById('log-tools').value = log.log_tools || '';
                    document.getElementById('log-note').value = log.log_note || '';
                    document.getElementById('logForm').scrollIntoView({ behavior: 'smooth' });
                }
            })
            .catch(err => console.error('Error loading log:', err));
        }

        @if(!$defaultSettings || !$defaultSettings->default_internship_period)
            window.addEventListener('load', function() {
                const modal = document.getElementById('defaultsModal');
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        @endif

        function closeDefaultsModal() {
            const modal = document.getElementById('defaultsModal');
            if (modal) modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function handleDefaultsSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            const formData = new FormData(form);

            fetch('/profile/defaults', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeDefaultsModal();
                    location.reload();
                } else {
                    alert(data.message || 'Ralat berlaku');
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            })
            .catch(err => {
                alert('Ralat: ' + err.message);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        }
    </script>

    <div id="aiPromptModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(8px);z-index:99999;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:20px;padding:2rem;max-width:500px;width:90%;box-shadow:0 20px 50px rgba(0,0,0,0.15);">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="font-size:2.5rem;margin-bottom:8px;">🤖</div>
                <h3 style="font-size:1.2rem;font-weight:800;color:#1F2937;">Siapkan Log dengan AI</h3>
                <p style="color:#6B7280;font-size:0.85rem;margin-top:4px;">Apakah yang anda buat hari ini?</p>
            </div>
            <textarea id="aiPromptInput" rows="4" placeholder="Terangkan secara ringkas apa yang anda buat hari ini... Contoh: Saya menyediakan dokumen UAT untuk sistem baru" style="width:100%;padding:12px;border:1px solid #E5E7EB;border-radius:12px;font-size:0.9rem;resize:vertical;margin-bottom:1rem;"></textarea>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="closeAiPromptModal()" style="flex:1;padding:12px;border:1.5px solid #E5E7EB;border-radius:12px;background:white;font-weight:600;color:#6B7280;cursor:pointer;">Batal</button>
                <button type="button" onclick="submitAiPrompt()" style="flex:1;padding:12px;border:none;border-radius:12px;background:#FF6B35;color:white;font-weight:700;cursor:pointer;"> Jana Log</button>
            </div>
        </div>
    </div>

    <div id="defaultsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(8px);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:20px;padding:2rem;max-width:460px;width:90%;box-shadow:0 20px 50px rgba(0,0,0,0.15);">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="font-size:2.5rem;margin-bottom:8px;">📋</div>
                <h3 style="font-size:1.3rem;font-weight:800;color:#1F2937;">Lengkapkan Profil Latihan</h3>
                <p style="color:#6B7280;font-size:0.85rem;margin-top:4px;">Sila isi maklumat asas latihan industri anda</p>
            </div>

            <form method="POST" action="/profile/defaults" onsubmit="handleDefaultsSubmit(event)">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px;">Tempoh Latihan (Hari)</label>
                    <input type="number" name="default_internship_period" placeholder="90" required min="1" style="width:100%;padding:12px;border:1px solid #E5E7EB;border-radius:12px;font-size:0.95rem;" />
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px;">Jabatan</label>
                    <input type="text" name="default_department" placeholder="Contoh: IT, Kewangan, HR" required style="width:100%;padding:12px;border:1px solid #E5E7EB;border-radius:12px;font-size:0.95rem;" />
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px;">Syarikat</label>
                    <input type="text" name="default_company" placeholder="Nama syarikat" style="width:100%;padding:12px;border:1px solid #E5E7EB;border-radius:12px;font-size:0.95rem;" />
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;color:#374151;margin-bottom:6px;">Skop Kerja</label>
                    <textarea name="default_job_scope" placeholder="Contoh: Pengaturcaraan, Penyelenggaraan Sistem" rows="3" style="width:100%;padding:12px;border:1px solid #E5E7EB;border-radius:12px;font-size:0.95rem;resize:vertical;"></textarea>
                </div>
                <button type="submit" style="width:100%;background:#FF6B35;color:white;border:none;border-radius:12px;padding:14px;font-weight:700;font-size:1rem;cursor:pointer;">Simpan & Teruskan</button>
            </form>
        </div>
    </div>
</body>
</html>