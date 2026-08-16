# AGENTS.md

Indonesian-language government service portal (Diskominsa Aceh Barat) for submitting IT service requests (websites, official email, TTE, cloud, helpdesk).

## Stack & commands

- Laravel 13 (PHP 8.3), Blade + Tailwind v4 (Vite), MySQL. No API, no auth scaffolding — hand-rolled auth.
- All routes live in a single `routes/web.php` with clear section comments (public / guest / auth / admin).
- Dev server: `composer run dev` (runs `artisan serve` + `queue:listen` + `pail` + `vite` concurrently). Requires node deps (`npm install`); `.npmrc` sets `ignore-scripts=true`.
- Build frontend: `npm run build` (prod) or `npm run dev` (watch). `resources/js/app.js` is empty; most interactivity is inline Blade JS.
- Tests: `composer test` (or `php artisan test`). Tests run on in-memory sqlite per `phpunit.xml`; only default ExampleTests exist.
- Formatting: `vendor/bin/pint`. No pint.json; default Laravel preset.

## Database gotchas

- **The app runs on MySQL, not MongoDB.** `.env` sets `DB_CONNECTION=mysql` (db `portal_layanan`). The `mongodb` connection in `config/database.php` and the `mongodb/laravel-mongodb` dependency exist but are unused by any app code — don't assume models are MongoDB models.
- Setup: `php artisan migrate` then `php artisan db:seed`. Seeder creates the only admin: `admin@acehbaratkab.go.id` / `password123`.
- `php artisan storage:link` is required for uploaded files (public disk). Symlink currently exists.
- `config/database.php` line 4 has a benign unused `use PDO;` that triggers a PHP warning in artisan output — don't "fix" it unless asked.

## Domain conventions

- UI text, validation messages, route names, and view dirs are in Indonesian (`pengajuan`, `pengaturan`, `bantuan`, `tte`, `cloud`, `website`). Keep new UI text in Indonesian.
- Users register with email restricted to `@acehbaratkab.go.id` (AuthController). Roles are `enum('admin','user')` in the DB, checked via string equality in `IsAdmin` middleware and `AuthController`.
- Service types and their ticket-code prefixes are centralized in `Pengajuan::booted()` (app/Models/Pengajuan.php:44): Pembuatan Website=WEB, Pembuatan Email Resmi=EML, Layanan TTE=TTE, Cloud Government=CLD, Pusat Bantuan/Reset Password=HLP. Keep new services in sync there.
- Statuses: `Pending`, `Proses`, `Selesai`, `Ditolak` (standardized, validated via `Rule::in` in AdminPengajuanController `updateProgres`). The admin "Proses" filter filters `where('status', 'Proses')` (routes/web.php and AdminPengajuanController). Badge colors: Pending=amber, Proses=blue, Selesai=emerald, Ditolak=rose.
- Submission payloads are JSON columns on `pengajuan`: `data_pengajuan`, `logs`, `pesan` (cast to array). Controllers defensively handle both array and JSON-string forms (`is_array(...) ? ... : json_decode(...)`) — follow that pattern in new code.
- The `PengajuanLog` model / `pengajuan_logs` table exist but are unused; status history is stored in the `logs` JSON column instead.
- File uploads: user submissions go to `dokumen_pengajuan/<service>/`, admin result files to `dokumen_hasil/`, all on the public disk.
