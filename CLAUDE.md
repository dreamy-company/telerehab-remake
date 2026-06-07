# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A telerehabilitation platform built on Laravel 12. It has two interfaces:
- **Web app** (Livewire) — for admin, doctor, therapist, and patient roles
- **REST API** (`/api/patient/*`) — for the mobile patient app, authenticated via Laravel Sanctum tokens

## Development Environment

The project runs via **Laravel Sail** (Docker). All commands should be run through Sail unless you're doing a first-time install.

### First-time setup
```bash
make setup
```
This installs Composer deps via Docker, copies `.env`, starts containers, generates key, runs migrations + seeders, links storage, and builds frontend assets.

### Common commands
```bash
make up          # Start Docker containers (http://localhost)
make down        # Stop containers
make dev         # Start containers + Vite HMR for frontend development
make build       # Build frontend assets (npm install + npm run build)
make logs        # Tail container logs
make destroy     # Tear down containers and volumes (destructive)
make shell       # Open shell inside app container
```

All Sail-specific commands follow the pattern: `./vendor/bin/sail <command>`

### Running tests (Pest)
```bash
./vendor/bin/sail artisan test                    # All tests
./vendor/bin/sail artisan test --filter=TestName  # Single test
```
Tests use a separate `testing` database (configured in `phpunit.xml`).

### Code formatting (Pint)
```bash
./vendor/bin/sail exec laravel.test ./vendor/bin/pint
```

### Database tooling
- phpMyAdmin is available at `http://localhost:8080`
- Run migrations: `./vendor/bin/sail artisan migrate`
- Run seeders: `./vendor/bin/sail artisan db:seed`

## Architecture

### Roles & Access Control

Four roles stored directly on the `users.role` column: `admin`, `doctor`, `therapist`, `patient`.

Access is enforced by `RoleMiddleware` (`app/Http/Middleware/RoleMiddleware.php`), registered as the `role` alias. Routes are grouped by role prefix:
- `/admin/*` → `role:admin`
- `/doctor/*` → `role:doctor`
- `/therapist/*` → `role:therapist`
- `/patient/*` (web) → `role:patient`
- `/api/patient/*` → `auth:sanctum` + `role:patient`

### Web Layer (Livewire)

All web UI is built with Livewire 3. Livewire components live in `app/Livewire/{Role}/` and render Blade views from `resources/views/livewire/{role}/`. Each component declares its layout via the `#[Layout('layouts.{role}')]` attribute. Navigation menus per role are driven by JSON files in `resources/views/layouts/partials/`.

### API Layer (Sanctum)

The mobile API lives under `routes/api.php`. Endpoints are namespaced under `App\Http\Controllers\Api\Patient\`. Authentication uses Sanctum token-based auth (not cookie/session). The `EnsureFrontendRequestsAreStateful` middleware is prepended to the API stack for SPA compatibility.

### Data Model

Core relationships:
- `User` → `Patient` (one-to-one via `user_id`; doctors relate via `doctor_id`)
- `Patient` → `RehabRoutine` → `Rehab` (via `rehabilitation_id`) → `RehabType`
- `RehabRoutine` → `RoutineRating` → `RatingResponse`
- `RehabRoutine` → `RoutineResult` (patient-uploaded exercise videos)
- `Patient` → `Meeting` (consultation scheduling between patient and doctor)
- `Patient` → `PatientPhoto`, `PatientScore`

### Soft Delete Pattern

Models use a dual soft-delete approach:
- Laravel's `SoftDeletes` trait (standard `deleted_at` column) on `Patient` and `Meeting`
- Custom `isDeleted` + `deleted_by` flags on most models (set in `boot()` hooks before deletion)

When deleting a parent model, child cascades are handled manually in `boot()` static hooks — not via database foreign key cascades.

### Movement Tracking Feature

Browser-side pose tracking via **Google MediaPipe Pose Landmarker** (WASM, loaded from CDN). No server-side ML.

Key models:
- `MovementExercise` — exercise definitions with `target_joint` string and `thresholds` JSON (`min_angle`, `max_angle`, `target_reps`)
- `MovementSession` — per-patient tracking session; links to `patients.id`, `movement_exercises.id`, and optionally `rehab_routines.id`
- `MovementLog` — one row per completed rep (`rep_number`, `joint_angle`, `within_threshold`)
- `MovementFlag` — clinical flags created by doctor/therapist (`flag_type`: `wrong_form` | `incomplete_range` | `missed_reps`)

Flow: Patient exercise page → pick exercise → `RehabilitationExercise::startTracking()` creates a `MovementSession` → redirect to `/patient/movement/{sessionId}/track` → `MovementTracking` Livewire component → `logMovement()` per rep → `completeSession()` aggregates + broadcasts `MovementSessionCompleted` event.

The tracking JS (`resources/js/tracking.js`) is loaded only on the tracking page via `@push('scripts') @vite(['resources/js/tracking.js']) @endpush`. It uses ESM imports from `cdn.skypack.dev/@mediapipe/tasks-vision`.

Doctor/therapist review: movement data panel and flag modal added to the existing `PatientRehabilitationExercise` Livewire component and its blade view.

### Real-time Events

`app/Events/UpdateEvent.php` is broadcast over Pusher (configured via `BROADCAST_CONNECTION` and Pusher env vars). The frontend subscribes via Laravel Echo (`resources/js/echo.js`).

### Email

`app/Mail/ScheduleEmail.php` — meeting schedule notifications  
`app/Mail/VerifyEmailMail.php` — custom email verification (User implements `MustVerifyEmail`)  
Email verification uses a custom middleware `EnsureEmailIsVerified` and custom Livewire flow under `app/Livewire/Auth/`.
