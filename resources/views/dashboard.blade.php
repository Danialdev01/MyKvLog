@php
$user = Auth::user();
$monthYear = date('F Y');
$bulanMelayu = ['January'=>'Januari','February'=>'Februari','March'=>'Mac','April'=>'April','May'=>'Mei','June'=>'Jun','July'=>'Julai','August'=>'Ogos','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Disember'];
$monthYear = ($bulanMelayu[date('F')] ?? date('F')) . ' ' . date('Y');
$recentLogs = $user->logs()->orderBy('log_day', 'desc')->limit(5)->get();
$totalLogs = $user->logs()->count();
$defaultSettings = $user->defaults;
$internshipPeriod = $defaultSettings->default_internship_period ?? 90;
$currentDay = $totalLogs + 1;
$completionPercent = min(100, round(($totalLogs / max(1, $internshipPeriod)) * 100));
@endphp

@extends('layouts.shell')

@section('title', 'MyKvLog — Papan Pemuka')
@section('breadcrumb', 'Papan Pemuka')

@push('styles')
<style>
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; margin-bottom: 28px; }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--gray-200);
        border-radius: 16px; padding: 18px 20px;
        position: relative; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .stat-card .stat-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gray-500); margin-bottom: 6px; }
    .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-sub { font-size: 0.7rem; color: var(--gray-500); margin-top: 4px; }

    .progress-wrap { background: var(--gray-100); border-radius: 999px; height: 6px; }
    .progress-bar { height: 6px; border-radius: 999px; background: var(--orange); transition: width 1.2s ease; }

    .main-cols { display: grid; grid-template-columns: 1fr; gap: 22px; }
    @media (min-width: 1100px) { .main-cols { grid-template-columns: 1fr 340px; } }

    .edit-banner {
        display: none;
        align-items: center; gap: 10px; flex-wrap: wrap;
        background: rgba(255,107,53,0.08);
        border: 1.5px solid rgba(255,107,53,0.35);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 0.85rem; font-weight: 600; color: var(--gray-800);
    }
    .edit-banner.active { display: flex; }
    .edit-banner .cancel-edit {
        margin-left: auto;
        background: #fff; border: 1.5px solid var(--gray-200);
        border-radius: 8px; padding: 6px 12px;
        font-size: 0.75rem; font-weight: 700; color: var(--gray-600);
        cursor: pointer; font-family: inherit;
    }
    .edit-banner .cancel-edit:hover { border-color: var(--orange); color: var(--orange-deep); }

    .upload-zone {
        border: 2px dashed rgba(255,107,53,0.25);
        border-radius: 14px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        background: rgba(255,107,53,0.02);
    }
    .upload-zone:hover, .upload-zone.dragover { border-color: var(--orange); background: rgba(255,107,53,0.05); }
    .upload-zone input[type="file"] { display: none; }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-zone p { font-size: 0.8rem; color: var(--gray-500); }
    .upload-zone p strong { color: var(--orange-deep); }

    #preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px,1fr)); gap: 10px; margin-top: 14px; }
    .preview-thumb {
        position: relative; border-radius: 10px; overflow: hidden;
        aspect-ratio: 1; background: var(--gray-100);
        border: 1px solid var(--gray-200);
    }
    .preview-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .preview-thumb .remove-img {
        position: absolute; top: 4px; right: 4px;
        width: 22px; height: 22px; border-radius: 50%;
        background: rgba(255,107,53,0.9); color: #fff;
        font-size: 0.65rem; cursor: pointer; border: none;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
    }

    .char-counter { font-size: 0.68rem; color: var(--gray-500); text-align: right; margin-top: 4px; }
    .char-counter.warn { color: var(--orange-deep); }

    .input-with-ai { position: relative; }
    .ai-btn-small {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: rgba(255,107,53,0.1);
        border: 1px solid rgba(255,107,53,0.25);
        color: var(--orange-deep);
        font-size: 0.65rem; font-weight: 700;
        padding: 4px 10px; border-radius: 6px;
        cursor: pointer; white-space: nowrap; font-family: inherit;
        transition: background 0.15s, border-color 0.15s;
    }
    .ai-btn-small:hover { background: rgba(255,107,53,0.2); border-color: var(--orange); }
    .ai-btn-small:disabled { opacity: 0.5; cursor: not-allowed; }
    .ai-btn-small.loading::after {
        content: '';
        display: inline-block;
        width: 10px; height: 10px;
        border: 1.5px solid var(--orange-deep);
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-left: 4px;
        vertical-align: middle;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .ai-panel {
        background: linear-gradient(135deg, rgba(255,107,53,0.05), rgba(255,107,53,0.02));
        border: 1px solid rgba(255,107,53,0.15);
        border-radius: 16px; padding: 18px 20px;
        margin-top: 24px;
    }
    .ai-panel-header { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .ai-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--orange);
        animation: pulse 1.6s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.75)} }
    .ai-hint {
        position: absolute; bottom: 10px; right: 12px;
        display: flex; align-items: center; gap: 5px;
        font-size: 0.65rem; color: var(--gray-500); font-weight: 600;
        pointer-events: none;
    }
    .ai-hint .ai-dot { width: 6px; height: 6px; }

    .right-col { display: flex; flex-direction: column; gap: 20px; }
    @media (max-width: 768px) { .right-col { display: none; } }

    .tips-list { padding: 16px 18px; }
    .tips-list .tip { display: flex; gap: 8px; align-items: flex-start; margin-bottom: 10px; }
    .tips-list .tip:last-child { margin-bottom: 0; }
    .tips-list .tip .tip-icon { font-size: 0.85rem; flex-shrink: 0; }
    .tips-list .tip .tip-text { font-size: 0.78rem; color: var(--gray-600); line-height: 1.5; }

    .recent-log-item {
        background: var(--card);
        border: 1px solid var(--gray-200);
        border-radius: 14px; padding: 12px 16px;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 10px;
        transition: transform 0.15s, border-color 0.15s;
        text-decoration: none; color: inherit;
    }
    .recent-log-item:hover { transform: translateX(4px); border-color: rgba(255,107,53,0.3); }
    .recent-log-item .day { font-weight: 800; font-size: 1rem; min-width: 40px; text-align: center; color: var(--orange-deep); }
    .recent-log-item .divider { width: 1px; height: 36px; background: var(--gray-200); flex-shrink: 0; }
    .recent-log-item .summary {
        font-size: 0.82rem; font-weight: 600; color: var(--gray-800); margin-bottom: 2px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .recent-log-item .date { font-size: 0.7rem; color: var(--gray-500); }

    .print-cta {
        background: linear-gradient(135deg, rgba(255,107,53,0.08), rgba(255,107,53,0.03));
        border: 1px solid rgba(255,107,53,0.2);
        border-radius: 16px; padding: 18px 20px; text-align: center;
    }

    @media (max-width: 480px) {
        .stat-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .stat-card { padding: 12px; }
        .stat-card .stat-value { font-size: 1.4rem; }
        .btn-group { flex-direction: column; }
        .btn-group > * { width: 100%; }
    }
</style>
@endpush

@section('content')
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Hari</div>
            <div class="stat-value" style="color:var(--orange-deep);">{{ $currentDay }}</div>
            <div class="stat-sub">daripada {{ $internshipPeriod }} hari latihan industri</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Log Selesai</div>
            <div class="stat-value" style="color:var(--gray-700);">{{ $totalLogs }}</div>
            <div class="stat-sub">entri selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Minggu Ini</div>
            <div class="stat-value" style="color:var(--orange-deep);">{{ $user->logs()->where('log_date', '>=', now()->startOfWeek())->count() }}</div>
            <div class="stat-sub">entri minggu ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Kemajuan</div>
            <div class="stat-value" style="color:var(--orange-deep);">{{ $completionPercent }}%</div>
            <div class="stat-sub">kemajuan ke matlamat</div>
            <div class="progress-wrap" style="margin-top:10px;">
                <div class="progress-bar" style="width:{{ $completionPercent }}%;"></div>
            </div>
        </div>
    </div>

    <div class="main-cols">
        <div>
            <div class="section-header">
                <div>
                    <div class="section-title">✍️ Entri Log Hari Ini</div>
                    <div style="font-size:0.7rem;color:var(--gray-500);margin-top:2px;">{{ $monthYear }} · Isi aktiviti harian anda</div>
                </div>
                <span class="section-tag">Hari {{ $currentDay }}</span>
            </div>

            <div id="edit-banner" class="edit-banner" role="status">
                ✏️ Anda sedang mengedit log untuk <strong id="edit-banner-date"></strong>
                <button type="button" class="cancel-edit" onclick="exitEditMode(true)">Batal — kembali ke entri baru</button>
            </div>

            <div class="card">
                <div class="card-header">
                    <span style="font-size:1.3rem;" aria-hidden="true">📝</span>
                    <div>
                        <div class="card-title" id="form-card-title">Entri Log Baru</div>
                        <div class="card-subtitle">Semua ruangan bertanda <span style="color:var(--orange-deep);">*</span> diperlukan</div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="logForm" method="POST" action="{{ route('logs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid" style="margin-bottom:20px;">
                            <div>
                                <label class="field-label" for="day-number">
                                    Hari Latihan Industri <span class="field-required">*</span>
                                </label>
                                <div style="position:relative;">
                                    <input type="number" id="day-number" name="log_day" class="input-base" placeholder="cth. {{ $currentDay }}" min="1" max="365" value="{{ $currentDay }}" required />
                                    <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:0.75rem;color:var(--gray-500);">/ {{ $internshipPeriod }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="field-label" for="log-date">
                                    Tarikh <span class="field-required">*</span>
                                </label>
                                <input type="date" id="log-date" name="log_date" class="input-base" value="{{ date('Y-m-d') }}" required onchange="checkExistingLog()" />
                                <div class="field-hint">Pilih tarikh yang sudah ada log untuk mengeditnya.</div>
                            </div>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label class="field-label" for="log-location">Jabatan/Unit/Bahagian</label>
                            <input type="text" id="log-location" name="log_location" class="input-base" placeholder="cth. Jabatan IT, Makmal Elektrik" value="{{ $defaultSettings->default_department ?? '' }}" />
                        </div>

                        <div style="margin-bottom:20px;">
                            <label class="field-label" for="log-place">Lokasi Tempat</label>
                            <input type="text" id="log-place" name="log_place" class="input-base" placeholder="cth. Aras 2, Blok A, Kuala Lumpur" value="{{ $defaultSettings->default_location ?? '' }}" />
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
                            <label class="field-label" for="log-knowledge">Pengetahuan &amp; Pembelajaran</label>
                            <textarea id="log-knowledge" name="log_knowledge" class="input-base" rows="2" placeholder="Apa yang anda pelajari hari ini? Apa-apa kemahiran atau pandangan baru?"></textarea>
                            <button type="button" class="ai-btn-small" onclick="generateField('log_knowledge', this)">✨ Guna AI</button>
                        </div>

                        <div style="margin-bottom:24px;" class="input-with-ai">
                            <label class="field-label" for="log-tools">Alat Digunakan</label>
                            <input type="text" id="log-tools" name="log_tools" class="input-base" placeholder="cth. Cable tester, punch down tool (pisahkan dengan koma)" />
                            <button type="button" class="ai-btn-small" onclick="generateField('log_tools', this)">✨ Guna AI</button>
                        </div>

                        <div style="margin-bottom:24px;" class="input-with-ai">
                            <label class="field-label" for="log-note">Nota Tambahan</label>
                            <textarea id="log-note" name="log_note" class="input-base" rows="2" placeholder="Apa-apa nota atau pemerhatian tambahan..."></textarea>
                            <button type="button" class="ai-btn-small" onclick="generateField('log_note', this)">✨ Guna AI</button>
                        </div>

                        <div style="margin-bottom:24px;" id="upload-section">
                            <label class="field-label">
                                📸 Foto / Bukti <span style="color:var(--gray-500);font-weight:400;text-transform:none;letter-spacing:0;">(opsyen tapi disyorkan)</span>
                            </label>
                            <div class="upload-zone" id="upload-zone" tabindex="0" role="button"
                                 aria-label="Muat naik foto"
                                 onclick="document.getElementById('file-input').click()"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();document.getElementById('file-input').click();}"
                                 ondragover="dragOver(event)" ondrop="dropFiles(event)" ondragleave="dragLeave(event)">
                                <input type="file" id="file-input" multiple accept="image/*" onchange="handleFiles(this.files)"/>
                                <div class="upload-icon" aria-hidden="true">📂</div>
                                <p><strong>Klik untuk muat naik</strong> atau seret &amp; letak foto di sini</p>
                                <p style="margin-top:4px;font-size:0.7rem;">PNG, JPG, WEBP · Maks 5MB setiap satu · sehingga 10 gambar</p>
                            </div>
                            <div id="preview-grid"></div>
                            <div id="file-count" style="font-size:0.72rem;color:var(--gray-500);margin-top:8px;"></div>
                        </div>

                        <div style="margin-bottom:24px;display:none;" id="upload-disabled-note">
                            <div class="field-hint" style="background:var(--gray-100);border-radius:10px;padding:10px 14px;">
                                ℹ️ Muat naik foto hanya tersedia semasa membuat entri baru. Foto sedia ada dikekalkan.
                            </div>
                        </div>

                        <div class="ai-panel" id="ai-panel" style="display:none;">
                            <div class="ai-panel-header">
                                <div class="ai-dot"></div>
                                <span style="font-weight:700;font-size:0.9rem;color:var(--orange-deep);">AI sedang menulis log anda...</span>
                            </div>
                            <div style="font-size:0.82rem;color:var(--gray-600);line-height:1.7;" id="ai-output">
                                Menganalisis aktiviti anda dan mengembangkan ke format log profesional...
                            </div>
                            <div class="progress-wrap" style="margin-top:12px;">
                                <div class="progress-bar" id="ai-progress" style="width:0%;"></div>
                            </div>
                        </div>

                        <div class="btn-group" style="margin-top:4px;">
                            <button type="submit" id="submit-btn" class="btn-orange">💾 Simpan</button>
                            <button type="button" id="generateBtn" class="btn-soft" onclick="generateWithAI()">🤖 Siapkan guna AI</button>
                            <button type="button" class="btn-outline" onclick="clearForm()">🗑️ Kosongkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <span aria-hidden="true">💡</span>
                    <div class="card-title" style="font-size:0.9rem;">Tips Penulisan</div>
                </div>
                <div class="tips-list">
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
                    <div class="tip">
                        <span class="tip-icon" aria-hidden="true">{{ $tip[0] }}</span>
                        <span class="tip-text">{{ $tip[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="section-header" style="margin-bottom:12px;">
                    <div class="section-title" style="font-size:0.95rem;">📋 Log Terkini</div>
                    <a href="{{ route('logs.index') }}" style="font-size:0.72rem;color:var(--orange-deep);font-weight:700;text-decoration:none;">Lihat semua →</a>
                </div>

                @forelse($recentLogs as $log)
                <a href="{{ route('dashboard') }}?edit={{ $log->log_date->format('Y-m-d') }}" class="recent-log-item">
                    <div class="day">D{{ $log->log_day }}</div>
                    <div class="divider"></div>
                    <div style="flex:1;min-width:0;">
                        <div class="summary">{{ Str::limit($log->log_summary, 50) }}</div>
                        <div class="date">{{ $log->log_date->format('d/m/Y') }}</div>
                    </div>
                </a>
                @empty
                <div class="empty-state" style="padding:24px;">
                    <div class="empty-state-text">Belum ada log. Mula tulis entri pertama anda!</div>
                </div>
                @endforelse
            </div>

            <div class="print-cta">
                <div style="font-size:2rem;margin-bottom:8px;" aria-hidden="true">🖨️</div>
                <div style="font-weight:700;font-size:0.9rem;margin-bottom:4px;color:var(--gray-800);">Sedia untuk Cetak?</div>
                <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:14px;">Kompil log anda menjadi PDF yang sedia untuk dihantar</div>
                <a href="{{ route('print') }}" class="btn-orange" style="width:100%;">Pergi ke Cetak</a>
            </div>
        </div>
    </div>

    {{-- Modal: minta penerangan ringkas sebelum jana log penuh dengan AI --}}
    <div id="aiPromptModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="aiPromptTitle">
        <div class="modal-box" style="max-width:500px;">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="font-size:2.5rem;margin-bottom:8px;" aria-hidden="true">🤖</div>
                <h3 id="aiPromptTitle" style="font-size:1.2rem;font-weight:800;color:var(--gray-800);">Siapkan Log dengan AI</h3>
                <p style="color:var(--gray-500);font-size:0.85rem;margin-top:4px;">Apakah yang anda buat hari ini?</p>
            </div>
            <textarea id="aiPromptInput" rows="4" class="input-base" style="margin-bottom:1rem;resize:vertical;" placeholder="Terangkan secara ringkas apa yang anda buat hari ini... Contoh: Saya menyediakan dokumen UAT untuk sistem baru"></textarea>
            <div class="btn-group">
                <button type="button" class="btn-outline" style="flex:1;" onclick="closeAiPromptModal()">Batal</button>
                <button type="button" class="btn-orange" style="flex:1;" onclick="submitAiPrompt()">Jana Log</button>
            </div>
        </div>
    </div>

    {{-- Modal onboarding: lengkapkan tetapan lalai latihan --}}
    <div id="defaultsModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="defaultsTitle">
        <div class="modal-box" style="max-width:460px;">
            <div style="text-align:center;margin-bottom:1.5rem;">
                <div style="font-size:2.5rem;margin-bottom:8px;" aria-hidden="true">📋</div>
                <h3 id="defaultsTitle" style="font-size:1.3rem;font-weight:800;color:var(--gray-800);">Lengkapkan Profil Latihan</h3>
                <p style="color:var(--gray-500);font-size:0.85rem;margin-top:4px;">Sila isi maklumat asas latihan industri anda</p>
            </div>

            <form method="POST" action="{{ route('profile.defaults') }}" onsubmit="handleDefaultsSubmit(event)">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label class="field-label" for="def-period">Tempoh Latihan (Hari)</label>
                    <input type="number" id="def-period" name="default_internship_period" class="input-base" placeholder="90" required min="1" />
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="field-label" for="def-dept">Jabatan</label>
                    <input type="text" id="def-dept" name="default_department" class="input-base" placeholder="Contoh: IT, Kewangan, HR" required />
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="field-label" for="def-company">Syarikat</label>
                    <input type="text" id="def-company" name="default_company" class="input-base" placeholder="Nama syarikat" />
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label class="field-label" for="def-scope">Skop Kerja</label>
                    <textarea id="def-scope" name="default_job_scope" class="input-base" placeholder="Contoh: Pengaturcaraan, Penyelenggaraan Sistem" rows="3" style="resize:vertical;"></textarea>
                </div>
                <button type="submit" class="btn-orange" style="width:100%;">Simpan &amp; Teruskan</button>
                <button type="button" onclick="closeDefaultsModal()" style="width:100%;margin-top:10px;background:none;border:none;color:var(--gray-500);font-size:0.8rem;font-weight:600;cursor:pointer;font-family:inherit;padding:8px;">
                    Lengkapkan kemudian
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const existingLogDates = @json($existingLogDates ?? []);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const logForm = document.getElementById('logForm');
    const dateInput = document.getElementById('log-date');
    const submitBtn = document.getElementById('submit-btn');

    // Satu-satunya keadaan edit: null = entri baru, string tarikh = sedang edit log tarikh itu.
    let editingDate = null;
    let uploadedFiles = [];

    /* ===== Mod edit ===== */

    function formatTarikh(dateStr) {
        const [y, m, d] = dateStr.split('-');
        return `${d}/${m}/${y}`;
    }

    function enterEditMode(dateStr) {
        editingDate = dateStr;
        document.getElementById('edit-banner-date').textContent = formatTarikh(dateStr);
        document.getElementById('edit-banner').classList.add('active');
        document.getElementById('form-card-title').textContent = 'Kemaskini Log';
        submitBtn.textContent = '✏️ Kemaskini';
        document.getElementById('upload-section').style.display = 'none';
        document.getElementById('upload-disabled-note').style.display = 'block';
    }

    function exitEditMode(resetForm) {
        editingDate = null;
        document.getElementById('edit-banner').classList.remove('active');
        document.getElementById('form-card-title').textContent = 'Entri Log Baru';
        submitBtn.textContent = '💾 Simpan';
        document.getElementById('upload-section').style.display = '';
        document.getElementById('upload-disabled-note').style.display = 'none';
        if (resetForm) {
            logForm.reset();
            dateInput.value = '{{ date('Y-m-d') }}';
            document.getElementById('day-number').value = '{{ $currentDay }}';
            clearUploads();
            countChars(document.getElementById('activities'), 'act-count', 800);
        }
    }

    function checkExistingLog() {
        const selectedDate = dateInput.value;
        if (existingLogDates.includes(selectedDate)) {
            loadLogForEdit(selectedDate);
        } else if (editingDate) {
            // Tarikh baru tanpa log — kembali ke mod entri baru tetapi kekalkan isi ruangan.
            exitEditMode(false);
        }
    }

    function loadLogForEdit(dateStr) {
        fetch(`/logs/${dateStr}/edit`, {
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.log) {
                const log = data.log;
                document.getElementById('day-number').value = log.log_day || '';
                dateInput.value = log.log_date || dateStr;
                document.getElementById('log-location').value = log.log_location || '';
                document.getElementById('log-place').value = log.log_place || '';
                document.getElementById('activities').value = log.log_summary || '';
                document.getElementById('log-knowledge').value = log.log_knowledge || '';
                document.getElementById('log-tools').value = log.log_tools || '';
                document.getElementById('log-note').value = log.log_note || '';
                countChars(document.getElementById('activities'), 'act-count', 800);
                enterEditMode(dateStr);
            }
        })
        .catch(() => showToast('Gagal memuatkan log untuk diedit.', 'error'));
    }

    // ?edit=YYYY-MM-DD dari halaman lain — terus buka mod edit.
    document.addEventListener('DOMContentLoaded', function() {
        const editDate = new URLSearchParams(window.location.search).get('edit');
        if (editDate && existingLogDates.includes(editDate)) {
            loadLogForEdit(editDate);
            logForm.scrollIntoView({ behavior: 'smooth' });
        }
    });

    /* ===== Simpan / kemaskini — satu laluan sahaja ===== */

    logForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!document.getElementById('activities').value.trim()) {
            showToast('Sila masukkan apa yang anda buat hari ini.', 'error');
            return;
        }

        const formData = new FormData(logForm);
        let url = logForm.action;

        if (editingDate) {
            // PHP tidak memproses multipart pada PUT — guna method spoofing Laravel.
            formData.append('_method', 'PUT');
            url = `/logs/${editingDate}`;
        } else {
            uploadedFiles.forEach(f => formData.append('images[]', f));
        }

        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Menyimpan...';

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData
        })
        .then(res => res.ok ? res.json() : res.json().then(data => Promise.reject(data)))
        .then(data => {
            if (data.success || data.id) {
                showToast(editingDate ? 'Log berjaya dikemaskini!' : 'Log berjaya disimpan!', 'success');
                // Muat semula supaya statistik & senarai log terkini dikemas kini.
                setTimeout(() => { window.location.href = '{{ route('dashboard') }}'; }, 900);
            } else {
                throw data;
            }
        })
        .catch(err => {
            showToast(err.message || err.error || 'Gagal menyimpan log.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    function clearForm() {
        if (!confirm('Kosongkan semua ruangan?')) return;
        exitEditMode(true);
        document.getElementById('ai-panel').style.display = 'none';
    }

    /* ===== Kiraan aksara ===== */

    function countChars(el, counterId, max) {
        const counter = document.getElementById(counterId);
        const len = el.value.length;
        counter.textContent = `${len} / ${max} aksara`;
        counter.classList.toggle('warn', len > max * 0.85);
        if (len > max) el.value = el.value.slice(0, max);
    }

    /* ===== Muat naik foto ===== */

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            if (file.size > 5 * 1024 * 1024) { showToast(`${file.name} terlalu besar (maks 5MB).`, 'error'); return; }
            convertToJpeg(file).then(jpegFile => {
                if (uploadedFiles.length >= 10) { showToast('Maksimum 10 gambar sahaja.', 'error'); return; }
                uploadedFiles.push(jpegFile);
                const reader = new FileReader();
                reader.onload = e => addPreview(e.target.result, uploadedFiles.length - 1);
                reader.readAsDataURL(jpegFile);
                updateFileCount();
            });
        });
    }

    // Convert sebarang gambar (PNG/GIF/WebP) kepada JPEG dalam browser.
    // PDF generator hanya boleh embed JPEG tanpa PHP GD extension di server,
    // jadi kita pastikan semua upload sampai ke server sebagai JPEG.
    function convertToJpeg(file) {
        if (file.type === 'image/jpeg') return Promise.resolve(file);
        return new Promise(resolve => {
            const url = URL.createObjectURL(file);
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                const ctx = canvas.getContext('2d');
                // JPEG tak support transparency — isi background putih dulu
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);
                URL.revokeObjectURL(url);
                canvas.toBlob(blob => {
                    if (!blob) { resolve(file); return; }
                    const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                    resolve(new File([blob], name, { type: 'image/jpeg' }));
                }, 'image/jpeg', 0.9);
            };
            img.onerror = () => { URL.revokeObjectURL(url); resolve(file); };
            img.src = url;
        });
    }

    function addPreview(src, idx) {
        const grid = document.getElementById('preview-grid');
        const div = document.createElement('div');
        div.className = 'preview-thumb';
        div.innerHTML = `<img src="${src}" alt="Pratonton gambar ${idx + 1}"/><button type="button" class="remove-img" aria-label="Buang gambar ${idx + 1}" onclick="removeFile(${idx})">✕</button>`;
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

    function clearUploads() {
        uploadedFiles = [];
        document.getElementById('preview-grid').innerHTML = '';
        document.getElementById('file-count').textContent = '';
    }

    function updateFileCount() {
        const el = document.getElementById('file-count');
        el.textContent = uploadedFiles.length ? `📸 ${uploadedFiles.length} gambar ditambah` : '';
    }

    function dragOver(e) { e.preventDefault(); document.getElementById('upload-zone').classList.add('dragover'); }
    function dragLeave(e) { document.getElementById('upload-zone').classList.remove('dragover'); }
    function dropFiles(e) { e.preventDefault(); dragLeave(e); handleFiles(e.dataTransfer.files); }

    /* ===== Fungsi AI ===== */

    const jobScope = @json($defaultSettings->default_job_scope ?? '');

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
            document.getElementById('aiPromptModal').classList.add('active');
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
        aiOutput.style.color = '';
        aiOutput.textContent = 'Menghantar log anda ke AI...';
        aiProgress.style.width = '20%';

        const emptyFields = [];
        if (!summary) emptyFields.push('log_summary');
        if (!location) emptyFields.push('log_location');
        if (!place) emptyFields.push('log_place');
        if (!knowledge) emptyFields.push('log_knowledge');
        if (!note) emptyFields.push('log_note');

        const primaryRef = summary || (location + ' ' + place);

        const fillField = (fieldName, value) => {
            return fetch('/ai/generate-field', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    field: fieldName,
                    log_day: day,
                    log_location: location || 'Jabatan',
                    log_place: place || '',
                    job_scope: jobScope,
                    log_summary: value || summary || primaryRef,
                    log_knowledge: knowledge || '',
                    log_tools: tools || '',
                    log_note: note || '',
                    reference: primaryRef,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.content) applyFieldContent(fieldName, data.content);
            })
            .catch(() => {});
        };

        const finish = () => {
            aiProgress.style.width = '100%';
            aiOutput.textContent = '✅ Log berjaya disediakan!';
            btn.textContent = '✅ Selesai!';
            setTimeout(() => {
                btn.disabled = false;
                btn.textContent = '🤖 Siapkan guna AI';
            }, 2000);
        };

        if (emptyFields.length > 0) {
            aiOutput.textContent = `Mengisi ${emptyFields.length} ruangan kosong...`;
            (async () => {
                let fillCount = 0;
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
                finish();
            })();
        } else {
            aiOutput.textContent = 'Memperbaiki semua ruangan...';
            aiProgress.style.width = '50%';

            fetch('/ai/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
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
                    applyFieldContent('log_summary', data.content);
                    finish();
                } else {
                    throw new Error(data.error || 'Penjanaan gagal');
                }
            })
            .catch(err => {
                aiOutput.textContent = 'Ralat: ' + err.message;
                aiOutput.style.color = 'var(--red)';
                btn.disabled = false;
                btn.textContent = '🤖 Cuba Lagi';
            });
        }
    }

    function applyFieldContent(fieldName, content) {
        const idMap = {
            'log_summary': 'activities',
            'log_knowledge': 'log-knowledge',
            'log_tools': 'log-tools',
            'log_note': 'log-note',
            'log_location': 'log-location',
            'log_place': 'log-place',
        };
        const input = document.getElementById(idMap[fieldName] || fieldName);
        if (input) {
            input.value = content;
            if (fieldName === 'log_summary') countChars(input, 'act-count', 800);
        }
    }

    function closeAiPromptModal() {
        document.getElementById('aiPromptModal').classList.remove('active');
        document.getElementById('aiPromptInput').value = '';
    }

    function submitAiPrompt() {
        const userInput = document.getElementById('aiPromptInput').value.trim();
        if (!userInput) {
            showToast('Sila jelaskan apa yang anda buat hari ini.', 'error');
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
        aiOutput.style.color = '';
        aiOutput.textContent = 'Menghantar ke AI...';
        aiProgress.style.width = '20%';

        const day = document.getElementById('day-number').value;
        const place = document.getElementById('log-place').value.trim() || '';

        const requestField = (fieldName, logLocation) => {
            return fetch('/ai/generate-field', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    field: fieldName,
                    log_day: day,
                    log_location: logLocation,
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
                if (data.success && data.content) applyFieldContent(fieldName, data.content);
            })
            .catch(() => {});
        };

        (async () => {
            await requestField('log_location', '');
            aiProgress.style.width = '30%';

            const fields = ['log_summary', 'log_knowledge', 'log_tools', 'log_note'];
            const location = document.getElementById('log-location').value.trim() || 'Jabatan';
            let done = 0;
            for (const fieldName of fields) {
                aiOutput.textContent = `Mengisi ${fieldName.replace('log_', '')}...`;
                await requestField(fieldName, location);
                done++;
                aiProgress.style.width = `${30 + (done / fields.length) * 60}%`;
            }

            aiProgress.style.width = '100%';
            aiOutput.textContent = '✅ Log berjaya dijana!';
            btn.textContent = '✅ Selesai!';
            setTimeout(() => {
                btn.disabled = false;
                btn.textContent = '🤖 Siapkan guna AI';
            }, 2000);
        })();
    }

    function generateField(fieldName, btnElement) {
        btnElement.disabled = true;
        btnElement.classList.add('loading');

        fetch('/ai/generate-field', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                field: fieldName,
                log_day: document.getElementById('day-number').value,
                log_location: document.getElementById('log-location').value.trim(),
                log_place: document.getElementById('log-place').value.trim(),
                job_scope: jobScope,
                log_summary: document.getElementById('activities').value.trim(),
                log_knowledge: document.getElementById('log-knowledge').value.trim(),
                log_tools: document.getElementById('log-tools').value.trim(),
                log_note: document.getElementById('log-note').value.trim(),
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.content) {
                applyFieldContent(fieldName, data.content);
                btnElement.textContent = '✅ Selesai';
                setTimeout(() => {
                    btnElement.disabled = false;
                    btnElement.classList.remove('loading');
                    btnElement.textContent = '✨ Guna AI';
                }, 2000);
            } else {
                throw new Error(data.error || 'Penjanaan gagal');
            }
        })
        .catch(err => {
            showToast('Ralat: ' + err.message, 'error');
            btnElement.disabled = false;
            btnElement.classList.remove('loading');
            btnElement.textContent = '✨ Guna AI';
        });
    }

    /* ===== Modal tetapan lalai (onboarding) ===== */

    @if(!$defaultSettings || !$defaultSettings->default_internship_period)
        window.addEventListener('load', function() {
            document.getElementById('defaultsModal').classList.add('active');
        });
    @endif

    function closeDefaultsModal() {
        document.getElementById('defaultsModal').classList.remove('active');
    }

    function handleDefaultsSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Menyimpan...';

        const formData = new FormData(form);

        fetch('{{ route('profile.defaults') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeDefaultsModal();
                location.reload();
            } else {
                showToast(data.message || 'Ralat berlaku', 'error');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        })
        .catch(err => {
            showToast('Ralat: ' + err.message, 'error');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    }

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAiPromptModal();
            closeDefaultsModal();
        }
    });
</script>
@endpush
