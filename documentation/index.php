<?php
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MyInternLog — Tell. Print. Smile.</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --lime: #C8F135;
      --navy: #0D1117;
      --navy2: #131920;
      --ink: #1E2A38;
      --soft: #F0F4FF;
      --accent: #FF6B35;
      --sky: #38BDF8;
      --card: #192130;
    }

    * { box-sizing: border-box; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background-color: var(--navy);
      color: #E2EAF4;
      overflow-x: hidden;
    }

    h1, h2, h3, .syne { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }

    /* ── NOISE OVERLAY ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
      pointer-events: none;
      z-index: -1;
    }

    /* ── GRADIENT BLOBS ── */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.18;
      pointer-events: none;
    }
    .blob-lime  { background: var(--lime);   width: 520px; height: 520px; }
    .blob-orange{ background: var(--accent); width: 380px; height: 380px; }
    .blob-sky   { background: var(--sky);    width: 420px; height: 420px; }

    /* ── NAV ── */
    nav {
      position: sticky; top: 0; z-index: 50;
      backdrop-filter: blur(18px);
      background: rgba(13,17,23,0.78);
      border-bottom: 1px solid rgba(200,241,53,0.12);
    }

    /* ── PILL BADGE ── */
    .badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 4px 14px;
      border-radius: 999px;
      border: 1.5px solid var(--lime);
      color: var(--lime);
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    /* ── HERO HEADLINE ── */
    .hero-title span.highlight {
      color: var(--lime);
      position: relative;
      display: inline-block;
    }
    .hero-title span.highlight::after {
      content: '';
      position: absolute;
      bottom: -4px; left: 0; right: 0;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
      transform: skewX(-6deg);
    }

    /* ── CTA BUTTON ── */
    .btn-primary {
      background: var(--lime);
      color: var(--navy);
      font-family: --apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      font-weight: 800;
      border-radius: 12px;
      padding: 14px 32px;
      font-size: 1rem;
      transition: transform 0.15s, box-shadow 0.15s;
      display: inline-flex; align-items: center; gap: 8px;
      box-shadow: 0 0 0 0 rgba(200,241,53,0.4);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 32px 8px rgba(200,241,53,0.25);
    }
    .btn-secondary {
      border: 2px solid rgba(200,241,53,0.3);
      color: #E2EAF4;
      border-radius: 12px;
      padding: 14px 32px;
      font-size: 1rem;
      font-weight: 700;
      transition: border-color 0.15s, background 0.15s;
    }
    .btn-secondary:hover {
      border-color: var(--lime);
      background: rgba(200,241,53,0.07);
    }

    /* ── STEP CARDS ── */
    .step-card {
      background: var(--card);
      border: 1px solid rgba(200,241,53,0.12);
      border-radius: 20px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
      transition: transform 0.2s, border-color 0.2s;
    }
    .step-card:hover { transform: translateY(-4px); border-color: rgba(200,241,53,0.35); }
    .step-card .step-num {
      font-family: --apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      font-size: 4rem;
      font-weight: 800;
      line-height: 1;
      color: rgba(200,241,53,0.12);
      position: absolute;
      top: 12px; right: 20px;
    }

    /* ── FEATURE CARDS ── */
    .feat-card {
      background: var(--card);
      border: 1px solid rgba(56,189,248,0.12);
      border-radius: 20px;
      padding: 1.75rem;
      transition: transform 0.2s, border-color 0.2s;
    }
    .feat-card:hover { transform: translateY(-4px); border-color: rgba(56,189,248,0.35); }
    .feat-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    /* ── MOCKUP WINDOW ── */
    .mockup-window {
      background: var(--card);
      border: 1px solid rgba(200,241,53,0.15);
      border-radius: 16px;
      overflow: hidden;
    }
    .mockup-bar {
      background: rgba(255,255,255,0.04);
      padding: 10px 16px;
      display: flex; align-items: center; gap: 7px;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .dot { width: 11px; height: 11px; border-radius: 50%; }

    /* ── TESTIMONIAL ── */
    .testi-card {
      background: var(--card);
      border: 1px solid rgba(200,241,53,0.1);
      border-radius: 20px;
      padding: 1.75rem;
    }

    /* ── FLOATING TAGS ── */
    .float-tag {
      position: absolute;
      background: rgba(200,241,53,0.12);
      border: 1px solid rgba(200,241,53,0.3);
      color: var(--lime);
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 999px;
      white-space: nowrap;
      animation: floatY 3s ease-in-out infinite;
    }
    @keyframes floatY {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-8px); }
    }

    /* ── REVEAL ANIMATIONS ── */
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* ── MARQUEE ── */
    .marquee-wrap { overflow: hidden; }
    .marquee-track {
      display: flex; gap: 2rem;
      animation: marquee 22s linear infinite;
      width: max-content;
    }
    @keyframes marquee {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }
    .marquee-item {
      display: flex; align-items: center; gap: 8px;
      color: rgba(200,241,53,0.55);
      font-family: --apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      font-weight: 700;
      font-size: 0.9rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    /* ── GLOW LINE ── */
    .glow-line {
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--lime), transparent);
      opacity: 0.35;
    }

    /* ── SECTION LABEL ── */
    .section-label {
      font-family: --apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      font-size: 0.72rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--sky);
      font-weight: 700;
    }

    /* TYPING EFFECT */
    .typing-cursor::after {
      content: '|';
      animation: blink 0.8s step-end infinite;
      color: var(--lime);
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }

    /* ── MODAL ── */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(8px);
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s, visibility 0.25s;
    }
    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }
    .modal-box {
      background: var(--card);
      border: 1px solid rgba(200, 241, 53, 0.2);
      border-radius: 24px;
      padding: 1.5rem;
      width: 100%;
      max-width: 420px;
      margin: 1rem;
      transform: scale(0.95);
      transition: transform 0.25s;
    }
    @media (max-width: 480px) {
      .modal-box { padding: 1.25rem; }
    }
    .modal-overlay.active .modal-box {
      transform: scale(1);
    }
    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: transparent;
      border: none;
      color: #64748B;
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0.5rem;
      line-height: 1;
    }
    .modal-close:hover { color: #E2EAF4; }
    .modal-title {
      font-size: 1.75rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
      text-align: center;
    }
    .modal-subtitle {
      color: #64748B;
      text-align: center;
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }
    .btn-google {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      width: 100%;
      padding: 0.875rem 1.5rem;
      border-radius: 12px;
      border: 1.5px solid rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.04);
      color: #E2EAF4;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
    }
    .btn-google:hover {
      border-color: rgba(255, 255, 255, 0.25);
      background: rgba(255, 255, 255, 0.08);
    }
    .btn-google img {
      width: 20px;
      height: 20px;
    }
    .divider {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin: 1.5rem 0;
      color: #4B5563;
      font-size: 0.85rem;
    }
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
    }
    .form-group {
      margin-bottom: 1rem;
    }
    .form-group label {
      display: block;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #94A3B8;
      margin-bottom: 0.5rem;
    }
    .form-group input {
      width: 100%;
      padding: 0.875rem 1rem;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.04);
      color: #E2EAF4;
      font-size: 1rem;
      transition: border-color 0.2s;
    }
    .form-group input:focus {
      outline: none;
      border-color: var(--lime);
    }
    .form-group input::placeholder {
      color: #4B5563;
    }
    .btn-submit {
      width: 100%;
      padding: 1rem;
      border-radius: 12px;
      border: none;
      background: var(--lime);
      color: var(--navy);
      font-size: 1rem;
      font-weight: 800;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 24px 4px rgba(200, 241, 53, 0.25);
    }
    .modal-switch {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: #64748B;
    }
    .modal-switch a {
      color: var(--lime);
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
    }
    .modal-switch a:hover {
      text-decoration: underline;
    }
    .hidden { display: none; }
  </style>
