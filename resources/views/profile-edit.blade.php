@extends('layouts.shell')

@section('title', 'MyKvLog — Profil')
@section('breadcrumb', 'Profil')

@php
    $defaultSettings = Auth::user()->defaults;
@endphp

@push('styles')
<style>
    .profile-card-head {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 20px; padding-bottom: 16px;
        border-bottom: 1px solid var(--gray-100);
    }
    .profile-card-icon {
        width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
        background: rgba(255,107,53,0.1); color: var(--orange-deep);
        font-size: 1.4rem; display: flex; align-items: center; justify-content: center;
    }
    .profile-card-title { font-size: 1.05rem; font-weight: 800; color: var(--gray-800); }
    .profile-card-subtitle { font-size: 0.78rem; color: var(--gray-500); margin-top: 2px; }
    .form-group { margin-bottom: 16px; }
    .inline-error {
        display: none; background: #FEE2E2; border: 1px solid #FCA5A5; border-radius: 10px;
        padding: 10px 14px; margin-bottom: 16px; color: #DC2626; font-size: 0.82rem; font-weight: 600;
    }
    .inline-error.show { display: block; }
</style>
@endpush

@section('content')
    <h1 class="page-title">👤 Profil</h1>
    <p class="page-subtitle">Ubah kata laluan dan tetapan lalai anda</p>

    {{-- Ubah kata laluan --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-card-head">
                <div class="profile-card-icon" aria-hidden="true">🔒</div>
                <div>
                    <div class="profile-card-title">Ubah Kata Laluan</div>
                    <div class="profile-card-subtitle">Pastikan akaun anda menggunakan kata laluan yang panjang dan rawak</div>
                </div>
            </div>

            <form id="passwordForm" method="POST" action="/password">
                @csrf
                @method('PUT')
                <div id="passwordError" class="inline-error"></div>

                <div class="form-group">
                    <label class="field-label" for="current_password">Kata Laluan Semasa</label>
                    <input type="password" id="current_password" name="current_password" class="input-base" autocomplete="current-password" placeholder="Masukkan kata laluan semasa">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="field-label" for="password">Kata Laluan Baru</label>
                        <input type="password" id="password" name="password" class="input-base" autocomplete="new-password" placeholder="Masukkan kata laluan baru">
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="password_confirmation">Sahkan Kata Laluan</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-base" autocomplete="new-password" placeholder="Sahkan kata laluan baru">
                    </div>
                </div>

                <div class="btn-group" style="margin-top:6px;">
                    <button type="submit" class="btn-orange">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tetapan lalai --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-card-head">
                <div class="profile-card-icon" aria-hidden="true">⚙️</div>
                <div>
                    <div class="profile-card-title">Tetapan Lalai</div>
                    <div class="profile-card-subtitle">Nilai ini akan diisi secara automatik untuk setiap entri log baru</div>
                </div>
            </div>

            <form id="defaultsForm" method="POST" action="{{ route('profile.defaults') }}">
                @csrf
                <div id="defaultsError" class="inline-error"></div>

                <div class="form-group">
                    <label class="field-label" for="default_department">Jabatan / Unit / Bahagian</label>
                    <input type="text" id="default_department" name="default_department" class="input-base" placeholder="Contoh: IT, Kewangan, HR" value="{{ $defaultSettings->default_department ?? '' }}">
                </div>

                <div class="form-group">
                    <label class="field-label" for="default_location">Lokasi / Tempat</label>
                    <input type="text" id="default_location" name="default_location" class="input-base" placeholder="Contoh: Aras 2, Blok A, Kuala Lumpur" value="{{ $defaultSettings->default_location ?? '' }}">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="field-label" for="default_internship_period">Tempoh Latihan (Hari)</label>
                        <input type="number" id="default_internship_period" name="default_internship_period" class="input-base" min="1" placeholder="Contoh: 90" value="{{ $defaultSettings->default_internship_period ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="default_company">Syarikat</label>
                        <input type="text" id="default_company" name="default_company" class="input-base" placeholder="Nama syarikat latihan" value="{{ $defaultSettings->default_company ?? '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="field-label" for="default_job_scope">Skop Kerja</label>
                    <input type="text" id="default_job_scope" name="default_job_scope" class="input-base" placeholder="Skop kerja semasa latihan" value="{{ $defaultSettings->default_job_scope ?? '' }}">
                </div>

                <div class="btn-group" style="margin-top:6px;">
                    <button type="submit" class="btn-orange">💾 Simpan Tetapan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function showInlineError(el, message) {
        el.textContent = message;
        el.classList.add('show');
    }
    function clearInlineError(el) {
        el.textContent = '';
        el.classList.remove('show');
    }

    document.getElementById('passwordForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = this;
        const errorEl = document.getElementById('passwordError');
        clearInlineError(errorEl);
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: formData
            });

            if (response.ok || response.status === 302) {
                form.reset();
                showToast('Kata laluan berjaya dikemas kini!', 'success');
                return;
            }

            const result = await response.json().catch(() => ({}));
            let msg = 'Ralat: kata laluan tidak dapat dikemas kini.';
            if (result.errors && result.errors.current_password) {
                msg = result.errors.current_password[0];
            } else if (result.errors && result.errors.password) {
                msg = result.errors.password[0];
            } else if (result.message) {
                msg = result.message;
            }
            showInlineError(errorEl, msg);
        } catch (error) {
            showInlineError(errorEl, 'Berlaku ralat. Sila cuba lagi.');
        }
    });

    document.getElementById('defaultsForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = this;
        const errorEl = document.getElementById('defaultsError');
        clearInlineError(errorEl);
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: formData
            });

            const result = await response.json().catch(() => ({}));

            if (response.ok && result.success) {
                showToast('Tetapan lalai berjaya disimpan!', 'success');
                return;
            }

            let msg = 'Ralat semasa menyimpan tetapan.';
            if (result.errors) {
                const first = Object.values(result.errors)[0];
                msg = Array.isArray(first) ? first[0] : first;
            }
            showInlineError(errorEl, msg);
        } catch (error) {
            showInlineError(errorEl, 'Berlaku ralat. Sila cuba lagi.');
        }
    });
</script>
@endpush
