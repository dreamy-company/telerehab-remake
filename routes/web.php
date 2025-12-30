<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Patient;
use App\Livewire\Admin\PatientForm;
use App\Livewire\Admin\Rehabilitation;
use App\Livewire\Admin\RehabilitationForm;
use App\Livewire\Admin\RehabType as AdminRehabType;
use App\Livewire\Admin\RehabTypeForm;
use App\Livewire\Admin\User;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Doctor\Dashboard as DoctorDashboard;
use App\Livewire\Welcome;
use App\Models\RehabType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', action: Welcome::class);

Route::get('/auth/register', Register::class)->name('auth.register');
Route::get('/auth/login', Login::class)->name('auth.login');
Route::get('/auth/logout', [Login::class, 'logout'])->name('auth.logout');

// Redirect Dashboard
Route::get('/dashboard', function () {
    $checkUser = Auth::user();
    if ($checkUser->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
})->name('dashboard');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // MASTERDATA GROUP
    Route::get('/masterdata/user', User::class)->name('user');
    Route::get('/masterdata/patient', Patient::class)->name('patient');
    Route::get('/masterdata/patient/create', PatientForm::class)->name('patient.create');
    Route::get('/masterdata/patient/{id}/edit', PatientForm::class)->name('patient.edit');
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
});

require __DIR__ . '/auth.php';
