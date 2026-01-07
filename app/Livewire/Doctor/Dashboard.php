<?php

namespace App\Livewire\Doctor;

use App\Models\Meeting;
use App\Models\Patient;
use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{

    public $search = '', $patientData = null, $date, $time, $category, $location;

    public function detail($idPatient)
    {
        $this->patientData = Patient::find($idPatient);
    }

    public function setConsultation($id)
    {
        $this->patientData = Meeting::with('patient.user')->find($id);
    }

    public function saveSchedule()
    {

        $this->validate([
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
        ]);

        $this->patientData->update([
            'doctor_id' => Auth::user()->id,
            'date' => $this->date,
            'time' => $this->time,
            'category' => 'Offline',
            'meeting_category' => $this->category,
            'location' => $this->location,
        ]);


        $this->patientData = null;
        return redirect()->route('doctor.dashboard')->with('success-alert', 'Consultation schedule saved successfully.');
    }
    public function render()
    {
        return view('livewire.doctor.dashboard', [
            'totalPatient' => Patient::whereHas('meetings', function ($q) {
                $q->where('doctor_id', Auth::user()->id);
            })->count(),
            'totalConsultationRequest' => Meeting::where('status', 'pending')->count(),
            'totalRehabilitationPhases' => Patient::whereHas('rehabRoutines', function ($q) {
                $q->where('doctor_id', Auth::user()->id)->where('status', 'process');
            })->count(),
            'dataConsultationRequest' => Meeting::when($this->search, function ($query) {
                $query->whereHas('patient.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
                ->where('status', 'pending')
                ->whereNull('doctor_id')
                ->get(),
            'dataMeetingSchedule' => Meeting::when($this->search, function ($query) {
                $query->whereHas('patient.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
                ->with('patient')
                ->where('status', 'pending')
                ->where('doctor_id', Auth::user()->id)
                ->where('status', 'pending')
                ->get(),
            'dataPatients' => Patient::query()
                // 1. Logika Pencarian (Wajib dibungkus dalam where closure agar OR tidak merusak filter role)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('medical_record_number', 'like', "%{$this->search}%")
                            ->orWhere('bpjs_number', 'like', "%{$this->search}%")
                            ->orWhere('address', 'like', "%{$this->search}%")
                            ->orWhereHas('user', function ($sq) {
                                $sq->where('name', 'like', "%{$this->search}%");
                            });
                    });
                })
                // 2. Filter Role Pasien
                ->whereHas('user', function ($query) {
                    $query->where('role', 'patient');
                })
                // 3. Filter Doctor (jika user adalah doctor)
                ->when(Auth::user()->role === 'doctor', function ($query) {
                    $query->whereHas('rehabRoutines', function ($q) {
                        $q->where('doctor_id', Auth::user()->id);
                    });
                })
                // 4. Menghitung Jumlah Routine pada Rehabilitasi Aktif
                ->withCount([
                    'rehabRoutines as active_routines_count' => function ($query) {
                        $query->whereHas('rehabilitation', function ($q) {
                            $q->where('status', '!=', 'complete');
                        });
                    }
                ])
                // 5. Mengurutkan Berdasarkan Upload Terbaru (Subquery)
                ->addSelect([
                    'latest_routine_at' => RehabRoutine::query()->select('created_at')
                        ->whereColumn('patient_id', 'patients.id')
                        ->latest()
                        ->take(1),
                    'rehab_routine_id' => RehabRoutine::query()->select('id')
                        ->whereColumn('patient_id', 'patients.id')
                        ->latest()
                        ->take(1)
                ])
                ->orderByDesc('latest_routine_at')
                ->get()
        ]);
    }
}
