@extends('layouts.shell')

@section('title', 'MyKvLog — Log Saya')
@section('breadcrumb', 'Log Saya')

@push('styles')
<style>
    .cal-container {
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        overflow: hidden;
    }
    .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    .cal-day-name {
        text-align: center;
        font-size: 0.7rem; font-weight: 700;
        color: var(--gray-500);
        text-transform: uppercase; letter-spacing: 0.05em;
        padding: 12px 4px;
        background: var(--gray-50);
        border-right: 1px solid var(--gray-200);
        border-bottom: 1.5px solid var(--gray-200);
    }
    .cal-day-name:last-child { border-right: none; }

    .cal-day {
        min-height: 72px;
        border-right: 1px solid var(--gray-200);
        border-bottom: 1px solid var(--gray-200);
        display: flex; flex-direction: column;
        align-items: flex-start; justify-content: flex-start;
        padding: 8px;
        background: var(--card);
        position: relative;
        font-family: inherit;
        font-size: 0.85rem;
        border-left: none; border-top: none;
        width: 100%;
        text-align: left;
    }
    .cal-day:nth-child(7n) { border-right: none; }
    .cal-day.empty { background: var(--gray-50); }
    .cal-day.has-log { background: rgba(255,107,53,0.06); cursor: pointer; }
    .cal-day.has-log:hover { background: rgba(255,107,53,0.14); }
    .cal-day .day-num { line-height: 1; font-size: 0.85rem; font-weight: 600; color: var(--gray-700); }
    .cal-day.today .day-num {
        background: var(--orange); color: #fff;
        width: 26px; height: 26px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
    }
    .cal-day.has-log::after {
        content: '';
        position: absolute; bottom: 6px; right: 6px;
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--orange);
    }

    .cal-card-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px;
    }
    .cal-nav-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        border: 1.5px solid var(--gray-200);
        background: var(--card);
        color: var(--gray-600);
        font-size: 1rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: border-color 0.15s, color 0.15s;
        text-decoration: none;
    }
    .cal-nav-btn:hover { border-color: var(--orange); color: var(--orange-deep); }
    .cal-month-year { font-size: 1.1rem; font-weight: 800; color: var(--gray-800); }

    .cal-legend {
        display: flex; gap: 20px;
        margin-top: 12px; padding: 10px 16px;
        background: var(--gray-50);
        border-radius: 8px;
        justify-content: center;
    }
    .cal-legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.72rem; color: var(--gray-500); }
    .cal-legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .cal-legend-dot.log { background: rgba(255,107,53,0.45); }
    .cal-legend-dot.today { background: var(--orange); }

    /* Modal butiran log */
    .log-modal {
        background: #fff; border-radius: 20px;
        width: 100%; max-width: 600px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .log-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--gray-200);
        position: sticky; top: 0; background: #fff;
        border-radius: 20px 20px 0 0;
    }
    .log-modal-title { font-size: 1.1rem; font-weight: 800; color: var(--gray-800); }
    .log-modal-close {
        width: 32px; height: 32px; border-radius: 8px;
        border: 1px solid var(--gray-200); background: #fff;
        cursor: pointer; font-size: 1rem;
        display: flex; align-items: center; justify-content: center;
    }
    .log-modal-close:hover { background: var(--gray-50); }
    .log-modal-body { padding: 24px; }
    .log-modal-section { margin-bottom: 20px; }
    .log-modal-section:last-child { margin-bottom: 0; }
    .log-modal-label {
        font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--gray-500); margin-bottom: 6px;
    }
    .log-modal-value {
        font-size: 0.95rem; color: var(--gray-800); line-height: 1.6;
        background: var(--gray-50);
        padding: 12px 16px; border-radius: 10px;
        white-space: pre-wrap;
    }
    .log-modal-actions {
        display: flex; gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid var(--gray-200);
        position: sticky; bottom: 0; background: #fff;
        border-radius: 0 0 20px 20px;
    }

    @media (max-width: 768px) {
        .cal-day { min-height: 52px; padding: 6px; }
        .cal-day .day-num { font-size: 0.75rem; }
        .cal-day.today .day-num { width: 22px; height: 22px; }
        .cal-day-name { font-size: 0.58rem; padding: 8px 2px; }
        .cal-legend { gap: 12px; padding: 8px 12px; }
        .cal-legend-item { font-size: 0.65rem; }
    }
</style>
@endpush