</head>
<body>

<!-- ══════════════════════════════════ NAV ══════════════════════════════════ -->
<nav>
  <div class="max-w-6xl mx-auto px-4 md:px-6 py-3 md:py-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <span style="font-size:1.6rem;">📒</span>
      <span class="syne font-800 text-xl tracking-tight" style="font-weight:800;">
        My<span style="color:var(--lime);">Kv</span>Log
      </span>
    </div>
    <div class="hidden md:flex items-center gap-8 text-sm font-600" style="font-weight:600;">
      <a href="#how" class="hover:text-white transition-colors" style="color:#94A3B8;">How It Works</a>
      <a href="#features" class="hover:text-white transition-colors" style="color:#94A3B8;">Features</a>
      <a href="#testimonials" class="hover:text-white transition-colors" style="color:#94A3B8;">Students Say</a>
    </div>
    <a href="#" onclick="openModal(); return false;" class="btn-primary text-sm py-2.5 px-4 md:px-5">Get Started Free →</a>
  </div>
</nav>


<!-- ══════════════════════════════════ HERO ══════════════════════════════════ -->
<section class="relative min-h-screen flex items-center pt-10 pb-20 overflow-hidden">
  <!-- Blobs -->
  <div class="blob blob-lime hidden sm:block" style="top:-80px;left:-120px;"></div>
  <div class="blob blob-orange hidden sm:block" style="bottom:60px;right:-100px;"></div>

  <div class="max-w-6xl mx-auto px-4 md:px-6 relative z-10 w-full">
    <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">

      <!-- LEFT -->
      <div class="text-center lg:text-left">
        <div class="badge mb-6 inline-flex">✨ AI-Powered for KV Interns</div>

        <h1 class="hero-title syne text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
          Stop Struggling to<br>
          <span class="highlight">Write Your Log.</span><br>
          Just Tell Us.
        </h1>

        <p class="text-base md:text-lg mb-8" style="color:#94A3B8; line-height:1.75;">
          MyInternLog listens to what you did today and <strong style="color:#E2EAF4;">automatically writes your internship log</strong> in perfect, professional sentences — with pictures and diagrams included. Done in minutes.
        </p>

        <!-- Tell → Print → Smile -->
        <div class="flex items-center gap-3 mb-10 flex-wrap">
          <?php
          $steps = ['📣 Tell', '→', '🖨️ Print', '→', '😊 Smile'];
          foreach ($steps as $s):
            if ($s === '→'):
          ?>
            <span style="color:rgba(200,241,53,0.4);font-size:1.5rem;font-weight:800;">→</span>
          <?php else: ?>
            <span class="syne font-800 text-base px-4 py-2 rounded-xl" style="font-weight:800;background:rgba(200,241,53,0.1);border:1.5px solid rgba(200,241,53,0.25);color:var(--lime);"><?= $s ?></span>
          <?php endif; endforeach; ?>
        </div>

        <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
          <a href="#" onclick="openModal(); return false;" class="btn-primary">Start Writing Now 🚀</a>
          <a href="#how" class="btn-secondary">See How It Works</a>
        </div>

        <!-- Stats row -->
        <div class="mt-12 flex gap-8 flex-wrap justify-center lg:justify-start">
          <?php
          $stats = [
            ['500+', 'Students using it'],
            ['3 mins', 'Avg. log time'],
            ['100%', 'CIDB-ready format'],
          ];
          foreach ($stats as $st):
          ?>
          <div>
            <div class="syne text-2xl font-extrabold" style="color:var(--lime);font-weight:800;"><?= $st[0] ?></div>
            <div class="text-xs" style="color:#64748B;"><?= $st[1] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- RIGHT — Mockup -->
      <div class="relative hidden lg:block">
        <!-- Floating tags -->
        <div class="float-tag" style="top:-18px;left:20px;animation-delay:0s;">✅ Auto-written</div>
        <div class="float-tag" style="top:40px;right:-10px;animation-delay:1s;">📸 Photos added</div>

        <div class="mockup-window shadow-2xl">
          <div class="mockup-bar">
            <div class="dot" style="background:#FF5F57;"></div>
            <div class="dot" style="background:#FFBD2E;"></div>
            <div class="dot" style="background:#28CA41;"></div>
            <span class="ml-3 text-xs" style="color:#4B5563;">MyInternLog — Daily Log Generator</span>
          </div>
          <div class="p-6">
            <!-- Input area -->
            <div class="mb-5">
              <div class="text-xs mb-2" style="color:#64748B;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">What did you do today?</div>
              <div class="rounded-xl p-4 text-sm leading-relaxed" style="background:rgba(255,255,255,0.04);border:1px solid rgba(200,241,53,0.15);color:#94A3B8;">
                helped set up the server rack, labelled cables, and attended the morning briefing with the IT team...
              </div>
            </div>
            <!-- AI generating animation -->
            <div class="flex items-center gap-2 mb-4">
              <div style="width:8px;height:8px;border-radius:50%;background:var(--lime);animation:blink 0.6s ease infinite;"></div>
              <span class="text-xs" style="color:var(--lime);font-weight:700;">AI Writing your log...</span>
            </div>
            <!-- Output -->
            <div class="rounded-xl p-4 text-sm leading-relaxed" style="background:rgba(200,241,53,0.05);border:1px solid rgba(200,241,53,0.2);color:#CBD5E1;">
              <strong style="color:var(--lime);">Log Entry — <?= date('d/m/Y') ?></strong><br><br>
              On this date, I assisted the IT Department in the physical installation and organisation of the server rack within the data centre. Tasks performed included routing and labelling network cables according to the assigned colour-coding system to ensure clarity and ease of maintenance. Additionally, I participated in the morning briefing conducted by the IT supervisor, during which updates on the current network infrastructure project were discussed...
            </div>
            <!-- Bottom bar -->
            <div class="flex items-center justify-between mt-5">
              <div class="flex gap-2">
                <span class="text-xs px-3 py-1 rounded-lg" style="background:rgba(56,189,248,0.1);color:var(--sky);border:1px solid rgba(56,189,248,0.2);">📸 2 Photos</span>
                <span class="text-xs px-3 py-1 rounded-lg" style="background:rgba(255,107,53,0.1);color:#FF9F7A;border:1px solid rgba(255,107,53,0.2);">📊 1 Diagram</span>
              </div>
              <button class="btn-primary text-xs py-2 px-4">🖨️ Print</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ══════════════════════════════════ MARQUEE ══════════════════════════════════ -->
