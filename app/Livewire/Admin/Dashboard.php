<?php

namespace App\Livewire\Admin;

use App\Models\Meeting;
use App\Models\MovementSession;
use App\Models\Patient;
use App\Models\Rehab;
use App\Models\RehabRoutine;
use App\Models\RehabType;
use App\Models\RoutineResult;
use App\Models\Schedule;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            // User stats
            'totalUsers'      => User::where('role', '!=', 'patient')->count(),
            'totalDoctors'    => User::where('role', 'doctor')->count(),
            'totalTherapists' => User::where('role', 'therapist')->count(),
            'totalAdmins'     => User::where('role', 'admin')->count(),

            // Patient & clinical
            'totalPatients'        => Patient::count(),
            'totalRehabilitations' => Rehab::count(),
            'totalRehabTypes'      => RehabType::count(),
            'totalRehabRoutines'   => RehabRoutine::count(),
            'activeRoutines'       => RehabRoutine::where('status', 'process')->count(),
            'completedRoutines'    => RehabRoutine::where('status', 'complete')->count(),

            // Meetings & schedules
            'totalMeetings'        => Meeting::count(),
            'pendingMeetings'      => Meeting::where('status', 'pending')->count(),
            'totalSchedules'       => Schedule::count(),

            // Uploads & tracking
            'totalRoutineResults'  => RoutineResult::count(),
            'totalMovingSessions'  => MovementSession::count(),
            'completedSessions'    => MovementSession::where('status', 'completed')->count(),

            // Recent data
            'recentPatients' => Patient::with('user')
                ->latest()
                ->take(5)
                ->get(),

            'recentMeetings' => Meeting::with(['doctor', 'patient.user'])
                ->latest()
                ->take(5)
                ->get(),

            'recentRoutines' => RehabRoutine::with(['patient.user', 'doctor', 'rehabilitation'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
