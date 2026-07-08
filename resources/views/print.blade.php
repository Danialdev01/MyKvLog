@extends('layouts.shell')

@section('title', 'MyKvLog — Cetak')
@section('breadcrumb', 'Cetak')

@push('styles')
<style>
    .print-option-label { font-size: 0.8rem; font-weight: 600; color: var(--gray-600); margin-bottom: 10px; display: block; }
    .print-radio-group { display: flex; gap: 12px; flex-wrap: wrap; }
    .print-radio-item {
        display: flex; align-items: center; gap: 10px; cursor: pointer;
        padding: 12px 16px; border: 1.5px solid var(--gray-200); border-radius: 12px;
        flex: 1; min-width: 200px; transition: border-color 0.15s, background 0.15s;
    }
    .print-radio-item:hover { border-color: var(--gray-300); }
    .print-radio-item:has(input:checked) { border-color: var(--orange); background: rgba(255,107,53,0.05); }
    .print-radio-item input[type="radio"] { accent-color: var(--orange); width: 18px; height: 18px; flex-shrink: 0; }
    .print-radio-item span { font-size: 0.88rem; font-weight: 600; color: var(--gray-700); }

    .date-range-inputs { display: none; gap: 16px; align-items: flex-end; flex-wrap: wrap; margin-top: 18px; }
    .date-range-inputs.visible { display: flex; }
    .date-input-wrapper { display: flex; flex-direction: column; gap: 6px; }

    /* Search */
    .search-bar { display: flex; gap: 12px; align-items: center; }
    .search-bar .input-base { flex: 1; }

    /* Table */
    .table-scroll { overflow-x: auto; }
    .logs-table { width: 100%; border-collapse: collapse; min-width: 560px; }
    .logs-table th {
        text-align: left; padding: 12px 16px; font-size: 0.7rem; font-weight: 700;
        color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em;
        background: var(--gray-50); border-bottom: 1px solid var(--gray-100); white-space: nowrap;
    }
    .logs-table td { padding: 14px 16px; border-bottom: 1px solid var(--gray-100); font-size: 0.85rem; vertical-align: middle; }
    .logs-table tr:last-child td { border-bottom: none; }
    .logs-table tbody tr:hover td { background: rgba(255,107,53,0.03); }
    .log-summary-cell { font-weight: 600; color: var(--gray-800); }
    .log-date-cell { color: var(--gray-500); font-size: 0.8rem; white-space: nowrap; }
    .log-status-pill {
        font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 999px;
        background: rgba(255,107,53,0.1); color: var(--orange-deep);
        border: 1px solid rgba(255,107,53,0.2); white-space: nowrap;
    }
    .print-page-btn {
        padding: 6px 12px; border-radius: 8px; background: rgba(255,107,53,0.08);
        border: 1px solid rgba(255,107,53,0.2); color: var(--orange-deep);
        font-size: 0.75rem; font-weight: 700; cursor: pointer; text-decoration: none;
        white-space: nowrap;
    }
    .print-page-btn:hover { background: rgba(255,107,53,0.15); }

    .pagination { display: flex; justify-content: center; align-items: center; gap: 10px; padding: 20px; }
    .pagination-btn {
        padding: 8px 14px; border-radius: 10px; border: 1.5px solid var(--gray-200);
        background: var(--white); color: var(--gray-600); font-size: 0.82rem;
        font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .pagination-btn:hover:not(:disabled) { border-color: var(--orange); color: var(--orange-deep); }
    .pagination-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .pagination-info { font-size: 0.82rem; font-weight: 600; color: var(--gray-500); }

    .no-results { text-align: center; padding: 32px; color: var(--gray-400); font-size: 0.88rem; }
</style>
@endpush

@section('content')
    <h1 class="page-title">🖨️ Cetak Log</h1>
    <p class="page-subtitle">Sedia untuk mencetak log latihan industri anda</p>

    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    {{-- Pilihan cetakan --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Pilihan Cetakan</span>
        </div>
        <div class="card-body">
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

            <div id="dateRangeInputs" class="date-range-inputs">
                <div class="date-input-wrapper">
                    <label class="field-label" for="start_date">Dari</label>
                    <input type="date" id="start_date" name="start_date" class="input-base">
                </div>
                <div class="date-input-wrapper">
                    <label class="field-label" for="end_date">Hingga</label>
                    <input type="date" id="end_date" name="end_date" class="input-base">
                </div>
            </div>

            <div class="btn-group" style="margin-top:20px;">
                {{-- target="_blank": PDF dipapar sebagai preview dalam tab baru; user boleh muat turun dari toolbar PDF. --}}
                <form method="POST" action="{{ route('logs.pdf') }}" id="pdfForm" target="_blank">
                    @csrf
                    <input type="hidden" name="print_type" id="hidden_print_type" value="all">
                    <input type="hidden" name="start_date" id="hidden_start_date" value="">
                    <input type="hidden" name="end_date" id="hidden_end_date" value="">
                    <button type="submit" class="btn-orange" onclick="preparePdfForm(event)">🖨️ Preview &amp; Muat Turun PDF</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Carian --}}
    <div class="card">
        <div class="card-body">
            <div class="search-bar">
                <input type="text" id="searchInput" class="input-base" placeholder="Cari log mengikut ringkasan atau tarikh…" oninput="filterLogs()" aria-label="Cari log">
            </div>
        </div>
    </div>

    {{-- Senarai log --}}
    <div class="card">
        <div class="card-header" style="justify-content:space-between;">
            <span class="card-title">Senarai Log</span>
            <span class="log-count-badge">{{ $logs->count() }} entri</span>
        </div>

        @if($logs->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon" aria-hidden="true">📋</div>
                <div class="empty-state-text">Tiada log ditemui</div>
                <div class="empty-state-sub">Mulakan dengan membuat log baru di Papan Pemuka</div>
            </div>
        @else
            <div class="table-scroll">
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
                            <tr class="log-row"
                                data-summary="{{ strtolower($log->log_summary) }}"
                                data-date="{{ $log->log_date->format('d/m/Y') }}">
                                <td><div class="log-day-badge">{{ $log->log_day }}</div></td>
                                <td class="log-summary-cell">{{ $log->log_summary }}</td>
                                <td class="log-date-cell">{{ $log->log_date->format('d/m/Y') }}</td>
                                <td><span class="log-status-pill">{{ $log->log_status }}</span></td>
                                <td><a href="{{ route('logs.pdf.single', $log->log_id) }}" class="print-page-btn" target="_blank">🖨️ Cetak</a></td>
                            </tr>
                        @endforeach
                        <tr id="noResultsRow" style="display:none;">
                            <td colspan="5" class="no-results">Tiada log sepadan dengan carian anda.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination" id="paginationContainer">
                <button type="button" class="pagination-btn" id="prevPageBtn" onclick="changePage(-1)" disabled>← Sebelum</button>
                <span class="pagination-info" id="paginationInfo">Halaman 1 / 1</span>
                <button type="button" class="pagination-btn" id="nextPageBtn" onclick="changePage(1)">Seterusnya →</button>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function toggleDateRange() {
        const printType = document.querySelector('input[name="print_type"]:checked').value;
        document.getElementById('dateRangeInputs').classList.toggle('visible', printType === 'range');
    }

    function preparePdfForm(event) {
        const printType = document.querySelector('input[name="print_type"]:checked').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (printType === 'range' && (!startDate || !endDate)) {
            event.preventDefault();
            showToast('Sila isi tarikh mula dan tarikh akhir terlebih dahulu.', 'error');
            return;
        }

        document.getElementById('hidden_print_type').value = printType;
        document.getElementById('hidden_start_date').value = startDate;
        document.getElementById('hidden_end_date').value = endDate;
    }

    /* ===== Carian + pagination sebenar ===== */
    const LOGS_PER_PAGE = 10;
    const allRows = Array.from(document.querySelectorAll('.log-row'));
    let filteredRows = allRows.slice();
    let currentPage = 1;

    function filterLogs() {
        const term = (document.getElementById('searchInput').value || '').toLowerCase().trim();
        filteredRows = allRows.filter(row => {
            const summary = row.getAttribute('data-summary') || '';
            const date = row.getAttribute('data-date') || '';
            return summary.includes(term) || date.includes(term);
        });
        currentPage = 1;          // carian baru → kembali ke halaman 1
        renderPage();
    }

    function totalPages() {
        return Math.max(1, Math.ceil(filteredRows.length / LOGS_PER_PAGE));
    }

    function renderPage() {
        const pages = totalPages();
        if (currentPage > pages) currentPage = pages;

        // Sembunyi semua, kemudian tunjuk hanya baris untuk halaman semasa
        allRows.forEach(r => { r.style.display = 'none'; });
        const start = (currentPage - 1) * LOGS_PER_PAGE;
        filteredRows.slice(start, start + LOGS_PER_PAGE).forEach(r => { r.style.display = ''; });

        const noResults = document.getElementById('noResultsRow');
        if (noResults) noResults.style.display = filteredRows.length === 0 ? '' : 'none';

        const pagination = document.getElementById('paginationContainer');
        if (pagination) pagination.style.display = filteredRows.length === 0 ? 'none' : 'flex';

        document.getElementById('paginationInfo').textContent = `Halaman ${currentPage} / ${pages}`;
        document.getElementById('prevPageBtn').disabled = currentPage <= 1;
        document.getElementById('nextPageBtn').disabled = currentPage >= pages;
    }

    function changePage(direction) {
        const pages = totalPages();
        currentPage = Math.min(pages, Math.max(1, currentPage + direction));
        renderPage();
    }

    // Papar halaman pertama (kalau ada log)
    if (allRows.length) renderPage();
</script>
@endpush
