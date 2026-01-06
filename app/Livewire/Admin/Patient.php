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