<div class="glow-line"></div>
<div class="py-5 marquee-wrap" style="background:rgba(200,241,53,0.03);">
  <div class="marquee-track">
    <?php
    $items = ['AI Writing Assistant','Photo Integration','Auto Diagrams','One-Click Print','KV Log Format','Fast & Easy','Tell → Print → Smile','No More Writer\'s Block','Professional Results','AI Writing Assistant','Photo Integration','Auto Diagrams','One-Click Print','KV Log Format','Fast & Easy','Tell → Print → Smile','No More Writer\'s Block','Professional Results'];
    foreach ($items as $item):
    ?>
    <div class="marquee-item">
      <span style="color:var(--lime);font-size:0.6rem;">◆</span>
      <?= htmlspecialchars($item) ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<div class="glow-line"></div>


<!-- ══════════════════════════════════ HOW IT WORKS ══════════════════════════════════ -->
<section id="how" class="py-16 md:py-28 relative overflow-hidden">
  <div class="blob blob-sky" style="top:50%;right:-200px;opacity:0.1;"></div>
  <div class="max-w-6xl mx-auto px-4 md:px-6">

    <div class="text-center mb-12 md:mb-16 reveal">
      <div class="section-label mb-3">How It Works</div>
      <h2 class="syne text-4xl lg:text-5xl font-extrabold mb-4" style="font-weight:800;">
        3 Steps. Zero Stress.
      </h2>
      <p style="color:#64748B;max-width:480px;margin:0 auto;">From rough thoughts to a polished, printable log book entry — in under 3 minutes.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      <?php
      $steps_data = [
        ['01','📣','Tell MyInternLog','Just speak or type what you did today in plain language. No grammar worries, no formatting — just your raw thoughts.', 'var(--lime)'],
        ['02','🤖','AI Writes It','Our AI expands your input into a detailed, professional log entry in the correct KV internship format — instantly.', 'var(--sky)'],
        ['03','🖨️','Print & Smile','Add your photos, get diagrams auto-generated, compile your weekly log, and hit Print. Done. Supervisor impressed.', 'var(--accent)'],
      ];
      foreach ($steps_data as $i => $step):
      ?>
      <div class="step-card reveal" style="transition-delay: <?= $i * 0.12 ?>s">
        <div class="step-num text-center md:text-right"><?= $step[0] ?></div>
        <div class="text-3xl md:text-4xl mb-4 text-center"><?= $step[1] ?></div>
        <h3 class="syne text-xl font-bold mb-3" style="color:<?= $step[4] ?>;font-weight:700;"><?= $step[2] ?></h3>
        <p class="text-sm leading-relaxed" style="color:#64748B;"><?= $step[3] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Arrow connectors (desktop) -->
    <div class="hidden md:flex justify-center items-center gap-6 mt-8">
      <?php for ($i=0;$i<3;$i++): ?>
        <div class="flex-1 h-px" style="background:linear-gradient(90deg,transparent,rgba(200,241,53,0.3),transparent);"></div>
        <?php if ($i<2): ?>
        <div style="color:var(--lime);font-size:1.5rem;font-weight:800;">→</div>
        <?php endif; ?>
      <?php endfor; ?>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════ FEATURES ══════════════════════════════════ -->
