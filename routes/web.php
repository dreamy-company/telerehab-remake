<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Patient;
use App\Livewire\Admin\PatientForm;
use App\Livewire\Admin\PatientRehabilitation as AdminPatientRehabilitation;
use App\Livewire\Admin\Rehabilitation;
use App\Livewire\Admin\RehabilitationForm;
use App\Livewire\Admin\RehabType as AdminRehabType;
use App\Livewire\Admin\RehabTypeForm;
use App\Livewire\Admin\User;
use App\Livewire\Admin\UserForm;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Doctor\Consultation;
use App\Livewire\Doctor\Dashboard as DoctorDashboard;
use App\Livewire\Doctor\MeetingSchedule;
use App\Livewire\Doctor\MeetingScheduleConsultation;
use App\Livewire\Patient\Dashboard as PatientDashboard;
use App\Livewire\Patient\Rehabilitation as PatientRehabilitation;
use App\Livewire\Patient\RehabilitationExercise;
use App\Livewire\Admin\PatientRehabilitationExercise;
use App\Livewire\Auth\EmailVerifed;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Therapist\Dashboard as TherapistDashboard;
use App\Livewire\Welcome;
use App\Models\RehabType;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', action: Welcome::class)->name('welcome');

// Authentication Routes
Route::middleware(['not-authenticated'])->group(function () {
    Route::get('/auth/register', Register::class)->name('auth.register');
    Route::get('/auth/login', Login::class)->name('auth.login');
});

// Email Verification Notice
Route::get('/auth/email/{email}/verify', VerifyEmail::class)->middleware('guest')->name(name: 'auth.verification');

Route::get('/email/verify/{id}/{hash}', EmailVerifed::class
)->middleware(['not-authenticated'])->name('verification.verify');
// Logout
Route::get('/auth/logout', [Login::class, 'logout'])->name('auth.logout');


// Profile
Route::get('/{role}/{id}/profile', Profile::class)->middleware(['auth', 'verified'])->name('auth.profile');
// Redirect Dashboard
Route::get('/dashboard', function () {
    $checkUser = Auth::user();

    if ($checkUser->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else if ($checkUser->role === 'doctor') {
        return redirect()->route('doctor.dashboard');
    } else if ($checkUser->role === 'patient') {
        return redirect()->route('patient.dashboard');
    } else if ($checkUser->role === 'therapist') {
        return redirect()->route('therapist.dashboard');
    }
})->name('dashboard');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // MASTERDATA GROUP
    Route::get('/masterdata/user', User::class)->name('user');
    Route::get('/masterdata/user/create', UserForm::class)->name('user.create');
    Route::get('/masterdata/user/{id}/edit', UserForm::class)->name('user.edit');
    Route::get('/masterdata/patient', Patient::class)->name('patient');
    Route::get('/masterdata/patient/create', PatientForm::class)->name('patient.create');
    Route::get('/masterdata/patient/{id}/edit', PatientForm::class)->name('patient.edit');
    Route::get('/patient/{id}/rehabilitation', AdminPatientRehabilitation::class)->name('patient.rehabilitation');
    Route::get('/patient/{id}/rehabilitation/{rehabRoutineId}/exercise', PatientRehabilitationExercise::class)->name('patient.rehabilitation.exercise');
    Route::get('/masterdata/rehabilitation-phase', AdminRehabType::class)->name('rehabilitation-phase');
    Route::get('/masterdata/rehabilitation-phase/create', RehabTypeForm::class)->name('rehabilitation-phase.create');
    Route::get('/masterdata/rehabilitation-phase/{id}/edit', RehabTypeForm::class)->name('rehabilitation-phase.edit');
    Route::get('/masterdata/rehabilitation', Rehabilitation::class)->name('rehabilitation');
    Route::get('/masterdata/rehabilitation/create', RehabilitationForm::class)->name('rehabilitation.create');
    Route::get('/masterdata/rehabilitation/{id}/edit', RehabilitationForm::class)->name('rehabilitation.edit');
});

// Doctor
Route::middleware(['auth', 'verified', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', DoctorDashboard::class)->name('dashboard');

    // PATIENT
    Route::get('/patient', Patient::class)->name('patient');
    Route::get('/patient/create', PatientForm::class)->name('patient.create');
    Route::get('/patient/{id}/edit', PatientForm::class)->name(name: 'patient.edit');
    Route::get('/patient/{id}/rehabilitation', AdminPatientRehabilitation::class)->name('patient.rehabilitation');
    Route::get('/patient/{id}/rehabilitation/{rehabRoutineId}/exercise', PatientRehabilitationExercise::class)->name('patient.rehabilitation.exercise');

    // CONSULTATION
    Route::get('/consultation', Consultation::class)->name('consultation');

    // MEETING SCHEDULE
    Route::get('/meeting-schedule', MeetingSchedule::class)->name('meeting-schedule');
    Route::get('/meeting-schedule/{id}/consultation', MeetingScheduleConsultation::class)->name('meeting-schedule.consultation');
});


// Patient
Route::middleware(['auth', 'verified', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', PatientDashboard::class)->name('dashboard');
    Route::get('/rehabilitation', PatientRehabilitation::class)->name('rehabilitation');
    Route::get('/rehabilitation/{id}/exercise', RehabilitationExercise::class)->name('rehabilitation.exercise');
});


// Therapist
Route::middleware(['auth', 'verified', 'role:therapist'])->prefix('therapist')->name('therapist.')->group(function () {
    Route::get('/dashboard', TherapistDashboard::class)->name('dashboard');

    // PATIENT
    Route::get('/patient', Patient::class)->name('patient');
    Route::get('/patient/create', PatientForm::class)->name('patient.create');
    Route::get('/patient/{id}/edit', PatientForm::class)->name(name: 'patient.edit');
    Route::get('/patient/{id}/rehabilitation', AdminPatientRehabilitation::class)->name('patient.rehabilitation');
    Route::get('/patient/{id}/rehabilitation/{rehabRoutineId}/exercise', PatientRehabilitationExercise::class)->name('patient.rehabilitation.exercise');
});
require __DIR__ . '/auth.php';
