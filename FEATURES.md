# TeleRehab — Feature Documentation

## Upgrade Brief

This document covers the features delivered across the recent development phase of the TeleRehab platform. The project is a telerehabilitation system built to connect patients undergoing physical rehabilitation with their doctors and therapists remotely.

### What Changed

| # | Commit | Description |
|---|--------|-------------|
| 1 | `412a4b9` | Tracking showcase section on welcome page with animated demo and interaction logic |
| 2 | `1989b23` | Country and telephone fields made optional in registration; intl-tel-input integration updated |
| 3 | `3abeb99` | Telephone validation rules updated; welcome page enhanced with key features section |
| 4 | `0370af3` | Code refactor for readability and maintainability across Livewire components |
| 5 | `3954513` | Movement tracking system, country foreign key on users, and complete admin CRUD |
| 6 | `869c093` | Patient REST API for the mobile app (Laravel Sanctum token authentication) |
| 7 | `dbecb6d` | Route middleware updated to enforce stricter role-based access |
| 8 | `b9bfd7c` | Foreign key fix: `meetings.patient_id` correctly references `patients.id` |
| 9 | `63fe2e3` | Schedule email notification sent to patient on appointment creation |

---

## Tech Stack

### Backend
| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | ^12.0 |
| PHP | PHP | ^8.2 |
| Reactive UI | Livewire | ^3.0 |
| API Auth | Laravel Sanctum | ^4.0 |
| Real-time | Pusher PHP Server | ^7.2 |
| Dev tooling | Laravel Sail (Docker), Pint, Pest | — |
| Queue driver | Database | — |
| Mailing | Laravel Mailer (SMTP) | — |

### Frontend
| Layer | Technology | Version |
|-------|-----------|---------|
| Bundler | Vite | ^7.0 |
| CSS Framework | TailwindCSS | ^4.1 |
| UI Components | DaisyUI | ^5.5 |
| WebSocket client | Laravel Echo + Pusher JS | ^2.2 / ^8.4 |
| Pose detection | Google MediaPipe Tasks-Vision (WASM) | ^0.10 |
| Phone input | intl-tel-input | ^25.14 |
| Select UI | Tom Select, Nice Select 2 | ^2.4 |
| Notifications | Toastify JS | ^1.12 |

### Infrastructure
- **Docker** via Laravel Sail (app, MySQL, Redis, phpMyAdmin, Mailpit)
- **Storage**: Laravel public disk for patient photos and BPJS documents
- **Database**: MySQL (separate `testing` database for Pest tests)

---

## Feature Documentation

### 1. Movement Tracking

The most significant new feature. Patients perform their rehabilitation exercises on-screen while the browser tracks their joint movements in real-time using Google MediaPipe — no server-side ML, no camera upload.

#### How it works

1. Admin creates a **Movement Exercise** with a target joint and angle thresholds (`min_angle`, `max_angle`, `target_reps`).
2. Patient selects a rehab routine and starts a tracking session.
3. The `MovementTracking` Livewire component creates a `MovementSession` record and redirects to `/patient/movement/{sessionId}/track`.
4. The tracking page loads `resources/js/tracking.js`, which:
   - Initializes MediaPipe PoseLandmarker (33-point skeleton, GPU delegate).
   - Tracks the configured joint angle frame-by-frame.
   - Runs a 45-frame position validation (~1.5 s) before counting reps.
   - Counts reps via a state machine (`up` / `down` stage transitions).
   - Fires audio cues (Web Audio API tones + Web Speech API Indonesian voice guidance).
   - Displays real-time angle, direction arrows, and a rep progress bar on a `<canvas>` overlay.
   - Calls `Livewire.dispatch('logMovement', {...})` per completed rep and `completeSession()` at the end.
5. On completion the session is finalized, aggregated stats (avg angle, max angle) are saved, and a `MovementSessionCompleted` event is broadcast.

#### Supported joints

`right_elbow`, `left_elbow`, `right_shoulder`, `left_shoulder`, `right_knee`, `left_knee`, `right_hip`, `left_hip`

#### Database tables

| Table | Purpose |
|-------|---------|
| `movement_exercises` | Exercise definitions: name, target_joint, thresholds JSON |
| `movement_sessions` | One session per patient exercise attempt; stores status, total_reps, avg/max angle |
| `movement_logs` | One row per completed rep: rep_number, joint_angle, within_threshold, landmark_snapshot |
| `movement_flags` | Clinical flags doctors/therapists attach to a session (wrong_form, incomplete_range, missed_reps) |

#### Doctor / Therapist review

Movement data panel and a flag modal are embedded in the existing `PatientRehabilitationExercise` Livewire view so clinicians can review session stats and add flags without leaving the patient record.