<section id="features" class="py-16 md:py-28" style="background:var(--navy2);">
  <div class="max-w-6xl mx-auto px-4 md:px-6">

    <div class="text-center mb-12 md:mb-16 reveal">
      <div class="section-label mb-3">Features</div>
      <h2 class="syne text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4" style="font-weight:800;">
        Built for KV Students,<br>by people who get it.
      </h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $features = [
        ['🧠','rgba(200,241,53,0.12)','var(--lime)','AI Log Writer','Paste or speak your day in broken sentences. MyInternLog rewrites it into perfect, professional English automatically.'],
        ['📸','rgba(56,189,248,0.12)','var(--sky)','Photo Integration','Attach your on-site photos and the AI positions them right next to the relevant log entries.'],
        ['📊','rgba(255,107,53,0.12)','var(--accent)','Auto Diagrams','Describe a workflow and get a clean diagram auto-generated to attach to your log. No Visio needed.'],
        ['📋','rgba(200,241,53,0.12)','var(--lime)','CIDB/KV Format','Output is pre-formatted to match your KV internship log book structure — ready to submit.'],
        ['📅','rgba(56,189,248,0.12)','var(--sky)','Weekly Compiler','Entries from the week are automatically compiled into one clean, paginated weekly log.'],
        ['🖨️','rgba(255,107,53,0.12)','var(--accent)','One-Click Print','Print straight from the browser with perfect margins, headers, and page numbers. No extra tools.'],
      ];
      foreach ($features as $i => $feat):
      ?>
      <div class="feat-card reveal" style="transition-delay:<?= $i*0.08 ?>s">
        <div class="feat-icon" style="background:<?= $feat[1] ?>;color:<?= $feat[2] ?>;"><?= $feat[0] ?></div>
        <h3 class="syne font-bold text-lg mb-2" style="font-weight:700;"><?= $feat[3] ?></h3>
        <p class="text-sm leading-relaxed" style="color:#64748B;"><?= $feat[4] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════ BEFORE / AFTER ══════════════════════════════════ -->
