@php
$user = Auth::user();
$today = date('d/m/Y');
$defaultSettings = $user->defaults;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyInternLog — Profil</title>
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

        .profile-card { background: var(--card); border: 1px solid var(--gray-200); border-radius: 20px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .profile-card-header { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--gray-100); }
        .profile-card-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(255,107,53,0.1); color: var(--orange); font-size: 1.4rem; display: flex; align-items: center; justify-content: center; }
        .profile-card-title { font-size: 1.1rem; font-weight: 800; color: var(--gray-800); }
        .profile-card-subtitle { font-size: 0.78rem; color: var(--gray-400); margin-top: 2px; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 700; color: var(--gray-600); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid var(--gray-200); border-radius: 10px; font-size: 0.9rem; font-family: inherit; background: var(--white); transition: border-color 0.15s; }
        .form-input:focus { outline: none; border-color: var(--orange); }
        .form-input::placeholder { color: var(--gray-400); }
        .form-hint { font-size: 0.72rem; color: var(--gray-400); margin-top: 4px; }

        .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }

        .btn-orange { padding: 10px 20px; border-radius: 10px; background: var(--orange); color: var(--white); font-size: 0.85rem; font-weight: 700; border: none; cursor: pointer; transition: background 0.15s; }
        .btn-orange:hover { background: var(--orange-light); }
        .btn-outline { padding: 10px 20px; border-radius: 10px; background: transparent; color: var(--gray-600); font-size: 0.85rem; font-weight: 700; border: 1.5px solid var(--gray-200); cursor: pointer; transition: all 0.15s; }
        .btn-outline:hover { border-color: var(--orange); color: var(--orange); }

        .btn-group { display: flex; gap: 12px; margin-top: 20px; }
        .btn-group-right { display: flex; gap: 12px; margin-top: 20px; justify-content: flex-end; }

        .status-message { padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; margin-bottom: 16px; display: none; }
        .status-message.success { background: rgba(34,197,94,0.1); color: #16a34a; border: 1px solid rgba(34,197,94,0.2); display: block; }
        .status-message.error { background: rgba(239,68,68,0.1); color: #dc2626; border: 1px solid rgba(239,68,68,0.2); display: block; }

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
        <a href="{{ route('print') }}" class="nav-item">
            <span class="nav-icon">🖨️</span>
            Cetak
            <span class="nav-arrow">→</span>
        </a>

        <div class="nav-divider"></div>
        <div class="nav-section-label">Akaun</div>
        <a href="{{ route('profile.edit') }}" class="nav-item active">
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
                    <strong>Profil</strong>
                </span>
            </div>
            <div class="topbar-right">
                <span class="topbar-date-pill">📅 {{ $today }}</span>
            </div>
        </div>

        <div class="page-content">
            <h1 class="page-title">👤 Profil</h1>
            <p class="page-subtitle">Ubah kata laluan dan tetapan lalai anda</p>

            <div id="statusMessage" class="status-message"></div>

            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon">🔒</div>
                    <div>
                        <div class="profile-card-title">Ubah Kata Laluan</div>
                        <div class="profile-card-subtitle">Pastikan akaun anda menggunakan kata laluan yang panjang dan rawak</div>
                    </div>
                </div>

                <form id="passwordForm" method="POST" action="/password">
                    @csrf
                    @method('PUT')
                    <div id="passwordError" class="status-message error"></div>

                    <div class="form-group">
                        <label class="form-label" for="current_password">Kata Laluan Semasa</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password" placeholder="Masukkan kata laluan semasa">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Kata Laluan Baru</label>
                            <input type="password" id="password" name="password" class="form-input" autocomplete="new-password" placeholder="Masukkan kata laluan baru">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Sahkan Kata Laluan</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password" placeholder="Sahkan kata laluan baru">
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn-orange">💾 Simpan</button>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon">⚙️</div>
                    <div>
                        <div class="profile-card-title">Tetapan Lalai</div>
                        <div class="profile-card-subtitle">Ubah tetapan lalai untuk entri log baru</div>
                    </div>
                </div>

                <form id="defaultsForm" method="POST" action="/profile/defaults">
                    @csrf
                    <div id="defaultsError" class="status-message error"></div>

                    <div class="form-group">
                        <label class="form-label" for="default_department">Jabatan/Unit/Bahagian</label>
                        <input type="text" id="default_department" name="default_department" class="form-input" placeholder="Contoh: IT, Kewangan, HR" value="{{ $defaultSettings->default_department ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="default_location">Lokasi/Tempat</label>
                        <input type="text" id="default_location" name="default_location" class="form-input" placeholder="Contoh: Aras 2, Blok A, Kuala Lumpur" value="{{ $defaultSettings->default_location ?? '' }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="default_internship_period">Tempoh Latihan (Hari)</label>
                            <input type="number" id="default_internship_period" name="default_internship_period" class="form-input" min="1" placeholder="Contoh: 90" value="{{ $defaultSettings->default_internship_period ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="default_company">Syarikat</label>
                            <input type="text" id="default_company" name="default_company" class="form-input" placeholder="Nama syarikat latihan" value="{{ $defaultSettings->default_company ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="default_job_scope">Skop Kerja</label>
                        <input type="text" id="default_job_scope" name="default_job_scope" class="form-input" placeholder="Skop kerja semasa latihan" value="{{ $defaultSettings->default_job_scope ?? '' }}">
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn-orange">💾 Simpan Tetapan</button>
                    </div>
                </form>
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

        function showStatus(message, type = 'success') {
            const statusEl = document.getElementById('statusMessage');
            statusEl.textContent = message;
            statusEl.className = 'status-message ' + type;
            statusEl.style.display = 'block';
            setTimeout(() => { statusEl.style.display = 'none'; }, 3000);
        }

        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const errorEl = document.getElementById('passwordError');
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok || response.status === 302) {
                    form.reset();
                    showStatus('Kata laluan berjaya dikemas kini!');
                    errorEl.style.display = 'none';
                } else {
                    let errorMsg = 'Ralat tidak dapat dikemas kini.';
                    if (result.errors && result.errors.current_password) {
                        errorMsg = result.errors.current_password[0];
                    } else if (result.message) {
                        errorMsg = result.message;
                    }
                    errorEl.textContent = errorMsg;
                    errorEl.style.display = 'block';
                }
            } catch (error) {
                errorEl.textContent = ' Berlaku ralat. Sila Cuba lagi.';
                errorEl.style.display = 'block';
            }
        });

        document.getElementById('defaultsForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const errorEl = document.getElementById('defaultsError');
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showStatus('Tetapan lalai berjaya disimpan!');
                    errorEl.style.display = 'none';
                } else {
                    let errorMsg = 'Ralat semasa menyimpan tetapan.';
                    if (result.errors) {
                        const firstError = Object.values(result.errors)[0];
                        errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                    }
                    errorEl.textContent = errorMsg;
                    errorEl.style.display = 'block';
                }
            } catch (error) {
                errorEl.textContent = 'Berlaku ralat. Sila Cuba lagi.';
                errorEl.style.display = 'block';
            }
        });
    </script>
</body>
</html>