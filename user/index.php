<?php
$isnew = false;
$today      = date('d/m/Y');
$day_num    = date('j');
$month_year = date('F Y');
$year       = date('Y');

// Mock recent logs
$recent_logs = [
  ['day' => 42, 'date' => '01/05/2025', 'summary' => 'Server rack installation & cable labelling', 'status' => 'done'],
  ['day' => 41, 'date' => '30/04/2025', 'summary' => 'Network troubleshooting & IT briefing', 'status' => 'done'],
  ['day' => 40, 'date' => '29/04/2025', 'summary' => 'CCTV system maintenance & report writing', 'status' => 'done'],
];

// Default values for new users
$default_internship_period = 100;
$default_department = '';
$default_company = '';
$default_job_scope = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MyInternLog — Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --lime:   #C8F135;
      --navy:   #0D1117;
      --navy2:  #131920;
      --card:   #192130;
      --card2:  #1E2A3A;
      --border: rgba(200,241,53,0.12);
      --accent: #FF6B35;
      --sky:    #38BDF8;
      --muted:  #4B6080;
      --text:   #E2EAF4;
      --subtle: #94A3B8;
      --sidebar-w: 260px;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background: var(--navy);
      color: var(--text);
      overflow-x: hidden;
    }
    .syne { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }

    /* ── NOISE ── */
    body::before {
      content: '';
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
    }

    /* ══════════════ SIDEBAR ══════════════ */
    #sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: var(--sidebar-w);
      background: var(--navy2);
      border-right: 1px solid var(--border);
      display: flex; flex-direction: column;
      z-index: 100;
      transition: transform 0.3s cubic-bezier(.4,0,.2,1);
    }
    #sidebar.collapsed { transform: translateX(calc(-1 * var(--sidebar-w))); }

    .sidebar-logo {
      padding: 22px 20px 18px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: 10px;
    }
    .sidebar-logo .logo-icon { font-size: 1.6rem; }
    .sidebar-logo .logo-text { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 1.15rem; font-weight: 800; }
    .sidebar-logo .logo-text span { color: var(--lime); }

    .nav-section-label {
      font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--muted);
      padding: 18px 20px 6px;
    }

    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 20px; margin: 2px 10px;
      border-radius: 12px;
      cursor: pointer;
      text-decoration: none; color: var(--subtle);
      font-weight: 600; font-size: 0.9rem;
      transition: background 0.15s, color 0.15s;
      position: relative;
    }
    .nav-item:hover  { background: rgba(200,241,53,0.07); color: var(--text); }
    .nav-item.active {
      background: rgba(200,241,53,0.13);
      color: var(--lime);
      border: 1px solid rgba(200,241,53,0.18);
    }
    .nav-item .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; }
    .nav-item .nav-badge {
      margin-left: auto;
      background: var(--lime); color: var(--navy);
      font-size: 0.6rem; font-weight: 800;
      padding: 2px 7px; border-radius: 999px;
    }

    /* Sidebar bottom profile */
    .sidebar-profile {
      margin-top: auto;
      padding: 16px 20px;
      border-top: 1px solid var(--border);
      display: flex; align-items: center; gap: 12px;
    }
    .avatar {
      width: 36px; height: 36px; border-radius: 10px;
      background: rgba(200,241,53,0.15);
      color: var(--lime); font-weight: 800;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.9rem; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      flex-shrink: 0;
    }
    .avatar-info .name { font-size: 0.82rem; font-weight: 700; color: var(--text); }
    .avatar-info .role { font-size: 0.68rem; color: var(--muted); }

    /* ══════════════ MAIN LAYOUT ══════════════ */
    #main {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
      position: relative; z-index: 1;
    }
    #main.expanded { margin-left: 0; }

    /* ── TOPBAR ── */
    #topbar {
      position: sticky; top: 0; z-index: 50;
      background: rgba(13,17,23,0.82);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      padding: 14px 28px;
      display: flex; align-items: center; justify-content: space-between;
    }

    #sidebar-toggle {
      width: 36px; height: 36px; border-radius: 10px;
      background: rgba(200,241,53,0.08); border: 1px solid var(--border);
      cursor: pointer; display: flex; flex-direction: column;
      align-items: center; justify-content: center; gap: 4px;
      transition: background 0.15s;
    }
    #sidebar-toggle:hover { background: rgba(200,241,53,0.15); }
    #sidebar-toggle span {
      display: block; width: 16px; height: 2px;
      background: var(--lime); border-radius: 2px;
      transition: transform 0.25s, opacity 0.25s;
    }

    .topbar-breadcrumb { font-size: 0.8rem; color: var(--muted); margin-left: 14px; }
    .topbar-breadcrumb strong { color: var(--text); font-weight: 700; }

    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .topbar-date-pill {
      padding: 5px 14px; border-radius: 999px;
      background: rgba(200,241,53,0.08); border: 1px solid var(--border);
      font-size: 0.75rem; color: var(--lime); font-weight: 700;
    }

    /* ── PAGE CONTENT ── */
    .page-content { padding: 28px 28px 60px; }

    /* ══════════════ STAT CARDS ══════════════ */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap: 14px; margin-bottom: 28px; }

    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 16px; padding: 18px 20px;
      position: relative; overflow: hidden;
      transition: transform 0.18s, border-color 0.18s;
    }
    .stat-card:hover { transform: translateY(-3px); border-color: rgba(200,241,53,0.3); }
    .stat-card .stat-label { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; }
    .stat-card .stat-value { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-card .stat-sub { font-size: 0.7rem; color: var(--muted); margin-top: 4px; }
    .stat-card .stat-glow {
      position: absolute; bottom: -20px; right: -20px;
      width: 80px; height: 80px; border-radius: 50%;
      filter: blur(28px); opacity: 0.2;
    }
    @media (max-width: 768px) {
      .stat-card { padding: 12px 14px; border-radius: 12px; }
      .stat-card .stat-value { font-size: 1.5rem; }
      .stat-card .stat-label { font-size: 0.6rem; }
      .stat-card .stat-sub { font-size: 0.62rem; }
    }
    @media (max-width: 480px) {
      .stat-card { padding: 10px 12px; }
      .stat-card .stat-value { font-size: 1.3rem; }
    }

    /* ══════════════ SECTION HEADER ══════════════ */
    .section-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 16px;
    }
    .section-title { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 1.1rem; font-weight: 700; }
    .section-tag {
      font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--sky);
      background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2);
      padding: 3px 10px; border-radius: 999px;
    }
    @media (max-width: 768px) {
      .section-header { margin-bottom: 12px; }
      .section-title { font-size: 0.95rem; }
      .section-tag { font-size: 0.6rem; padding: 2px 8px; }
    }
    @media (max-width: 480px) {
      .section-title { font-size: 0.9rem; }
    }

    /* ══════════════ MAIN FORM CARD ══════════════ */
    .form-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      overflow: hidden;
    }
    .form-card-header {
      padding: 14px 16px 12px;
      display: flex; align-items: center; gap: 12px;
      background: rgba(200,241,53,0.03);
    }
    .form-card-body { padding: 24px; }
    @media (max-width: 768px) {
      .form-card-header { padding: 14px 16px 12px; }
      .form-card-body { padding: 14px; }
      .form-card-header span { font-size: 1.1rem; }
    }
    @media (max-width: 480px) {
      .form-card-body { padding: 12px; }
    }

    /* ── INPUTS ── */
    .field-label {
      display: block; font-size: 0.72rem; font-weight: 700;
      letter-spacing: 0.08em; text-transform: uppercase;
      color: var(--muted); margin-bottom: 7px;
    }
    @media (max-width: 768px) {
      .field-label { font-size: 0.65rem; margin-bottom: 5px; }
    }
    .field-required { color: var(--accent); margin-left: 3px; }

    .input-base {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1.5px solid rgba(200,241,53,0.12);
      border-radius: 12px;
      padding: 12px 16px;
      color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      font-size: 0.9rem;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      outline: none;
      resize: none;
    }
    .input-base::placeholder { color: var(--muted); }
    .input-base:focus {
      border-color: var(--lime);
      background: rgba(200,241,53,0.04);
      box-shadow: 0 0 0 3px rgba(200,241,53,0.1);
    }
    .input-base:hover:not(:focus) { border-color: rgba(200,241,53,0.25); }
    @media (max-width: 768px) {
      .input-base { padding: 10px 12px; font-size: 0.85rem; border-radius: 10px; }
    }
    @media (max-width: 480px) {
      .input-base { padding: 9px 10px; font-size: 0.82rem; }
    }

    select.input-base {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234B6080' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
      cursor: pointer;
    }
    select.input-base option { background: var(--navy2); color: var(--text); }

    /* ── TOOL TAGS INPUT ── */
    .tag-input-wrap {
      min-height: 48px;
      background: rgba(255,255,255,0.04);
      border: 1.5px solid rgba(200,241,53,0.12);
      border-radius: 12px;
      padding: 8px 10px;
      display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
      cursor: text;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .tag-input-wrap:focus-within {
      border-color: var(--lime);
      box-shadow: 0 0 0 3px rgba(200,241,53,0.1);
    }
    .tag-chip {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(200,241,53,0.12); border: 1px solid rgba(200,241,53,0.25);
      color: var(--lime); font-size: 0.75rem; font-weight: 700;
      padding: 3px 10px; border-radius: 999px;
    }
    .tag-chip .tag-remove {
      cursor: pointer; color: rgba(200,241,53,0.5);
      font-size: 0.9rem; line-height: 1;
      transition: color 0.15s;
    }
    .tag-chip .tag-remove:hover { color: var(--accent); }
    #tag-field {
      flex: 1; min-width: 100px; background: transparent;
      border: none; outline: none; color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 0.88rem;
      padding: 3px 4px;
    }
    #tag-field::placeholder { color: var(--muted); }

    /* ── ACTIVITY TEXTAREA ── */
    #activity-box {
      position: relative;
    }
    .ai-hint {
      position: absolute; bottom: 10px; right: 12px;
      display: flex; align-items: center; gap: 5px;
      font-size: 0.65rem; color: var(--muted); font-weight: 600;
      pointer-events: none;
    }
    .ai-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: var(--lime);
      animation: pulse 1.6s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.75)} }

    /* ── IMAGE UPLOAD ── */
    .upload-zone {
      border: 2px dashed rgba(56,189,248,0.25);
      border-radius: 14px;
      padding: 28px 20px;
      text-align: center;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      background: rgba(56,189,248,0.03);
    }
    .upload-zone:hover, .upload-zone.dragover {
      border-color: var(--sky); background: rgba(56,189,248,0.07);
    }
    .upload-zone input[type="file"] { display: none; }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-zone p { font-size: 0.8rem; color: var(--muted); }
    .upload-zone p strong { color: var(--sky); }
    @media (max-width: 768px) {
      .upload-zone { padding: 20px 14px; }
      .upload-icon { font-size: 1.6rem; }
      .upload-zone p { font-size: 0.72rem; }
    }

    /* Image preview grid */
    #preview-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(90px,1fr));
      gap: 10px; margin-top: 14px;
    }
    .preview-thumb {
      position: relative; border-radius: 10px; overflow: hidden;
      aspect-ratio: 1; background: var(--card2);
      border: 1px solid var(--border);
    }
    .preview-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .preview-thumb .remove-img {
      position: absolute; top: 4px; right: 4px;
      width: 20px; height: 20px; border-radius: 50%;
      background: rgba(255,107,53,0.85); color: #fff;
      font-size: 0.65rem; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800;
    }

    /* ── CHAR COUNTER ── */
    .char-counter { font-size: 0.68rem; color: var(--muted); text-align: right; margin-top: 4px; }
    .char-counter.warn { color: var(--accent); }
    @media (max-width: 768px) { .char-counter { font-size: 0.62rem; } }

    /* ══════════════ FORM GRID ══════════════ */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }

    /* ══════════════ BUTTONS ══════════════ */
    .btn-lime {
      background: var(--lime); color: var(--navy);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-weight: 800;
      border-radius: 12px; padding: 13px 28px;
      font-size: 0.9rem; cursor: pointer; border: none;
      transition: transform 0.15s, box-shadow 0.15s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-lime:hover { transform: translateY(-2px); box-shadow: 0 0 28px rgba(200,241,53,0.3); }
    .btn-lime:active { transform: translateY(0); }

    .btn-ghost {
      background: transparent; color: var(--subtle);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-weight: 700;
      border-radius: 12px; padding: 13px 22px;
      font-size: 0.9rem; cursor: pointer;
      border: 1.5px solid rgba(200,241,53,0.15);
      transition: border-color 0.15s, color 0.15s, background 0.15s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-ghost:hover { border-color: var(--lime); color: var(--text); background: rgba(200,241,53,0.05); }

    .btn-sky {
      background: rgba(56,189,248,0.12); color: var(--sky);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-weight: 700;
      border-radius: 12px; padding: 13px 22px;
      font-size: 0.9rem; cursor: pointer;
      border: 1.5px solid rgba(56,189,248,0.25);
      transition: background 0.15s, border-color 0.15s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-sky:hover { background: rgba(56,189,248,0.2); border-color: var(--sky); }
    @media (max-width: 768px) {
      .btn-lime, .btn-ghost, .btn-sky {
        width: 100%; justify-content: center;
        padding: 12px 16px; font-size: 0.85rem;
        border-radius: 10px;
      }
    }
    @media (max-width: 480px) {
      .btn-lime, .btn-ghost, .btn-sky { padding: 11px 14px; font-size: 0.82rem; }
    }

    /* ══════════════ RECENT LOGS ══════════════ */
    .log-item {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px; padding: 14px 18px;
      display: flex; align-items: center; gap: 14px;
      margin-bottom: 10px;
      transition: transform 0.15s, border-color 0.15s;
      cursor: pointer;
    }
    .log-item:hover { transform: translateX(4px); border-color: rgba(200,241,53,0.28); }
    .log-day-badge {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-weight: 800;
      font-size: 1.1rem; min-width: 40px; text-align: center;
      color: var(--lime);
    }
    .log-divider { width: 1px; height: 36px; background: var(--border); flex-shrink: 0; }
    .log-summary { font-size: 0.85rem; font-weight: 600; color: var(--text); margin-bottom: 2px; }
    .log-date { font-size: 0.7rem; color: var(--muted); }
    .log-status {
      margin-left: auto; font-size: 0.65rem; font-weight: 700;
      padding: 3px 10px; border-radius: 999px;
      background: rgba(200,241,53,0.1); color: var(--lime);
      border: 1px solid rgba(200,241,53,0.2);
      white-space: nowrap;
    }

    /* ══════════════ AI GENERATE PANEL ══════════════ */
    .ai-panel {
      background: linear-gradient(135deg, rgba(200,241,53,0.06), rgba(56,189,248,0.04));
      border: 1px solid rgba(200,241,53,0.18);
      border-radius: 16px; padding: 18px 20px;
      margin-top: 24px;
    }
    .ai-panel-header { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }

    /* ══════════════ PROGRESS BAR ══════════════ */
    .progress-wrap { background: rgba(255,255,255,0.05); border-radius: 999px; height: 6px; }
    .progress-bar { height: 6px; border-radius: 999px; background: var(--lime); transition: width 1.2s ease; }

    /* ══════════════ SIDEBAR OVERLAY (MOBILE) ══════════════ */
    #sidebar-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.6); z-index: 99;
    }

    /* ══════════════ RESPONSIVE ══════════════ */
    @media (max-width: 768px) {
      #sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
      #sidebar.open { transform: translateX(0); }
      #main { margin-left: 0 !important; }
      .page-content { padding: 12px 10px 60px; }
      .form-grid { grid-template-columns: 1fr !important; gap: 14px; }
      #topbar { padding: 10px 12px; }
      .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
      .topbar-breadcrumb { display: none; }
      .main-cols { grid-template-columns: 1fr !important; }
      .step-dots { display: none; }
      .form-grid > div { margin-bottom: 14px; }
      .form-card { border-radius: 14px; }
    }

      .form-card { border-radius: 14px; }
      .upload-zone { padding: 20px 14px; }
      .upload-icon { font-size: 1.6rem; }
      .upload-zone p { font-size: 0.72rem; }

      .char-counter { font-size: 0.62rem; }

      .ai-hint { bottom: 8px; right: 10px; font-size: 0.6rem; }
      .ai-panel { padding: 14px 14px; }
      .ai-panel-header { margin-bottom: 8px; }

      .stat-card { padding: 12px 14px; border-radius: 12px; }
      .stat-card .stat-value { font-size: 1.5rem; }
      .stat-card .stat-label { font-size: 0.6rem; }
      .stat-card .stat-sub { font-size: 0.62rem; }
    }

    @media (max-width: 480px) {
      .stat-grid { gap: 8px; }
      .stat-card { padding: 10px 12px; }
      .stat-card .stat-value { font-size: 1.3rem; }
      .section-title { font-size: 0.9rem; }
      .section-tag { font-size: 0.6rem; padding: 2px 8px; }
      .form-card-body { padding: 12px; }
      .input-base { padding: 9px 10px; font-size: 0.82rem; }
      .form-grid { gap: 10px; }
      .upload-zone { padding: 16px 12px; }
      .btn-lime, .btn-ghost, .btn-sky { padding: 11px 14px; font-size: 0.82rem; }
      .log-item { padding: 8px 12px; }
      .log-day-badge { font-size: 0.9rem; }
    }

    /* ══════════════ STEP INDICATOR ══════════════ */
    .step-dots {
      display: flex; gap: 6px; align-items: center;
      margin-bottom: 20px;
    }
    .step-dot {
      width: 28px; height: 28px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.65rem; font-weight: 800;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      border: 2px solid var(--border);
      color: var(--muted);
    }
    .step-dot.done { background: var(--lime); color: var(--navy); border-color: var(--lime); }
    .step-dot.active { border-color: var(--lime); color: var(--lime); }
    .step-line { flex: 1; height: 1px; background: var(--border); }

    /* ══════════════ FLOAT BLOB ══════════════ */
    .bg-blob {
      position: fixed; border-radius: 50%;
      filter: blur(90px); opacity: 0.06; pointer-events: none; z-index: 0;
    }

    /* Reveal */
    .reveal { opacity:0; transform:translateY(20px); transition:opacity .5s ease,transform .5s ease; }
    .reveal.in { opacity:1; transform:translateY(0); }

    /* ══════════════ SETUP MODAL ══════════════ */
    .modal-overlay {
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.75); backdrop-filter: blur(8px);
      z-index: 1000; display: flex; align-items: center; justify-content: center;
      opacity: 0; visibility: hidden; transition: opacity .25s, visibility .25s;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box {
      background: var(--card); border: 1px solid rgba(200,241,53,0.2);
      border-radius: 24px; padding: 2rem; width: 100%; max-width: 440px;
      margin: 1rem; transform: scale(0.95); transition: transform .25s;
    }
    .modal-overlay.active .modal-box { transform: scale(1); }
    .form-group { margin-bottom: 1rem; }
    .form-group:last-child { margin-bottom: 0; }
    .field-label {
      display: block; font-size: 0.78rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .08em; color: #94A3B8; margin-bottom: .5rem;
    }
    .btn-primary {
      background: var(--lime); color: var(--navy); font-weight: 800;
      border-radius: 12px; padding: .875rem 1.5rem; font-size: 1rem;
      transition: transform .15s, box-shadow .15s;
      display: inline-flex; align-items: center; gap: .5rem;
      cursor: pointer; border: none;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 24px 6px rgba(200,241,53,.25);
    }
  </style>
</head>
<body>

<!-- BG BLOBS -->
<div class="bg-blob" style="width:500px;height:500px;background:var(--lime);top:-100px;left:-100px;"></div>
<div class="bg-blob" style="width:400px;height:400px;background:var(--sky);bottom:-80px;right:-80px;"></div>

<!-- ══════════════════════════════════════ SIDEBAR ══════════════════════════════════════ -->
<aside id="sidebar">

  <!-- Logo -->
  <div class="sidebar-logo">
    <span class="logo-icon">📒</span>
    <span class="logo-text">My<span>Kv</span>Log</span>
  </div>

  <!-- Nav -->
  <div class="nav-section-label">Main Menu</div>

  <?php
  $nav = [
    ['icon'=>'🏠','label'=>'Dashboard',  'href'=>'#','active'=>true,  'badge'=>null],
    ['icon'=>'📋','label'=>'My Logs',    'href'=>'#logs','active'=>false,'badge'=>'3'],
    ['icon'=>'🖨️','label'=>'Print',      'href'=>'#print','active'=>false,'badge'=>null],
  ];
  foreach ($nav as $item):
  ?>
  <a href="<?= $item['href'] ?>" class="nav-item <?= $item['active'] ? 'active' : '' ?>">
    <span class="nav-icon"><?= $item['icon'] ?></span>
    <?= $item['label'] ?>
    <?php if ($item['badge']): ?>
    <span class="nav-badge"><?= $item['badge'] ?></span>
    <?php endif; ?>
  </a>
  <?php endforeach; ?>

  <div class="nav-section-label" style="margin-top:8px;">Account</div>
  <a href="#profile" class="nav-item">
    <span class="nav-icon">👤</span>
    Profile
  </a>
  <a href="#" class="nav-item" style="color:rgba(255,107,53,0.7);" onmouseover="this.style.color='#FF6B35'" onmouseout="this.style.color='rgba(255,107,53,0.7)'">
    <span class="nav-icon">🚪</span>
    Logout
  </a>

  <!-- Profile bottom -->
  <div class="sidebar-profile">
    <div class="avatar">H</div>
    <div class="avatar-info">
      <div class="name">Hafizuddin R.</div>
      <div class="role">KV Ipoh · IT Networking</div>
    </div>
  </div>
</aside>

<!-- Mobile overlay -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>


<!-- ══════════════════════════════════════ MAIN ══════════════════════════════════════ -->
<div id="main">

  <!-- ── TOPBAR ── -->
  <div id="topbar">
    <div style="display:flex;align-items:center;gap:8px;">
      <button id="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <span id="tog1"></span>
        <span id="tog2"></span>
        <span id="tog3"></span>
      </button>
      <span class="topbar-breadcrumb">
        <strong>Dashboard</strong>
        <span style="margin:0 6px;opacity:0.4;">/</span>
        New Log Entry
      </span>
    </div>
    <div class="topbar-right">
      <span class="topbar-date-pill text-xs md:text-sm">📅 <?= $today ?></span>
      <div class="avatar" style="cursor:pointer;width:32px;height:32px;font-size:0.8rem;" title="Profile">H</div>
    </div>
  </div>

  <!-- ── PAGE CONTENT ── -->
  <div class="page-content">

    <!-- ─────────── STAT CARDS ─────────── -->
    <div class="stat-grid reveal in">
      <?php
      $stats = [
        ['Day','43','of 90-day internship','var(--lime)'],
        ['Logs Done','42','entries completed','var(--sky)'],
        ['This Week','5','entries this week','var(--lime)'],
        ['Completion','47%','progress to target','var(--accent)'],
      ];
      foreach ($stats as $s):
      ?>
      <div class="stat-card">
        <div class="stat-label"><?= $s[0] ?></div>
        <div class="stat-value" style="color:<?= $s[3] ?>;"><?= $s[1] ?></div>
        <div class="stat-sub"><?= $s[2] ?></div>
        <div class="stat-glow" style="background:<?= $s[3] ?>;"></div>
        <!-- Progress bar for completion -->
        <?php if ($s[0] === 'Completion'): ?>
        <div class="progress-wrap" style="margin-top:10px;">
          <div class="progress-bar" style="width:47%;background:var(--accent);"></div>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>


    <!-- ─────────── TWO COLUMN LAYOUT ─────────── -->
    <div style="display:grid;grid-template-columns:1fr 340px;gap:22px;" class="main-cols">

      <!-- ═══ LEFT: LOG ENTRY FORM ═══ -->
      <div>

        <div class="section-header reveal in">
          <div>
            <div class="section-title" style="font-size:1rem;">✍️ Today's Log Entry</div>
            <div style="font-size:0.7rem;color:var(--muted);margin-top:2px;"><?= $month_year ?> · Fill in your daily activities</div>
          </div>
          <span class="section-tag">Day <?= $day_num ?></span>
        </div>

        <!-- Step indicator -->
        <div class="step-dots reveal in hidden md:flex">
          <div class="step-dot done">1</div>
          <div class="step-line"></div>
          <div class="step-dot active">2</div>
          <div class="step-line"></div>
          <div class="step-dot">3</div>
          <div class="step-line"></div>
          <div class="step-dot">4</div>
          <div style="font-size:0.7rem;color:var(--muted);margin-left:4px;">Details → Activities → Tools → Media</div>
        </div>

        <div class="form-card reveal in">
          <div class="form-card-header">
            <span style="font-size:1.3rem;">📝</span>
            <div>
              <div class="syne" style="font-weight:700;font-size:0.95rem;">New Log Entry</div>
              <div style="font-size:0.7rem;color:var(--muted);">All fields marked <span style="color:var(--accent);">*</span> are required</div>
            </div>
          </div>
          <div class="form-card-body">
            <form id="logForm" onsubmit="handleSubmit(event)">

              <!-- Row 1: Day Number + Date -->
              <div class="form-grid" style="margin-bottom:20px;">
                <div>
                  <label class="field-label" for="day-number">
                    Internship Day <span class="field-required">*</span>
                  </label>
                  <div style="position:relative;">
                    <input
                      type="number"
                      id="day-number"
                      class="input-base"
                      placeholder="e.g. 43"
                      min="1" max="365"
                      value="43"
                      required
                    />
                    <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:0.75rem;color:var(--muted);">/ 90</span>
                  </div>
                </div>
                <div>
                  <label class="field-label" for="log-date">
                    Date <span class="field-required">*</span>
                  </label>
                  <input
                    type="date"
                    id="log-date"
                    class="input-base"
                    value="<?= date('Y-m-d') ?>"
                    required
                    style="color-scheme:dark;"
                  />
                </div>
              </div>

              <!-- Activities -->
              <div style="margin-bottom:20px;">
                <label class="field-label" for="activities">
                  Activities Done Today <span class="field-required">*</span>
                </label>
                <div id="activity-box" style="position:relative;">
                  <textarea
                    id="activities"
                    class="input-base"
                    rows="4"
                    placeholder="Just tell us what you did! e.g. 'helped set up the server rack, labelled cables, attended the morning briefing with IT team, installed new switches...' — don't worry about grammar 😊"
                    required
                    oninput="countChars(this,'act-count',800)"
                  ></textarea>
                  <div class="ai-hint">
                    <div class="ai-dot"></div>
                    AI will expand this
                  </div>
                </div>
                <div class="char-counter" id="act-count">0 / 800 characters</div>
              </div>

              <!-- Images Upload -->
              <div style="margin-bottom:24px;">
                <label class="field-label">
                  📸 Photos / Evidence <span style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0;">(optional but recommended)</span>
                </label>
                <div class="upload-zone" id="upload-zone" onclick="document.getElementById('file-input').click()" ondragover="dragOver(event)" ondrop="dropFiles(event)" ondragleave="dragLeave(event)">
                  <input type="file" id="file-input" multiple accept="image/*" onchange="handleFiles(this.files)"/>
                  <div class="upload-icon">📂</div>
                  <p><strong>Click to upload</strong> or drag & drop photos here</p>
                  <p style="margin-top:4px;font-size:0.7rem;">PNG, JPG, WEBP · Max 5MB each · Up to 10 images</p>
                </div>
                <div id="preview-grid"></div>
                <div id="file-count" style="font-size:0.72rem;color:var(--muted);margin-top:8px;"></div>
              </div>

              <!-- AI Generate Panel -->
              <div class="ai-panel" id="ai-panel" style="display:none;">
                <div class="ai-panel-header">
                  <div class="ai-dot" style="width:8px;height:8px;"></div>
                  <span class="syne" style="font-weight:700;font-size:0.9rem;color:var(--lime);">AI is writing your log...</span>
                </div>
                <div style="font-size:0.82rem;color:var(--subtle);line-height:1.7;" id="ai-output">
                  Analysing your activities and expanding into professional log format...
                </div>
                <div class="progress-wrap" style="margin-top:12px;">
                  <div class="progress-bar" id="ai-progress" style="width:0%;"></div>
                </div>
              </div>

              <!-- Buttons -->
              <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:4px;">
                <button type="submit" class="btn-lime">
                  🤖 Generate with AI
                </button>
                <button type="button" class="btn-sky" onclick="saveDraft()">
                  💾 Save Draft
                </button>
                <button type="button" class="btn-ghost" onclick="clearForm()">
                  🗑️ Clear
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>

      <!-- ═══ RIGHT COLUMN ═══ -->
      <div style="display:flex;flex-direction:column;gap:20px;" class="right-col hidden md:flex">

        <!-- Quick Tips -->
        <div class="form-card reveal in" style="transition-delay:0.1s;">
          <div class="form-card-header">
            <span>💡</span>
            <div class="syne" style="font-weight:700;font-size:0.9rem;">Writing Tips</div>
          </div>
          <div style="padding:16px 18px;">
            <?php
            $tips = [
              ['✅','Write in simple words — AI handles the grammar'],
              ['📸','Add photos for every task you complete'],
              ['🔧','List ALL tools, even basic ones'],
              ['📅','Log daily — don\'t wait until week-end'],
              ['🎯','Include what you learned, not just what you did'],
            ];
            foreach ($tips as $tip):
            ?>
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px;">
              <span style="font-size:0.85rem;flex-shrink:0;"><?= $tip[0] ?></span>
              <span style="font-size:0.78rem;color:var(--subtle);line-height:1.5;"><?= $tip[1] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Recent Logs -->
        <div class="reveal in" style="transition-delay:0.2s;">
          <div class="section-header" style="margin-bottom:12px;">
            <div class="section-title" style="font-size:0.95rem;">📋 Recent Logs</div>
            <a href="#logs" style="font-size:0.72rem;color:var(--sky);font-weight:700;text-decoration:none;">View all →</a>
          </div>

          <?php foreach ($recent_logs as $log): ?>
          <div class="log-item">
            <div class="log-day-badge">D<?= $log['day'] ?></div>
            <div class="log-divider"></div>
            <div style="flex:1;min-width:0;">
              <div class="log-summary" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= $log['summary'] ?></div>
              <div class="log-date"><?= $log['date'] ?></div>
            </div>
            <div class="log-status">✓ Done</div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Print CTA -->
        <div class="reveal in" style="transition-delay:0.4s;">
          <div style="background:linear-gradient(135deg,rgba(200,241,53,0.1),rgba(56,189,248,0.06));border:1px solid rgba(200,241,53,0.2);border-radius:16px;padding:18px 20px;text-align:center;">
            <div style="font-size:2rem;margin-bottom:8px;">🖨️</div>
            <div class="syne" style="font-weight:700;font-size:0.9rem;margin-bottom:4px;">Ready to Print?</div>
            <div style="font-size:0.75rem;color:var(--muted);margin-bottom:14px;">Compile this week's 4 logs into a ready-to-submit PDF</div>
            <button class="btn-lime" style="width:100%;justify-content:center;">Print Weekly Log</button>
          </div>
        </div>

      </div>
    </div><!-- /main-cols -->

  </div><!-- /page-content -->
</div><!-- /main -->


<!-- ══════════════════════════════════════ JS ══════════════════════════════════════ -->
<script>
  /* ── SIDEBAR TOGGLE ── */
  let sidebarOpen = window.innerWidth >= 769;

  function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const main = document.getElementById('main');
    const overlay = document.getElementById('sidebar-overlay');

    if (window.innerWidth < 769) {
      // Mobile: slide in/out
      sb.classList.toggle('open');
      overlay.style.display = sb.classList.contains('open') ? 'block' : 'none';
    } else {
      // Desktop: collapse/expand
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

  // Responsive two-column collapse
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

  /* ── TOOL TAGS ── */
  const tags = [];

  function addTag(val) {
    const v = val.trim();
    if (!v || tags.includes(v)) return;
    tags.push(v);
    renderTags();
  }

  function removeTag(idx) {
    tags.splice(idx, 1);
    renderTags();
  }

  function renderTags() {
    const wrap = document.getElementById('tag-wrap');
    const field = document.getElementById('tag-field');
    // Remove old chips
    wrap.querySelectorAll('.tag-chip').forEach(el => el.remove());
    // Re-insert before input
    tags.forEach((t, i) => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `${t}<span class="tag-remove" onclick="removeTag(${i})">✕</span>`;
      wrap.insertBefore(chip, field);
    });
  }

  function handleTagKey(e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      addTag(e.target.value);
      e.target.value = '';
    } else if (e.key === 'Backspace' && !e.target.value && tags.length) {
      tags.pop();
      renderTags();
    }
  }

  /* ── CHAR COUNTER ── */
  function countChars(el, counterId, max) {
    const counter = document.getElementById(counterId);
    const len = el.value.length;
    counter.textContent = `${len} / ${max} characters`;
    counter.classList.toggle('warn', len > max * 0.85);
    if (len > max) el.value = el.value.slice(0, max);
  }

  /* ── FILE UPLOAD ── */
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

  /* drag & drop */
  function dragOver(e) { e.preventDefault(); document.getElementById('upload-zone').classList.add('dragover'); }
  function dragLeave(e) { document.getElementById('upload-zone').classList.remove('dragover'); }
  function dropFiles(e) { e.preventDefault(); dragLeave(e); handleFiles(e.dataTransfer.files); }

  /* ── AI GENERATE ── */
  function handleSubmit(e) {
    e.preventDefault();
    const acts = document.getElementById('activities').value.trim();
    const desc = document.getElementById('description').value.trim();
    if (!acts || !desc) { alert('Please fill in the activities and description fields.'); return; }
    if (tags.length === 0) { alert('Please add at least one tool used today.'); return; }

    const panel = document.getElementById('ai-panel');
    const progress = document.getElementById('ai-progress');
    const output = document.getElementById('ai-output');
    panel.style.display = 'block';
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    // Simulate AI generation
    const stages = [
      [10,  'Analysing your activities...'],
      [30,  'Identifying key tasks and tools...'],
      [55,  'Expanding into professional format...'],
      [75,  'Structuring log entry paragraphs...'],
      [90,  'Integrating photos and descriptions...'],
      [100, generateLogText(acts, desc)],
    ];

    let delay = 0;
    stages.forEach(([pct, msg]) => {
      delay += 600;
      setTimeout(() => {
        progress.style.width = pct + '%';
        output.innerHTML = msg;
        if (pct === 100) {
          output.style.color = 'var(--text)';
        }
      }, delay);
    });
  }

  function generateLogText(acts, desc) {
    const day = document.getElementById('day-number').value || '43';
    const date = document.getElementById('log-date').value || new Date().toLocaleDateString('en-GB');
    const dept = document.getElementById('department').value || 'IT Department';
    const sup = document.getElementById('supervisor').value || 'the supervisor';
    const toolList = tags.join(', ') || 'various tools';

    return `<strong style="color:var(--lime);">✅ Log Generated — Day ${day} · ${date}</strong><br><br>
    On this date, as part of my practical training in the <em>${dept}</em>, I carried out the following tasks under the supervision of ${sup}.<br><br>
    ${acts.charAt(0).toUpperCase() + acts.slice(1)}. Throughout these activities, the following tools and equipment were utilised: <strong style="color:var(--sky);">${toolList}</strong>.<br><br>
    ${desc.charAt(0).toUpperCase() + desc.slice(1)}. Through these hands-on experiences, I was able to develop a deeper understanding of practical applications in the field.<br><br>
    <span style="color:var(--lime);font-weight:700;">→ Ready to print!</span> &nbsp;<button class="btn-lime" style="padding:6px 16px;font-size:0.78rem;" onclick="window.print()">🖨️ Print Now</button>`;
  }

  /* ── SAVE DRAFT ── */
  function saveDraft() {
    const btn = event.target;
    btn.textContent = '✅ Draft Saved!';
    btn.style.color = 'var(--lime)';
    setTimeout(() => { btn.textContent = '💾 Save Draft'; btn.style.color = ''; }, 2000);
  }

  /* ── CLEAR FORM ── */
  function clearForm() {
    if (!confirm('Clear all fields?')) return;
    document.getElementById('logForm').reset();
    tags.length = 0;
    renderTags();
    uploadedFiles = [];
    document.getElementById('preview-grid').innerHTML = '';
    document.getElementById('file-count').textContent = '';
    document.getElementById('act-count').textContent = '0 / 800 characters';
    document.getElementById('desc-count').textContent = '0 / 400 characters';
    document.getElementById('ai-panel').style.display = 'none';
  }

  /* ── REVEAL ── */
  const io = new IntersectionObserver(es => {
    es.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal:not(.in)').forEach(r => io.observe(r));

  /* ── SETUP MODAL FOR NEW USERS ── */
  <?php if ($isnew): ?>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('setupModal');
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  });
  
  function closeSetupModal() {
    document.getElementById('setupModal').classList.remove('active');
    document.body.style.overflow = '';
  }
  
  function handleSetupSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const period = form.querySelector('[name="internship_period"]').value;
    const department = form.querySelector('[name="department"]').value;
    const company = form.querySelector('[name="company"]').value;
    const jobScope = form.querySelector('[name="job_scope"]').value;
    
    console.log('Setup data:', { period, department, company, jobScope });
    alert('Setup complete! Welcome to MyInternLog 🚀');
    closeSetupModal();
  }
  <?php endif; ?>
