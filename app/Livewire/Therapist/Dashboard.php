<?php

namespace App\Livewire\Therapist;

use App\Models\Patient;
use App\Models\Rehab;
use App\Models\RehabRoutine;
use App\Models\RehabType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $search = '', $patientData = null;
    public function detail($idPatient)
    {
        $this->patientData = Patient::find($idPatient);
    }   
    public function render()
    {
        return view('livewire.therapist.dashboard', [
            'totalPatient' => Patient::all()->count(),
            'totalRehabilitationPhases' => RehabType::all()->count(),
            'totalRehabilitations' => Rehab::all()->count(),
            'dataPatients' => Patient::query()
                // 1. Logika Pencarian (Wajib dibungkus dalam where closure agar OR tidak merusak filter role)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('medical_record_number', 'like', "%{$this->search}%")
                            ->orWhere('bpjs_number', 'like', '%' . $this->search . '%')
                            ->orWhere('address', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', function ($sq) {
                                $sq->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                // 2. Filter Role Pasien
                ->whereHas('user', function ($query) {
                    $query->where('role', 'patient');
                })
                // 3. Menghitung Jumlah Routine pada Rehabilitasi Aktif
                // Kita gunakan alias 'active_routines_count' agar spesifik
                ->withCount([
                    'rehabRoutines as active_routines_count' => function ($query) {
                        // Filter ini memastikan yang dihitung hanya routine milik rehabilitasi yang sedang berjalan
                        // Sesuaikan 'rehabilitation' dan 'status' dengan nama relasi/kolom di database Anda
                        $query->whereHas('rehabilitation', function ($q) {
                            // Asumsi: Rehabilitasi aktif adalah yang statusnya BUKAN complete
                            // Atau Anda bisa ubah menjadi ->where('status', 'active')
                            $q->where('status', '!=', 'complete');
                        });
                    }
                ])
                // 4. Mengurutkan Berdasarkan Upload Terbaru (Subquery)
                ->addSelect([
                    'latest_routine_at' => RehabRoutine::select('created_at')
                        ->whereColumn('patient_id', 'patients.id')
                        ->latest()
                        ->take(1),
                    'rehab_routine_id' => RehabRoutine::select('id')
                        ->whereColumn('patient_id', 'patients.id')
                        ->latest()
                        ->take(1)
                ])
                // Pasien dengan upload terbaru akan muncul paling atas
                // Pasien yang belum pernah upload akan muncul di bawah (nulls last)
                ->orderByDesc('latest_routine_at')
                ->get()
        ]);
    }
}
