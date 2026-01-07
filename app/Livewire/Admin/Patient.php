<?php

namespace App\Livewire\Admin;

use App\Models\Patient as ModelsPatient;
use App\Models\RehabRoutine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Patient extends Component
{
    protected $listeners = ['deleteConfirmed'];
    public $search, $idPatient, $patientData = null;

    public function detail($idPatient)
    {
        $this->patientData = ModelsPatient::find($idPatient);

    }

    public function delete($id)
    {
        $this->idPatient = $id;
        $patient = ModelsPatient::findOrFail($this->idPatient);
        if ($patient) {
            $this->dispatch('alert-delete', 'Are you sure you want to delete this Patient?');
        } else {
            $this->dispatch('alert-error', 'Patient not found.');
        }
    }

    public function deleteConfirmed()
    {
        $patient = ModelsPatient::findOrFail($this->idPatient);
        if ($patient) {
            $patient->isDeleted = true;
            $patient->deleted_by = Auth::user()->id;
            $patient->save();
            $patient->delete();
            $this->dispatch('delete-success', 'Patient deleted successfully.');
        } else {
            $this->dispatch('alert-error', 'Patient not found.');
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.patient.index', [
            'data' => ModelsPatient::query()
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
