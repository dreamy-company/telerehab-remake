<?php

namespace App\Livewire\Admin;

use App\Models\RatingResponse;
use App\Models\RehabRoutine;
use App\Models\RoutineResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class PatientRehabilitationExercise extends Component
{
    use WithFileUploads;

    public $patientId, $rehabRoutineId, $routineResults;

    public $activeResultId = null;
    public $video;
    public $review;

    public function mount($id, $rehabRoutineId)
    {
        $this->patientId = $id;
        $this->rehabRoutineId = $rehabRoutineId;
        $this->routineResults = RoutineResult::where('rehab_routine_id', $this->rehabRoutineId)->orderBy('created_at', 'desc')->get();
    }
    public function openModal($resultId)
    {
        $this->reset(['video', 'review']);
        $this->activeResultId = $resultId;
    }

    /**
     * Divalidasi begitu file dipilih supaya error (mis. ukuran / format) langsung
     * tampil di modal, bukan gagal diam-diam saat tombol submit ditekan.
     */
    public function updatedVideo()
    {
        $this->validateOnly('video', [
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska,video/3gpp|max:102400',
        ], [
            'video.mimetypes' => 'Format video tidak didukung (gunakan mp4, mov, avi, webm, mkv, atau 3gp).',
            'video.max'       => 'Ukuran video maksimal 100MB.',
        ]);
    }

    public function submitFeedback()
    {
        $user = Auth::user();

        if (! in_array($user->role, ['doctor', 'therapist'], true)) {
            $this->addError('video', 'Hanya dokter atau terapis yang dapat mengirim feedback.');

            return;
        }

        if (! $this->activeResultId) {
            $this->addError('video', 'Sesi latihan tidak ditemukan, silakan buka ulang modal.');

            return;
        }

        $this->validate([
            'video'  => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm,video/x-matroska,video/3gpp|max:102400',
            'review' => 'nullable|string|max:5000',
        ], [
            'video.mimetypes' => 'Format video tidak didukung (gunakan mp4, mov, avi, webm, mkv, atau 3gp).',
            'video.max'       => 'Ukuran video maksimal 100MB.',
        ]);

        if (! $this->video && ! filled($this->review)) {
            $this->addError('video', 'Isi minimal video atau catatan evaluasi.');

            return;
        }

        // Simpan file sekali saja, lalu pakai path-nya untuk kolom sesuai role.
        $videoPath = $this->video?->store('feedback_videos', 'public');

        $videoColumn  = $user->role === 'doctor' ? 'video_doctor' : 'video_therapist';
        $reviewColumn = $user->role === 'doctor' ? 'review_doctor' : 'review_therapist';
        $userColumn   = $user->role === 'doctor' ? 'doctor_id' : 'therapist_id';

        $ratingResponse = RatingResponse::where('routine_result_id', $this->activeResultId)->first();

        $data = [
            $reviewColumn => $this->review,
            $userColumn   => $user->id,
        ];

        // Kalau tidak ada video baru, jangan timpa video lama dengan null.
        if ($videoPath) {
            $data[$videoColumn] = $videoPath;
        }

        if ($ratingResponse) {
            $ratingResponse->update($data);
        } else {
            RatingResponse::create($data + ['routine_result_id' => $this->activeResultId]);
        }

        $this->reset(['video', 'review']);

        return redirect()->route($user->role . '.patient.rehabilitation.exercise', ['id' => $this->patientId, 'rehabRoutineId' => $this->rehabRoutineId])->with('success-alert', 'Feedback submitted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.masterdata.patient.rehabilitation.exercise');
    }
}