@section('content')
    @php
    $bulanMelayu = [1=>'Januari',2=>'Februari',3=>'Mac',4=>'April',5=>'Mei',6=>'Jun',7=>'Julai',8=>'Ogos',9=>'September',10=>'Oktober',11=>'November',12=>'Disember'];
    $currentMonth = (int) ($selectedMonth ?? date('m'));
    $currentYear = (int) ($selectedYear ?? date('Y'));
    $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
    $daysInMonth = (int) date('t', $firstDayOfMonth);
    $firstDayWeek = (int) date('w', $firstDayOfMonth);
    $monthName = $bulanMelayu[$currentMonth];
    $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
    $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
    $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
    $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
    $todayDate = date('Y-m-d');
    @endphp

    <h1 class="page-title">📋 Log Saya</h1>
    <p class="page-subtitle">Jejak perjalanan latihan industri anda — {{ $logs->count() }} entri keseluruhan</p>

    <div class="card" style="padding:20px 24px;">
        <div class="cal-card-header">
            <a class="cal-nav-btn" href="?month={{ $prevMonth }}&year={{ $prevYear }}" aria-label="Bulan sebelumnya">←</a>
            <span class="cal-month-year">{{ $monthName }} {{ $currentYear }}</span>
            <a class="cal-nav-btn" href="?month={{ $nextMonth }}&year={{ $nextYear }}" aria-label="Bulan berikutnya">→</a>
        </div>

        <div class="cal-container">
            <div class="cal-grid">
                @foreach(['Ahd','Isn','Sel','Rab','Kha','Jum','Sab'] as $day)
                    <div class="cal-day-name">{{ $day }}</div>
                @endforeach
            </div>

            <div class="cal-grid">
                @for ($i = 0; $i < $firstDayWeek; $i++)
                    <div class="cal-day empty"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                    $dateStr = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $hasLog = isset($logDates) && in_array($dateStr, $logDates);
                    $isToday = $dateStr === $todayDate;
                    $logEntry = $logsByDate[$dateStr] ?? null;
                    @endphp
                    <div class="cal-day{{ $hasLog ? ' has-log' : '' }}{{ $isToday ? ' today' : '' }}"
                        @if($hasLog && $logEntry)
                        role="button" tabindex="0"
                        onclick="openLogModal('{{ $logEntry->log_id }}', '{{ $dateStr }}')"
                        onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openLogModal('{{ $logEntry->log_id }}', '{{ $dateStr }}');}"
                        aria-label="Lihat log {{ $day }} {{ $monthName }}"
                        @endif
                        title="{{ $hasLog ? 'Entri log wujud — klik untuk lihat' : 'Tiada entri' }}">
                        <span class="day-num">{{ $day }}</span>
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

    {{-- Senarai log: cara pantas menyemak imbas tanpa cari dalam kalendar --}}
    <div class="card">
        <div class="card-header" style="justify-content:space-between;">
            <span class="card-title">Senarai Log</span>
            <span class="log-count-badge">{{ $logs->count() }} entri</span>
        </div>

        @if($logs->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon" aria-hidden="true">📋</div>
                <div class="empty-state-text">Belum ada log</div>
                <div class="empty-state-sub">Mulakan dengan membuat entri pertama di Papan Pemuka</div>
            </div>
        @else
            @foreach($logs as $log)
                <button type="button" class="log-entry"
                        onclick="openLogModal('{{ $log->log_id }}', '{{ $log->log_date->format('Y-m-d') }}')">
                    <div class="log-day-badge">D{{ $log->log_day }}</div>
                    <div class="log-entry-info">
                        <div class="log-entry-summary">{{ Str::limit($log->log_summary, 90) }}</div>
                        <div class="log-entry-meta">{{ $log->log_date->format('d/m/Y') }}</div>
                    </div>
                    <span style="color:var(--gray-400);font-size:0.8rem;" aria-hidden="true">→</span>
                </button>
            @endforeach
        @endif
    </div>

    {{-- Modal butiran log --}}
    <div class="modal-overlay" id="logModalOverlay" onclick="closeLogModal(event)" role="dialog" aria-modal="true" aria-labelledby="logModalTitle">
        <div class="log-modal">
            <div class="log-modal-header">
                <span class="log-modal-title" id="logModalTitle">Log</span>
                <button class="log-modal-close" onclick="closeLogModal()" aria-label="Tutup">✕</button>
            </div>
            <div class="log-modal-body" id="logModalBody"></div>
            <div class="log-modal-actions">
                <button class="btn-orange" style="flex:1;" onclick="printLogEntry()">🖨️ Cetak</button>
                <button class="btn-outline" style="flex:1;" onclick="editLogEntry()">✏️ Edit</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentLogDate = null;

    function openLogModal(logId, dateStr) {
        currentLogDate = dateStr;
        const [y, m, d] = dateStr.split('-');
        document.getElementById('logModalTitle').textContent = `Log ${d}/${m}/${y}`;
        document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:var(--gray-400);">⏳ Memuatkan...</div>';
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
                const esc = s => { const div = document.createElement('div'); div.textContent = s || '-'; return div.innerHTML; };
                document.getElementById('logModalBody').innerHTML = `
                    <div class="log-modal-section">
                        <div class="log-modal-label">Hari Latihan</div>
                        <div class="log-modal-value">Hari ${esc(String(log.log_day))}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Jabatan</div>
                        <div class="log-modal-value">${esc(log.log_location)}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Lokasi</div>
                        <div class="log-modal-value">${esc(log.log_place)}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Aktiviti Hari Ini</div>
                        <div class="log-modal-value">${esc(log.log_summary)}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Pengetahuan &amp; Pembelajaran</div>
                        <div class="log-modal-value">${esc(log.log_knowledge)}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Alat Digunakan</div>
                        <div class="log-modal-value">${esc(log.log_tools)}</div>
                    </div>
                    <div class="log-modal-section">
                        <div class="log-modal-label">Nota Tambahan</div>
                        <div class="log-modal-value">${esc(log.log_note)}</div>
                    </div>
                `;
            } else {
                document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:var(--red);">Ralat memuatkan data log.</div>';
            }
        })
        .catch(() => {
            document.getElementById('logModalBody').innerHTML = '<div style="text-align:center;padding:40px;color:var(--red);">Ralat memuatkan data log.</div>';
        });
    }

    function closeLogModal(e) {
        if (!e || e.target === document.getElementById('logModalOverlay')) {
            document.getElementById('logModalOverlay').classList.remove('active');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') document.getElementById('logModalOverlay').classList.remove('active');
    });

    function printLogEntry() {
        if (currentLogDate) window.location.href = `/logs/print/${currentLogDate}`;
    }

    function editLogEntry() {
        if (currentLogDate) window.location.href = `/dashboard?edit=${currentLogDate}`;
    }
</script>
@endpush