<section class="py-16 md:py-28 relative overflow-hidden">
  <div class="blob blob-lime" style="bottom:-100px;left:-150px;opacity:0.1;"></div>
  <div class="max-w-5xl mx-auto px-4 md:px-6">

    <div class="text-center mb-14 reveal">
      <div class="section-label mb-3">The Difference</div>
      <h2 class="syne text-4xl font-extrabold" style="font-weight:800;">Before & After MyInternLog</h2>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      <!-- BEFORE -->
      <div class="rounded-2xl p-6 reveal" style="background:rgba(255,107,53,0.07);border:1.5px solid rgba(255,107,53,0.2);">
        <div class="flex items-center gap-2 mb-5">
          <span style="font-size:1.3rem;">😰</span>
          <span class="syne font-bold text-lg" style="color:#FF6B35;font-weight:700;">Without MyInternLog</span>
        </div>
        <?php
        $befores = [
          'Stare at blank page for 20 minutes',
          '"Today I do… erm… server thing"',
          'Forget what you did 3 days ago',
          'Submit log 2 weeks late',
          'Supervisor marks it "Not detailed enough"',
          'Miss the photo requirement again 📸❌',
        ];
        foreach ($befores as $b):
        ?>
        <div class="flex items-start gap-3 mb-3">
          <span style="color:#FF6B35;margin-top:2px;">✗</span>
          <span class="text-sm" style="color:#94A3B8;"><?= $b ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- AFTER -->
      <div class="rounded-2xl p-6 reveal" style="transition-delay:0.15s;background:rgba(200,241,53,0.07);border:1.5px solid rgba(200,241,53,0.25);">
        <div class="flex items-center gap-2 mb-5">
          <span style="font-size:1.3rem;">🎉</span>
          <span class="syne font-bold text-lg" style="color:var(--lime);font-weight:700;">With MyInternLog</span>
        </div>
        <?php
        $afters = [
          'Open app → tell it what you did → done',
          'Perfectly written in under 3 minutes',
          'Daily entries auto-saved, always on track',
          'Weekly log compiled automatically',
          '"Excellent detail!" — every supervisor',
          'Photos and diagrams included automatically 📸✅',
        ];
        foreach ($afters as $a):
        ?>
        <div class="flex items-start gap-3 mb-3">
          <span style="color:var(--lime);margin-top:2px;">✓</span>
          <span class="text-sm" style="color:#CBD5E1;"><?= $a ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════ TESTIMONIALS ══════════════════════════════════ -->
