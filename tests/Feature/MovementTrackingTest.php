<?php

use App\Models\MovementExercise;
use App\Models\MovementFlag;
use App\Models\MovementLog;
use App\Models\MovementSession;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function makePatientUser(): User
{
    $user = User::factory()->create(['role' => 'patient']);
    $user->patient()->create([
        'medical_record_number' => '99999999',
        'bpjs_number'           => '0009999999999999',
        'bpjs_card'             => 'card.jpg',
        'address'               => 'Test Address',
    ]);
    return $user->fresh(['patient']);
}

function makeExercise(array $overrides = []): MovementExercise
{
    return MovementExercise::create(array_merge([
        'name'         => 'Elbow Flexion (Right)',
        'target_joint' => 'right_elbow',
        'thresholds'   => ['min_angle' => 30, 'max_angle' => 160, 'target_reps' => 10],
        'description'  => 'Test exercise',
    ], $overrides));
}

function makeSession(Patient $patient, MovementExercise $exercise, array $overrides = []): MovementSession
{
    return MovementSession::create(array_merge([
        'patient_id'           => $patient->id,
        'movement_exercise_id' => $exercise->id,
        'session_date'         => now()->toDateString(),
        'status'               => 'in_progress',
    ], $overrides));
}

// ---------------------------------------------------------------------------
// Migration / relationship sanity
// ---------------------------------------------------------------------------

it('creates movement_exercises, movement_sessions, movement_logs, movement_flags tables', function () {
    expect(\Illuminate\Support\Facades\Schema::hasTable('movement_exercises'))->toBeTrue()
        ->and(\Illuminate\Support\Facades\Schema::hasTable('movement_sessions'))->toBeTrue()
        ->and(\Illuminate\Support\Facades\Schema::hasTable('movement_logs'))->toBeTrue()
        ->and(\Illuminate\Support\Facades\Schema::hasTable('movement_flags'))->toBeTrue();
});

it('has correct relationships on MovementSession', function () {
    $user = makePatientUser();
    $exercise = makeExercise();
    $session = makeSession($user->patient, $exercise);

    expect($session->patient)->toBeInstanceOf(Patient::class)
        ->and($session->exercise)->toBeInstanceOf(MovementExercise::class)
        ->and($session->logs())->not->toBeNull()
        ->and($session->flags())->not->toBeNull();
});

it('computes completion_rate correctly', function () {
    $user = makePatientUser();
    $exercise = makeExercise(['thresholds' => ['min_angle' => 30, 'max_angle' => 160, 'target_reps' => 10]]);
    $session = makeSession($user->patient, $exercise, ['total_reps' => 5]);

    expect($session->completion_rate)->toBe(50.0);
});

it('returns 0 completion rate when target_reps is 0', function () {
    $user = makePatientUser();
    $exercise = makeExercise(['thresholds' => ['min_angle' => 0, 'max_angle' => 180, 'target_reps' => 0]]);
    $session = makeSession($user->patient, $exercise, ['total_reps' => 5]);

    expect($session->completion_rate)->toBe(0.0);
});

// ---------------------------------------------------------------------------
// logMovement: creates log and updates rep count
// ---------------------------------------------------------------------------

it('logMovement creates a movement log and increments total_reps', function () {
    $user = makePatientUser();
    $exercise = makeExercise();
    $session = makeSession($user->patient, $exercise);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Patient\MovementTracking::class, ['sessionId' => $session->id])
        ->call('logMovement', ['rep' => 1, 'angle' => 90])
        ->assertSet('repCount', 1);

    expect(MovementLog::where('movement_session_id', $session->id)->count())->toBe(1);
    expect(MovementLog::first()->joint_angle)->toBe(90.0);
    expect(MovementLog::first()->within_threshold)->toBeTrue();
});