</script>

<!-- ══════════════════════════════════ SETUP MODAL FOR NEW USERS ══════════════════════════════════ -->
<?php if ($isnew): ?>
<div id="setupModal" class="modal-overlay">
  <div class="modal-box">
    <div style="text-align:center; margin-bottom:1.5rem;">
      <div style="font-size:3rem; margin-bottom:0.5rem;">🚀</div>
      <h2 style="font-size:1.5rem; font-weight:800; margin-bottom:0.5rem;">Welcome to MyInternLog!</h2>
      <p style="color:#64748B; font-size:0.95rem;">Let's set up your profile. Tell us about your internship.</p>
    </div>
    
    <form onsubmit="handleSetupSubmit(event)">
      <div class="form-group">
        <label class="field-label">Internship Duration (Days)</label>
        <input type="number" name="internship_period" class="input-base" value="<?= $default_internship_period ?>" min="1" required />
      </div>
      
      <div class="form-group">
        <label class="field-label">Department Name</label>
        <input type="text" name="department" class="input-base" placeholder="e.g. IT Department, Electrical Engineering" required />
      </div>
      
      <div class="form-group">
        <label class="field-label">Company / Workplace Name</label>
        <input type="text" name="company" class="input-base" placeholder="e.g. Petronas Carigali, Intel Malaysia" required />
      </div>
      
      <div class="form-group">
        <label class="field-label">Job Scope / Position</label>
        <textarea name="job_scope" class="input-base" rows="3" placeholder="e.g. Network infrastructure maintenance, server administration, cable management..." required></textarea>
      </div>
      
      <button type="submit" class="btn-primary" style="width:100%;">
        Get Started →
      </button>
    </form>
  </div>
</div>
<?php endif; ?>

</body>
</html>