#### Tutorial system

A 3-step in-app tutorial plays the first time a patient reaches the tracking page. State is persisted in `localStorage` so it only runs once.

---

### 2. Patient REST API (Mobile)

A Sanctum token-based API under `/api/patient/*` designed for the companion mobile application.

#### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/patient/login` | Authenticate with email + password; returns a Sanctum token |
| `POST` | `/api/patient/register` | Register with medical info and optional file uploads (BPJS, photos) |
| `POST` | `/api/patient/logout` | Revoke the current token |

#### Protected routes (`auth:sanctum` + `role:patient`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/patient/profile` | User and patient profile with uploaded photos |
| `GET` | `/api/patient/dashboard` | Dashboard summary data |
| `POST` | `/api/patient/dashboard/request-consultation` | Request a consultation with the assigned doctor |
| `GET` | `/api/patient/rehabilitations` | List of assigned rehab routines |
| `GET` | `/api/patient/rehabilitations/{id}/exercise` | Exercise details for a routine |
| `POST` | `/api/patient/rehabilitations/{id}/exercise/upload` | Upload an exercise result video |

---

### 3. Admin CRUD

Full create/read/update/delete management for all master data, implemented as paired Livewire components (`{Entity}` list + `{Entity}Form` modal).

| Entity | Component | Notes |
|--------|-----------|-------|
| Users | `Admin/User`, `Admin/UserForm` | All roles |
| Patients | `Admin/Patient`, `Admin/PatientForm` | Soft delete with audit trail |
| Rehab Phases | `Admin/RehabType`, `Admin/RehabTypeForm` | Cascade-deletes related Rehabs |
| Rehabilitations | `Admin/Rehabilitation`, `Admin/RehabilitationForm` | Linked to rehab phases |
| Movement Exercises | `Admin/MovementExercise`, `Admin/MovementExerciseForm` | Joint + threshold configuration |
| Countries | `Admin/Country`, `Admin/CountryForm` | Lookup table for user registration |

Soft delete is implemented via both Laravel's `SoftDeletes` trait (`deleted_at`) and a custom `isDeleted` / `deleted_by` flag for audit compliance. Child cascade deletes are handled in Eloquent `boot()` hooks.

---

### 4. Country Field on Users

A `country_id` foreign key was added to the `users` table, linking to the `countries` lookup table. This surfaces in:

- **Registration** — country selector (optional) with Nice Select 2 styling.
- **Admin user management** — editable country field in `UserForm`.
- **Patient profile API** — included in the profile response.

---

### 5. Registration Improvements

The 3-step registration flow was updated:

- **Country** and **telephone** fields are now optional (previously required).
- **intl-tel-input** integration was reworked: the raw E.164-formatted number is stored; validation uses `digits_between:7,15` with a numeric check.
- **Step 3** (supporting documents: BPJS card, condition photos) remains optional at the field level; files are uploaded to `bpjs/` and `patient_photos/` on the public disk.

---

### 6. Welcome Page Enhancements

Two new sections added to the public landing page:

- **Key Features section** — concise feature highlights (telerehabilitation, progress monitoring, movement tracking).
- **Tracking Showcase section** — animated interactive demo illustrating how the movement tracking UI looks and works, with interaction logic (hover/click states).

---

### 7. Schedule Email Notifications

When a doctor creates or updates a meeting/schedule, `app/Mail/ScheduleEmail.php` is dispatched and the patient receives an email rendered from the `livewire.doctor.schedule-email` Blade markdown template with appointment details.

---

### 8. Real-time Broadcasting

Two broadcast events are in use:

| Event | Channel | Trigger | Listeners |
|-------|---------|---------|-----------|
| `UpdateEvent` | `update-channel` | Schedule created/updated; consultation requested | Doctor Dashboard (Echo `#[On()]`) |
| `MovementSessionCompleted` | `movement-tracking` | Patient finishes a tracking session | Doctor/Therapist review panel |

Frontend is wired via `resources/js/echo.js` (Laravel Echo + Pusher JS). Switch to live broadcasting by setting `BROADCAST_CONNECTION=pusher` and the Pusher env vars.

---

## Role Access Summary

| Area | Admin | Doctor | Therapist | Patient |
|------|:-----:|:------:|:---------:|:-------:|
| Admin dashboard & CRUD | ✓ | — | — | — |
| Patient management | ✓ | ✓ | view | — |
| Rehabilitation assignment | ✓ | ✓ | view | — |
| Movement exercise config | ✓ | — | — | — |
| Consultation scheduling | — | ✓ | — | request |
| Movement tracking | — | — | — | ✓ |
| Mobile API | — | — | — | ✓ |

Access is enforced by `RoleMiddleware` registered as the `role` alias on all route groups.
