<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyInternLog — Cetak</title>
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
            --border: rgba(255,107,53,0.15);
            --sidebar-w: 260px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: var(--gray-50); color: var(--gray-800); }
        .syne { font-family: 'Figtree', sans-serif; }

        #sidebar { position: fixed; top: 0; left: 0; bottom: 0; width: var(--sidebar-w); background: var(--white); border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; z-index: 100; transition: transform 0.3s cubic-bezier(.4,0,.2,1); }
        #sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

        .sidebar-logo { padding: 22px 20px 18px; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center; gap: 10px; }
        .sidebar-logo .logo-icon { font-size: 1.6rem; }
        .sidebar-logo .logo-text { font-size: 1.15rem; font-weight: 800; color: var(--gray-800); }
        .sidebar-logo .logo-text span { color: var(--orange); }

        .nav-section-label { font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--gray-400); padding: 18px 20px 6px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 20px; margin: 2px 10px; border-radius: 12px; cursor: pointer; text-decoration: none; color: var(--gray-600); font-weight: 600; font-size: 0.9rem; transition: background 0.15s, color 0.15s; }
        .nav-item:hover { background: rgba(255,107,53,0.07); color: var(--gray-800); }
        .nav-item.active { background: rgba(255,107,53,0.1); color: var(--orange); border: 1px solid rgba(255,107,53,0.2); }
        .nav-item .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }
        .nav-item .nav-arrow { margin-left: auto; font-size: 0.7rem; opacity: 0; transition: opacity 0.15s; }
        .nav-item:hover .nav-arrow { opacity: 1; }
        .nav-divider { height: 1px; background: linear-gradient(90deg, transparent, var(--gray-200), transparent); margin: 12px 20px; }

        .sidebar-profile { margin-top: auto; padding: 16px; background: var(--gray-50); border-top: 1px solid var(--gray-200); }
        .profile-wrapper { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; background: var(--white); border: 1px solid var(--gray-200); }
        .avatar { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, var(--orange), var(--orange-light)); color: var(--white); font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
        .avatar-info .name { font-size: 0.8rem; font-weight: 700; color: var(--gray-800); }
        .avatar-info .role { font-size: 0.65rem; color: var(--gray-400); }
        .logout-btn { display: flex; align-items: center; gap: 10px; width: 100%; margin-top: 10px; padding: 10px 14px; border-radius: 10px; border: 1.5px solid var(--gray-200); background: var(--white); color: var(--gray-500); font-size: 0.82rem; font-weight: 600; cursor: pointer; }
        .logout-btn:hover { border-color: #EF4444; color: #EF4444; }
        .logout-btn .nav-icon { font-size: 1rem; }

        #main { margin-left: var(--sidebar-w); min-height: 100vh; transition: margin-left 0.3s cubic-bezier(.4,0,.2,1); }
        #main.expanded { margin-left: 0; }

        #topbar { position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--gray-200); padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; }
        #sidebar-toggle { width: 36px; height: 36px; border-radius: 10px; background: rgba(255,107,53,0.08); border: 1px solid var(--gray-200); cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; }
        #sidebar-toggle:hover { background: rgba(255,107,53,0.15); }
        #sidebar-toggle span { display: block; width: 16px; height: 2px; background: var(--orange); border-radius: 2px; }
        .topbar-breadcrumb { font-size: 0.8rem; color: var(--gray-400); margin-left: 14px; }
        .topbar-breadcrumb strong { color: var(--gray-800); font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-date-pill { padding: 5px 14px; border-radius: 999px; background: rgba(255,107,53,0.08); border: 1px solid rgba(255,107,53,0.2); font-size: 0.75rem; color: var(--orange); font-weight: 700; }

        .page-content { padding: 28px 28px 60px; }
        .page-title { font-size: 1.4rem; font-weight: 800; color: var(--gray-800); margin-bottom: 6px; }
        .page-subtitle { font-size: 0.8rem; color: var(--gray-400); margin-bottom: 24px; }

        .print-options-card { background: var(--card); border: 1px solid var(--gray-200); border-radius: 20px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .print-options-title { font-size: 1rem; font-weight: 700; color: var(--gray-800); margin-bottom: 16px; }

        .print-option-group { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
        .print-option-label { font-size: 0.8rem; font-weight: 600; color: var(--gray-600); margin-bottom: 6px; display: block; }
        .print-radio-group { display: flex; gap: 16px; flex-wrap: wrap; }
        .print-radio-item { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .print-radio-item input[type="radio"] { accent-color: var(--orange); width: 16px; height: 16px; }
        .print-radio-item span { font-size: 0.85rem; font-weight: 500; color: var(--gray-700); }

        .date-range-inputs { display: none; gap: 12px; align-items: center; flex-wrap: wrap; }
        .date-range-inputs.visible { display: flex; }
        .date-input-wrapper { display: flex; align-items: center; gap: 8px; }
        .date-input-wrapper label { font-size: 0.75rem; color: var(--gray-500); }
        .date-input-wrapper input { padding: 8px 12px; border: 1.5px solid var(--gray-200); border-radius: 10px; font-size: 0.85rem; font-family: inherit; }
        .date-input-wrapper input:focus { outline: none; border-color: var(--orange); }

        .btn-group { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-orange { padding: 10px 20px; border-radius: 10px; background: var(--orange); color: var(--white); font-size: 0.85rem; font-weight: 700; border: none; cursor: pointer; transition: background 0.15s; }
        .btn-orange:hover { background: var(--orange-light); }
        .btn-orange:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-outline { padding: 10px 20px; border-radius: 10px; background: transparent; color: var(--gray-600); font-size: 0.85rem; font-weight: 700; border: 1.5px solid var(--gray-200); cursor: pointer; transition: all 0.15s; }
        .btn-outline:hover { border-color: var(--orange); color: var(--orange); }

        .search-card { background: var(--card); border: 1px solid var(--gray-200); border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .search-bar { display: flex; gap: 12px; align-items: center; }
        .search-input { flex: 1; padding: 10px 16px; border: 1.5px solid var(--gray-200); border-radius: 10px; font-size: 0.9rem; font-family: inherit; }
        .search-input:focus { outline: none; border-color: var(--orange); }
        .search-btn { padding: 10px 16px; border-radius: 10px; background: rgba(255,107,53,0.08); border: 1.5px solid var(--gray-200); color: var(--gray-600); font-weight: 600; cursor: pointer; }
        .search-btn:hover { border-color: var(--orange); color: var(--orange); }

        .logs-table-card { background: var(--card); border: 1px solid var(--gray-200); border-radius: 20px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .logs-table-header { padding: 16px 20px; border-bottom: 1px solid var(--gray-100); display: flex; align-items: center; justify-content: space-between; }
        .logs-table-title { font-weight: 700; font-size: 0.95rem; color: var(--gray-800); }
        .logs-table { width: 100%; border-collapse: collapse; }
        .logs-table th { text-align: left; padding: 12px 16px; font-size: 0.72rem; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: 0.05em; background: var(--gray-50); border-bottom: 1px solid var(--gray-100); }
        .logs-table td { padding: 14px 16px; border-bottom: 1px solid var(--gray-100); font-size: 0.85rem; }
        .logs-table tr:last-child td { border-bottom: none; }
        .logs-table tr:hover td { background: rgba(255,107,53,0.03); }

        .day-badge { min-width: 40px; height: 40px; border-radius: 10px; background: rgba(255,107,53,0.1); color: var(--orange); font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; }
        .log-summary { font-weight: 600; color: var(--gray-800); }
        .log-date { color: var(--gray-400); font-size: 0.78rem; }
        .log-status { font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 999px; background: rgba(255,107,53,0.1); color: var(--orange); border: 1px solid rgba(255,107,53,0.2); white-space: nowrap; }

        .print-page-btn { padding: 6px 12px; border-radius: 8px; background: rgba(255,107,53,0.08); border: 1px solid rgba(255,107,53,0.2); color: var(--orange); font-size: 0.75rem; font-weight: 700; cursor: pointer; }
        .print-page-btn:hover { background: rgba(255,107,53,0.15); }

        .pagination { display: flex; justify-content: center; gap: 8px; padding: 20px; }
        .pagination-btn { padding: 8px 14px; border-radius: 10px; border: 1.5px solid var(--gray-200); background: var(--white); color: var(--gray-600); font-size: 0.82rem; font-weight: 600; cursor: pointer; }
        .pagination-btn:hover:not(:disabled) { border-color: var(--orange); color: var(--orange); }
        .pagination-btn:disabled { opacity: 0.4; cursor: not-allowed; }
        .pagination-btn.active { background: var(--orange); color: var(--white); border-color: var(--orange); }

        .empty-state { text-align: center; padding: 48px 24px; color: var(--gray-400); }
        .empty-state-icon { font-size: 3rem; margin-bottom: 12px; }
        .empty-state-text { font-size: 0.9rem; font-weight: 600; margin-bottom: 6px; color: var(--gray-500); }

        #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 99; }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0; }
            .page-content { padding: 12px 10px 60px; }
            #topbar { padding: 10px 12px; }
            .topbar-breadcrumb { display: none; }
        }
    </style>
</head>
<body>
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
        <a href="{{ route('logs.index') }}" class="nav-item">
            <span class="nav-icon">📋</span>
            Log Saya
            <span class="nav-arrow">→</span>
        </a>
        <a href="{{ route('print') }}" class="nav-item active">
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
                    <strong>Cetak</strong>
                </span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date-pill">📅 {{ $today }}</span>
            </div>
        </div>

        <div class="page-content">
            <h1 class="page-title">🖨️ Cetak Log</h1>
            <p class="page-subtitle">Sedia untuk mencetak log latihan industri anda</p>

            <div class="print-options-card">
                <div class="print-options-title">Pilihan Cetakan</div>

                <div class="print-option-group">
                    <span class="print-option-label">Jenis Cetakan</span>
                    <div class="print-radio-group">
                        <label class="print-radio-item">
                            <input type="radio" name="print_type" value="all" checked onchange="toggleDateRange()">
                            <span>Cetak Semua Log</span>
                        </label>
                        <label class="print-radio-item">
                            <input type="radio" name="print_type" value="range" onchange="toggleDateRange()">
                            <span>Cetak Mengikut Julat Tarikh</span>
                        </label>
                    </div>
                </div>

                <div id="dateRangeInputs" class="date-range-inputs">
                    <div class="date-input-wrapper">
                        <label for="start_date">Dari:</label>
                        <input type="date" id="start_date" name="start_date">
                    </div>
                    <div class="date-input-wrapper">
                        <label for="end_date">Hingga:</label>
                        <input type="date" id="end_date" name="end_date">
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn-orange" onclick="alert('Funzioni cetak belum disediakan')">🖨️ Cetak Log</button>
                </div>
            </div>

            <div class="search-card">
                <div class="search-bar">
                    <input type="text" id="searchInput" class="search-input" placeholder="Cari log..." oninput="filterLogs()">
                    <button type="button" class="search-btn" onclick="filterLogs()">🔍</button>
                </div>
            </div>

            <div class="logs-table-card">
                <div class="logs-table-header">
                    <span class="logs-table-title">Senarai Log</span>
                    <span class="log-count-badge">{{ $logs->count() }} entri</span>
                </div>

                @if($logs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <div class="empty-state-text">Tiada log ditemui</div>
                        <div class="empty-state-sub">Mulakan dengan membuat log baru di Papan Pemuka</div>
                    </div>
                @else
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Ringkasan</th>
                                <th>Tarikh</th>
                                <th>Status</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            @foreach($logs as $log)
                                <tr class="log-row" data-summary="{{ strtolower($log->log_summary) }}">
                                    <td><div class="day-badge">{{ $log->log_day }}</div></td>
                                    <td class="log-summary">{{ $log->log_summary }}</td>
                                    <td class="log-date">{{ $log->log_date->format('d/m/Y') }}</td>
                                    <td><span class="log-status">{{ $log->log_status }}</span></td>
                                    <td><button type="button" class="print-page-btn" onclick="alert('Funzioni cetak belum disediakan')">🖨️ Cetak</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination" id="paginationContainer">
                        <button type="button" class="pagination-btn" id="prevPageBtn" onclick="changePage(-1)" disabled>←</button>
                        <button type="button" class="pagination-btn active">1</button>
                        <button type="button" class="pagination-btn" id="nextPageBtn" onclick="changePage(1)">→</button>
                    </div>
                @endif
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

        function toggleDateRange() {
            const printType = document.querySelector('input[name="print_type"]:checked').value;
            const dateRangeInputs = document.getElementById('dateRangeInputs');
            dateRangeInputs.classList.toggle('visible', printType === 'range');
        }

        function filterLogs() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.log-row');
            rows.forEach(row => {
                const summary = row.getAttribute('data-summary');
                row.style.display = summary.includes(searchTerm) ? '' : 'none';
            });
        }

        let currentPage = 1;
        const logsPerPage = 10;

        function changePage(direction) {
            currentPage += direction;
            alert('Pagination belum disediakan');
        }
    </script>
</body>
</html>