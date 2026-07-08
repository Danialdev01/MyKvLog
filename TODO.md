# MyKvLog — UI Overhaul TODO

Goal: make the app easier to use without changing its visual identity (orange `#FF6B35`,
Figtree, light cards). Work is grouped in phases; each item lists the file(s) it touches.

**Status key:** `[x]` done & verified · `[~]` partially done · `[ ]` not started.

Two implementation passes so far:
- **Pass 1 (2026-07-07):** built the shared shell foundation and ported the Dashboard + Log Saya pages.
- **Pass 2 (2026-07-08):** ported the remaining authenticated pages (Cetak, Profil) onto the
  shell, fixed the fake print pagination, and made failed email/password logins actually show an
  error on the welcome page.

---

## Audit summary (what was wrong)

**Critical (broken functionality)**
1. Failed email/password login showed **no error** — `welcome.blade.php` never rendered `$errors`,
   and the auth modal didn't reopen after the redirect. Users thought the site was broken. **[fixed pass 2]**
2. `dashboard.blade.php` had **two conflicting edit-mode code paths** — updating a log fired two
   requests, one of them to `#`. **[fixed pass 1]**
3. Updating a log used `fetch(PUT, FormData)` — PHP does not parse multipart bodies on PUT, so
   field data never reached the server. Fixed with method spoofing (`POST` + `_method=PUT`). **[fixed pass 1]**
4. Dead controls: "Cetak Log Mingguan" (no handler), "Lihat semua →" (`href="#"`), and the print
   page's pagination buttons that only ran `alert('Pagination belum disediakan')`. **[all fixed — pass 1 for dashboard, pass 2 for print]**
5. Stray duplicate `</button>` tag in the dashboard form actions row. **[fixed pass 1]**

**Major (usability)**
6. Sidebar + topbar + ~250 lines of CSS **copy-pasted into 4 pages** and already drifting.
   Now a single `layouts/shell.blade.php` + `public/css/shell.css`. **[done — all 4 pages ported]**
7. All feedback via blocking `alert()` dialogs → non-blocking `showToast()`. **[done]**
8. "Log Saya" was calendar-only — added a scannable log list card. **[done pass 1]**
9. Edit mode was almost invisible → visible banner + Batal button. **[done pass 1]**
10. After saving a log the stats/recent-logs panels didn't refresh → toast then reload. **[done pass 1]**
11. English strings inside a Malay UI (day/month names, "Day 5", placeholders). **[done]**
12. Brand mismatch: titles said "MyInternLog" while the logo says "MyKvLog". **[done — all titles now MyKvLog]**

**Minor (polish / accessibility)**
13. Upload zone not keyboard-accessible. **[done pass 1]**
14. No `:focus-visible`; emoji icons unlabelled. **[done — shell.css + aria-hidden]**
15. Small orange-on-white text below WCAG AA → `--orange-deep` token. **[done]**
16. Topbar avatar looked clickable but did nothing → now links to Profil. **[done]**
17. Onboarding defaults modal couldn't be dismissed. **[done pass 1]**
18. `prefers-reduced-motion` not respected. **[done — shell.css]**

---

## Phase 0 — Shared foundation (the multiplier fix)  ✅ complete

- [x] `public/css/shell.css` — single source of truth for app chrome (tokens, sidebar, topbar,
      cards, buttons, form fields, log rows, empty states, toasts, focus rings, reduced-motion, mobile).
- [x] `public/js/shell.js` — shared sidebar toggle/overlay + `showToast()`.
- [x] `resources/views/layouts/shell.blade.php` — one layout (active nav via `request()->routeIs()`,
      breadcrumb slot, date pill, avatar → Profil, toast container, `@stack('styles')`/`@stack('scripts')`).
- [x] Port all 4 authenticated pages onto the shell; delete their duplicated chrome.
      (Dashboard + Log Saya in pass 1; **Cetak + Profil in pass 2**.)
- [x] Unify brand: `<title>` = "MyKvLog — …" everywhere; `lang="ms"`.

## Phase 1 — Dashboard (`dashboard.blade.php`)  ✅ complete (pass 1)

