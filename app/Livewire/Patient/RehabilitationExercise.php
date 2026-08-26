<?php

namespace App\Livewire\Patient;

use App\Models\RehabRoutine;
use App\Support\UploadValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class RehabilitationExercise extends Component
{
    use WithFileUploads;

    public $rehabRoutineId, $rehabData, $results, $video, $feedback, $rehabOverdue = false;

    public function mount($id)
    {
        $this->rehabRoutineId = $id;
        $this->rehabData = RehabRoutine::with('rehabilitation')->findOrFail($this->rehabRoutineId);
        $this->results = $this->rehabData->routineResults()->orderBy('created_at', 'desc')->get();
       
       
    }

    /**
     * Aturan validasi video dipakai bersama oleh updatedVideo() dan uploadVideo()
     * supaya batas ukuran tidak pernah berbeda antara pengecekan awal dan submit.
     */
    protected function videoRule(string $presence): string
    {
        return $presence . '|file|mimes:' . config('upload.video_mimes') . '|max:' . config('upload.max_size_kb');
    }

    protected function videoMessages(): array
    {
        return array_merge(UploadValidation::messages(['video' => 'video']), [
            'video.mimes' => 'Format video tidak didukung (gunakan mp4, mov, avi, webm, mkv, atau 3gp).',
        ]);
    }

    /**
     * Divalidasi begitu file dipilih supaya error (mis. ukuran / format) langsung
     * tampil, bukan baru ketahuan setelah tombol submit ditekan.
     */
    public function updatedVideo()
    {
        $this->validateOnly('video', [
            'video' => $this->videoRule('nullable'),
        ], $this->videoMessages());
    }

    public function uploadVideo()
    {
        $this->validate([
            'video' => $this->videoRule('required'),
            'feedback' => 'nullable|string',
        ], $this->videoMessages());

        $path = $this->video->store('rehab_videos', 'public');

        $this->rehabData->routineResults()->create([
            'video_url' => $path,
            'feedback' => $this->feedback,
            'patient_id' => Auth::user()->patient->id,
            'date' => now(),
        ]);

        $this->video = null;
        $this->feedback = null;
        $this->results = $this->rehabData->routineResults()->get();

        return redirect()->route('patient.rehabilitation.exercise', ['id' => $this->rehabRoutineId])->with('success-alert', 'Video uploaded successfully.');
    }
    public function render()
    {
        return view('livewire.patient.rehabilitation.exercise.index');
    }
}