it('logMovement marks log as out-of-threshold when angle is below min', function () {
    $user = makePatientUser();
    $exercise = makeExercise(['thresholds' => ['min_angle' => 30, 'max_angle' => 160, 'target_reps' => 10]]);
    $session = makeSession($user->patient, $exercise);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Patient\MovementTracking::class, ['sessionId' => $session->id])
        ->call('logMovement', ['rep' => 1, 'angle' => 10]);

    expect(MovementLog::first()->within_threshold)->toBeFalse();
});

it('logMovement clamps angle to 0-360', function () {
    $user = makePatientUser();
    $exercise = makeExercise();
    $session = makeSession($user->patient, $exercise);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Patient\MovementTracking::class, ['sessionId' => $session->id])
        ->call('logMovement', ['rep' => 1, 'angle' => 999]);

    expect(MovementLog::first()->joint_angle)->toBe(360.0);
});

// ---------------------------------------------------------------------------
// completeSession: computes aggregates and sets status
// ---------------------------------------------------------------------------

it('completeSession sets status to completed and computes aggregates', function () {
    $user = makePatientUser();
    $exercise = makeExercise();
    $session = makeSession($user->patient, $exercise);

    // Pre-create some logs
    MovementLog::create([
        'movement_session_id' => $session->id,
        'rep_number'          => 1,
        'joint_angle'         => 80.0,
        'within_threshold'    => true,
        'recorded_at'         => now(),
    ]);
    MovementLog::create([
        'movement_session_id' => $session->id,
        'rep_number'          => 2,
        'joint_angle'         => 120.0,
        'within_threshold'    => true,
        'recorded_at'         => now(),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Patient\MovementTracking::class, ['sessionId' => $session->id])
        ->call('completeSession')
        ->assertSet('status', 'done');

    $session->refresh();
    expect($session->status)->toBe('completed')
        ->and($session->total_reps)->toBe(2)
        ->and($session->avg_angle)->toBe(100.0)
        ->and($session->max_angle)->toBe(120.0);
});

// ---------------------------------------------------------------------------
// Authorization: non-owner patient cannot log to someone else's session
// ---------------------------------------------------------------------------

it('blocks a different patient from logging movement', function () {
    $ownerUser = makePatientUser();
    $attackerUser = makePatientUser();

    $exercise = makeExercise();
    $session = makeSession($ownerUser->patient, $exercise);

    Livewire::actingAs($attackerUser)
        ->test(\App\Livewire\Patient\MovementTracking::class, ['sessionId' => $session->id])
        ->assertForbidden();
});

// ---------------------------------------------------------------------------
// Authorization: non-assigned doctor cannot flag
// ---------------------------------------------------------------------------

it('blocks a non-doctor from flagging a movement session', function () {
    $patientUser = makePatientUser();
    $exercise = makeExercise();
    $session = makeSession($patientUser->patient, $exercise, ['status' => 'completed']);

    $randomPatient = makePatientUser();

    Livewire::actingAs($randomPatient)
        ->test(\App\Livewire\Admin\PatientRehabilitationExercise::class, [
            'id'            => $patientUser->patient->id,
            'rehabRoutineId' => 9999,
        ])
        ->call('openFlagModal', $session->id)
        ->assertForbidden();
});

it('allows a doctor to flag a completed session', function () {
    $patientUser = makePatientUser();
    $doctor = User::factory()->create(['role' => 'doctor']);
    $exercise = makeExercise();
    $session = makeSession($patientUser->patient, $exercise, ['status' => 'completed']);

    Livewire::actingAs($doctor)
        ->test(\App\Livewire\Admin\PatientRehabilitationExercise::class, [
            'id'            => $patientUser->patient->id,
            'rehabRoutineId' => 9999,
        ])
        ->set('activeFlagSessionId', $session->id)
        ->set('flagType', 'wrong_form')
        ->set('flagNote', 'Arm not straight')
        ->call('submitFlag');

    expect(MovementFlag::where('movement_session_id', $session->id)->count())->toBe(1);
    expect(MovementFlag::first()->flag_type)->toBe('wrong_form');
    expect($session->fresh()->status)->toBe('flagged');
});