<section id="testimonials" class="py-16 md:py-28" style="background:var(--navy2);">
  <div class="max-w-6xl mx-auto px-4 md:px-6">

    <div class="text-center mb-16 reveal">
      <div class="section-label mb-3">Students Say</div>
      <h2 class="syne text-4xl font-extrabold" style="font-weight:800;">Real KV Interns, Real Results 🎓</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      <?php
      $testis = [
        ['Hafizuddin R.', 'IT Networking, KV Ipoh', '⭐⭐⭐⭐⭐', 'Dulu makan masa 1 jam nak tulis log. Sekarang 3 minit je. Supervisor cakap log saya paling detail dalam batch! 🔥'],
        ['Nurul Ain M.', 'Electrical Engineering, KV Pasir Mas', '⭐⭐⭐⭐⭐', 'The AI is so smart, I just said "fixed broken socket and labelled wires" and it wrote 3 paragraphs of perfect log. I cried (happy tears) 😭'],
        ['Syafiq I.', 'Auto Mechanics, KV Temerloh', '⭐⭐⭐⭐⭐', 'The photo feature is amazing. I just upload pictures from my phone and they go right into the log next to the right task. Memang terbaik!'],
      ];
      foreach ($testis as $i => $t):
      ?>
      <div class="testi-card reveal" style="transition-delay:<?= $i*0.12 ?>s">
        <div class="text-2xl mb-3"><?= $t[2] ?></div>
        <p class="text-sm leading-relaxed mb-5" style="color:#94A3B8;">"<?= $t[3] ?>"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold" style="background:rgba(200,241,53,0.15);color:var(--lime);">
            <?= strtoupper(substr($t[0],0,1)) ?>
          </div>
          <div>
            <div class="text-sm font-bold"><?= $t[0] ?></div>
            <div class="text-xs" style="color:#4B5563;"><?= $t[1] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════ CTA ══════════════════════════════════ -->
<section id="cta" class="py-20 md:py-32 relative overflow-hidden">
  <div class="blob blob-lime" style="top:50%;left:50%;transform:translate(-50%,-50%);opacity:0.13;width:700px;height:700px;"></div>

  <div class="max-w-3xl mx-auto px-4 md:px-6 text-center relative z-10 reveal">
    <div class="text-4xl md:text-5xl mb-4 md:mb-6">🚀</div>
    <h2 class="syne text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 md:mb-6" style="font-weight:800;">
      Your Supervisor Will<br>
      <span style="color:var(--lime);">Actually Be Impressed.</span>
    </h2>
    <p class="text-base md:text-lg mb-8" style="color:#64748B;">
      Join 500+ KV interns who stopped dreading log day. It's free to start — no credit card, no complicated setup.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
      <a href="#" onclick="openModal(); return false;" class="btn-primary text-lg py-4 px-8">📒 Start Writing for Free</a>
      <a href="#how" class="btn-secondary text-lg py-4 px-8">Watch Demo →</a>
    </div>

    <p class="text-xs" style="color:#374151;">✓ Free for all KV students &nbsp;·&nbsp; ✓ Works on phone &nbsp;·&nbsp; ✓ Print-ready output</p>
  </div>
