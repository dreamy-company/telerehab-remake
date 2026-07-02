# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Telerehabilitation platform built on **Laravel 12** + **Livewire 3** (server-rendered SPA-like UI) with **TailwindCSS 4 / daisyUI** for styling and **Vite** for assets. There is also a **JSON API** (`routes/api.php`) consumed by a separate patient mobile app, authenticated with **Sanctum** tokens. The web app is multi-tenant by *role*: `admin`, `doctor`, `patient`, `therapist`.

## Commands

Two workflows coexist — Docker (Sail) via the `Makefile`, and native PHP via Composer scripts. The `Makefile` is written for a MySQL/Sail stack; `.env.example` ships with SQLite. Match your `.env` to the workflow you choose.

### Docker / Sail (Makefile)
- `make setup` — full first-time bootstrap (composer install, env, containers, DB create, `migrate --seed`, storage link, npm build). App serves at `http://localhost`.
- `make up` / `make down` / `make destroy` — start / stop / tear down (`destroy` also wipes the DB volume).
- `make dev` — Vite hot-reload. `make build` — production asset build.
- `make shell` / `make logs` — container shell / tailed logs.
- `make db-create name=foo` / `make db-drop name=foo` — DB management.

### Native PHP (Composer scripts)
- `composer run dev` — runs server + queue listener + `pail` logs + Vite concurrently (one command, all dev processes).
- `composer run test` — clears config then runs the test suite.
- `composer run setup` — install deps, env, key, migrate, npm build.

### Tests (Pest)
- `php artisan test` — run all tests.
- `php artisan test --filter=SomeTest` — run a single test/group.
- `php artisan test tests/Feature/Auth/LoginTest.php` — run one file.
- Tests use an in-memory SQLite DB (see `phpunit.xml`); `RefreshDatabase` runs migrations per test.

### Lint
- `./vendor/bin/pint` — Laravel Pint code formatter.

## Architecture

### Routing & access control
- `routes/web.php` maps URLs **directly to Livewire components** (e.g. `Route::get('/dashboard', Dashboard::class)`), not to controllers. Controllers exist only for auth (`routes/auth.php`, Breeze) and the mobile API.
- Route groups are organized per role with a prefix + name + the custom `role:` middleware: `->middleware(['auth','verified','role:admin'])->prefix('admin')->name('admin.')`.
- `role` middleware (`app/Http/Middleware/RoleMiddleware.php`) is variadic — `role:doctor,admin` allows multiple. It checks `Auth::user()->role`.
- `/dashboard` is a dispatcher that redirects to the role-specific dashboard route based on `$user->role`.
- Other custom middleware: `not-authenticated` (redirect logged-in users away from login/register), `verified` (custom `EnsureEmailIsVerified`).
- Middleware aliases are registered in `bootstrap/app.php` (Laravel 12 has no `Http/Kernel.php`).

### Livewire as the primary layer
- `app/Livewire/{Admin,Doctor,Patient,Therapist,Auth}/` — full-page components are the controllers/view-models of the app. Views live in `resources/views/livewire/...`.
- Note several role areas **reuse the same component class** — e.g. `Admin\Patient`, `Admin\PatientForm`, and `Admin\PatientRehabilitationExercise` are mounted under admin, doctor, and therapist routes. Changes to these components affect all three roles.

### Mobile API (`routes/api.php`)
- All under `prefix('patient')`. Public: `/login`, `/register`. Protected: `middleware(['auth:sanctum','role:patient'])`.
- Controllers in `app/Http/Controllers/Api/Patient/`. They return JSON directly with explicit status codes (see `AuthController` patterns: 404 unregistered, 403 unverified/non-patient, 401 bad password). Tokens issued via `createToken('patient-mobile')`.

### Domain model
Core chain: `User` (has a `role`) → `Patient` (profile, belongs to a `doctor` User via `doctor_id`) → `RehabRoutine` (assignment of a `Rehab` exercise to a patient) → `RoutineResult` / `RoutineRating` / `RatingResponse` (patient submissions + feedback). `Rehab` belongs to `RehabType` (rehab phase). `Meeting` + `Schedule` handle doctor consultations.

**Soft deletes & cascading are model-driven**, not DB-level. Many models override `boot()` to:
- Stamp `deleted_by = Auth::id()` on the `deleting` event, and
- Manually cascade soft-deletes to children (e.g. deleting a `Patient` deletes its photos/scores/rehabRoutines; deleting a `RehabType` cascades to `Rehab` → `RehabRoutine`).

When adding relationships or new child models, replicate this cascade in the parent's `boot()` — relying on DB foreign keys alone will not soft-delete children. Models also carry an `isDeleted` flag column alongside Laravel `SoftDeletes`.

### Real-time / broadcasting
- `app/Events/UpdateEvent.php` implements `ShouldBroadcastNow` on a public `update-channel` (event name `update-event`). Frontend uses `laravel-echo` + `pusher-js`. `BROADCAST_CONNECTION` defaults to `log` in `.env.example`; configure Pusher creds to enable real-time.

### Mail
- `app/Mail/` — `VerifyEmailMail` (custom email verification) and `ScheduleEmail` (consultation scheduling). `MAIL_MAILER=log` by default — emails are written to the log, not sent.

## Conventions
- Frontend libs are wired through Vite/npm (not CDN): `tom-select`/`nice-select2` (selects), `intl-tel-input` (phone), `toastify-js` (toasts).
- Code comments and the `Makefile` are written in Indonesian; follow the surrounding language when editing those files.