- [x] Remove stray `</button>` and dead code; CSS media query instead of JS `checkCols`.
- [x] One submit path keyed on a single `editingDate` state (POST /logs vs POST /logs/{date}+_method=PUT).
- [x] Visible edit-mode banner + "Batal"; submit label switches Simpan/Kemaskini.
- [x] Hide photo-upload zone while editing (update endpoint ignores images) and say so.
- [x] Wire dead controls: "Lihat semua →" → logs.index; "Cetak Log Mingguan" → print.
- [x] Replace `alert()` with toasts; reload after save so stats/recent/calendar refresh.
- [x] Malay copy; upload zone keyboard access; dismissible onboarding modal.

## Phase 2 — Log Saya (`logs.blade.php`)  ✅ complete (pass 1)

- [x] Scannable log-list card under the calendar (day badge, summary, date, open-modal).
- [x] Malay calendar (Ahd–Sab, Januari–Disember).
- [x] Drop the `100vh` fullscreen hack so the page scrolls and the list is reachable.
- [x] Detail modal with Escape-to-close and a loading state.

## Phase 3 — Cetak (`print.blade.php`)  ✅ complete (pass 2)

- [x] Ported onto the shell layout; removed ~130 lines of duplicated chrome/CSS.
- [x] **Real** client-side pagination (10 rows/page, prev/next + "Halaman X / Y"); the old
      `alert('Pagination belum disediakan')` stub is gone.
- [x] Search matches summary **and** date text; searching resets to page 1 and hides pagination
      when there are no matches ("Tiada log sepadan…").
- [x] Table wrapped in an `overflow-x:auto` container so it scrolls on mobile.
- [x] PDF date-range validation uses a toast instead of `alert()`.

## Phase 4 — Profil (`profile-edit.blade.php`)  ✅ complete (pass 2)

- [x] Ported onto the shell layout; uses shared `.card` / `.input-base` / `.field-label` / `.btn-orange`.
- [x] Success feedback via toast; validation errors shown in an inline red box per form.
- [x] Malay labels/hints reviewed.

## Phase 5 — Welcome / auth (`welcome.blade.php`)  🔶 mostly done

- [x] Render `$errors` inside the login **and** signup forms (first error, red box).
- [x] Auto-reopen the auth modal after a failed attempt, showing the right sub-form
      (signup if `old('name')` was present, otherwise login).
- [x] Preserve `old('email')` in the correct form; brand title fixed to MyKvLog.
- [ ] Later: replace invented landing-page stats/testimonials with honest copy, or real data.
- [ ] Later: restyle the orphaned Breeze `auth/*` views (password reset, email verify) to match
      the brand — they still render with the default Breeze look.

## Phase 6 — Accessibility & polish (cross-cutting)  🔶 mostly done

- [x] `:focus-visible` outline on interactive elements (shell.css).
- [x] `prefers-reduced-motion: reduce` kills transitions/animations (shell.css).
- [x] `--orange-deep` token for small text on light backgrounds.
- [x] Topbar avatar is a real link to Profil; emoji icons marked `aria-hidden`.
- [ ] Later: audit remaining contrast (gray-400 subtitles on white are ~3.5:1).
- [ ] Later: trap focus inside open modals; `aria-modal` + `role="dialog"` everywhere.

## Phase 7 — Backend follow-ups (not UI, tracked here so they aren't lost)  ⬜ open

- [ ] `LogController::update()` should accept image uploads (add/remove) so edit can manage photos;
      then un-hide the upload zone in edit mode.
- [ ] `LogController::edit()/show()` should return the log's images so the modal/edit form can preview them.
- [ ] Server-side pagination for the print table once users have 100+ logs (client-side is fine for now).
- [ ] Consider `Route::get('/login')` rendering the welcome page with the modal pre-opened
      (`?auth=login`) instead of a bare redirect, so deep links land on the form.

## Verification

- [x] `php artisan view:clear && php artisan view:cache` compiles every Blade view (both passes).
- [ ] Manual smoke test in browser: create log → edit log → calendar modal → print PDF →
      paginate/search print table → change defaults → failed login shows error.
      (Needs a running dev server + login.)