</section>


<!-- ══════════════════════════════════ FOOTER ══════════════════════════════════ -->
<footer style="background:var(--navy2);border-top:1px solid rgba(200,241,53,0.08);">
  <div class="max-w-6xl mx-auto px-6 py-10">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">

      <div class="flex items-center gap-2">
        <span style="font-size:1.4rem;">📒</span>
        <span class="syne font-800 text-lg" style="font-weight:800;">
          My<span style="color:var(--lime);">Kv</span>Log
        </span>
        <span class="text-xs ml-2" style="color:#374151;">Tell → Print → Smile</span>
      </div>

      <div class="flex gap-8 text-sm" style="color:#4B5563;">
        <a href="#" class="hover:text-white transition-colors">Privacy</a>
        <a href="#" class="hover:text-white transition-colors">Terms</a>
        <a href="#" class="hover:text-white transition-colors">Contact</a>
        <a href="#" class="hover:text-white transition-colors">FAQ</a>
      </div>

      <div class="text-xs" style="color:#374151;">
        © <?= $year ?> MyInternLog. Made with ❤️ for KV students in Malaysia.
      </div>
    </div>
  </div>
</footer>


<!-- ══════════════════════════════════ LOGIN/SIGNUP MODAL ══════════════════════════════════ -->
<div id="authModal" class="modal-overlay">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">&times;</button>
    
    <!-- Login Form -->
    <div id="loginForm">
      <div class="modal-title">Welcome Back! 👋</div>
      <div class="modal-subtitle">Sign in to continue writing your log</div>
      
      <button class="btn-google">
        <img src="https://www.google.com/favicon.ico" alt="Google" onerror="this.src='https://fonts.gstatic.com/s/product/sans_tf/v3/6xKtdSZaM9iE8KbpRA_hK1QN.woff2'" />
        Continue with Google
      </button>
      
      <div class="divider">or continue with email</div>
      
      <form onsubmit="handleLogin(event)">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" placeholder="you@example.com" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" placeholder="Enter your password" required />
        </div>
        <button type="submit" class="btn-submit">Sign In</button>
      </form>
      
      <div class="modal-switch">
        Don't have an account? <a onclick="showSignup()">Sign up free</a>
      </div>
    </div>
    
    <!-- Signup Form -->
    <div id="signupForm" class="hidden">
      <div class="modal-title">Get Started Free! 🚀</div>
      <div class="modal-subtitle">Create your account and start writing logs</div>
      
      <button class="btn-google">
        <img src="https://www.google.com/favicon.ico" alt="Google" onerror="this.src='https://fonts.gstatic.com/s/product/sans_tf/v3/6xKtdSZaM9iE8KbpRA_hK1QN.woff2'" />
        Continue with Google
      </button>
      
      <div class="divider">or continue with email</div>
      
      <form onsubmit="handleSignup(event)">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" placeholder="Your full name" required />
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" placeholder="you@example.com" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" placeholder="Create a password" required />
        </div>
        <button type="submit" class="btn-submit">Create Account</button>
      </form>
      
      <div class="modal-switch">
        Already have an account? <a onclick="showLogin()">Sign in</a>
      </div>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════ JS ══════════════════════════════════ -->
<script>
  // Modal functions
  const modal = document.getElementById('authModal');
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');

  function openModal() {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }

  function showSignup() {
    loginForm.classList.add('hidden');
    signupForm.classList.remove('hidden');
  }

  function showLogin() {
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
  }

  function handleLogin(e) {
    e.preventDefault();
    alert('Login functionality coming soon!');
  }

  function handleSignup(e) {
    e.preventDefault();
    alert('Signup functionality coming soon!');
  }

  // Close modal on overlay click
  modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
  });

  // Close modal on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
  });

  // Scroll reveal
  const reveals = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.12 });
  reveals.forEach(r => io.observe(r));

  // Smooth scroll for anchors
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  });
</script>

</body>
</html>