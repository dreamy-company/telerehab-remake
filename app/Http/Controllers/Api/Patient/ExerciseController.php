<?php

namespace App\Http\Controllers\Api\Patient;
use App\Http\Controllers\Controller;
use App\Models\RehabRoutine;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request, $id)
    {
        $patient = $request->user()->patient;

        $rehabData = RehabRoutine::where('id', $id)
            ->where('patient_id', $patient->id)
            ->with(['rehabilitation', 'routineResults.ratingResponse'])
            ->firstOrFail();

        $isExpired  = $rehabData->target
            && $rehabData->status === 'process'
            && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($rehabData->target));

        return response()->json([
            'rehab_routine' => [
                'id'          => $rehabData->id,
                'status'      => $rehabData->status,
                'goal'        => $rehabData->goal,
                'target'      => $rehabData->target,
                'is_expired'  => $isExpired,
                'can_upload'  => !$isExpired && $rehabData->status !== 'complete',
                'rehabilitation' => [
                    'name'        => $rehabData->rehabilitation?->name,
                    'description' => $rehabData->rehabilitation?->description,
                    'video_url'   => $rehabData->rehabilitation?->video_url,
                ],
            ],
            'results' => $rehabData->routineResults->map(fn($result) => [
                'id'         => $result->id,
                'video_url'  => asset('storage/' . $result->video_url),
                'feedback'   => $result->feedback,
                'created_at' => $result->created_at,
                'rating_response' => $result->ratingResponse ? [
                    'review_doctor'    => $result->ratingResponse->review_doctor,
                    'review_therapist' => $result->ratingResponse->review_therapist,
                    'video_doctor'     => $result->ratingResponse->video_doctor
                        ? asset('storage/' . $result->ratingResponse->video_doctor)
                        : null,
                    'video_therapist'  => $result->ratingResponse->video_therapist
                        ? asset('storage/' . $result->ratingResponse->video_therapist)
                        : null,
                    'created_at'       => $result->ratingResponse->created_at,
                ] : null,
            ]),
        ]);
    }

    public function uploadVideo(Request $request, $id)
    {
        $request->validate([
            'video'    => 'required|file|mimes:mp4,mov,avi,webm|max:10240',
            'feedback' => 'nullable|string',
        ]);

        $patient   = $request->user()->patient;
        $rehabData = RehabRoutine::where('id', $id)
            ->where('patient_id', $patient->id)
            ->firstOrFail();

        // Cek expired / complete
        $isExpired = $rehabData->target
            && $rehabData->status === 'process'
            && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($rehabData->target));

        if ($isExpired) {
            return response()->json(['message' => 'Cannot upload: rehabilitation routine is overdue.'], 422);
        }

        if ($rehabData->status === 'complete') {
            return response()->json(['message' => 'Cannot upload: rehabilitation routine is already complete.'], 422);
        }

        $videoPath = $request->file('video')->store('exercise_videos', 'public');

        // Sesuaikan model RoutineResult kamu
        $result = $rehabData->routineResults()->create([
            'video_url' => $videoPath,
            'feedback'  => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Video uploaded successfully.',
            'result'  => [
                'id'        => $result->id,
                'video_url' => asset('storage/' . $result->video_url),
                'feedback'  => $result->feedback,
                'created_at'=> $result->created_at,
            ],
        ], 201);
    }
}
