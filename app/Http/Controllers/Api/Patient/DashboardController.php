<?php

namespace App\Http\Controllers\Api\Patient;

use App\Events\UpdateEvent;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\RehabRoutine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        // --- Check active consultation (pending) ---
        $checkConsultation = Meeting::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->first();

        // --- Check active rehabilitation ---
        $checkRehabilitation = RehabRoutine::where('patient_id', $patient->id)
            ->where('status', 'process')
            ->with(['rehabilitation', 'routineResults'])
            ->latest()
            ->first();

        // --- Consultation history ---
        $consultationDatas = Meeting::where('patient_id', $patient->id)
            ->with('doctor')
            ->latest()
            ->get();

        // --- Build recovery progress ---
        $recoveryProgress = null;
        if ($checkRehabilitation) {
            $start     = Carbon::parse($checkRehabilitation->created_at)->startOfDay();
            $end       = Carbon::parse($checkRehabilitation->target)->startOfDay();
            $today     = now()->startOfDay();
            $totalDays = (int) $start->diffInDays($end) + 1;

            $completedDays = (int) $checkRehabilitation->routineResults
                ->filter(fn($item) => $item->created_at->startOfDay()->between($start, min($today, $end)))
                ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                ->count();

            $remainingDays      = max(0, $totalDays - $completedDays);
            $progressPercentage = $totalDays > 0 ? (int) round(($completedDays / $totalDays) * 100) : 0;

            $recoveryProgress = [
                'total_days'          => $totalDays,
                'completed_days'      => $completedDays,
                'remaining_days'      => $remainingDays,
                'progress_percentage' => $progressPercentage,
            ];
        }

        return response()->json([
            'check_rehabilitation' => $checkRehabilitation ? [
                'id'              => $checkRehabilitation->id,
                'status'          => $checkRehabilitation->status,
                'target'          => $checkRehabilitation->target,
                'rehabilitation'  => [
                    'name'      => $checkRehabilitation->rehabilitation?->name,
                    'video_url' => $checkRehabilitation->rehabilitation?->video_url,
                ],
                'recovery_progress' => $recoveryProgress,
            ] : null,

            'check_consultation' => $checkConsultation ? [
                'id'               => $checkConsultation->id,
                'status'           => $checkConsultation->status,
                'date'             => $checkConsultation->date,
                'time'             => $checkConsultation->time,
                'location'         => $checkConsultation->location,
                'meeting_category' => $checkConsultation->meeting_category,
                'doctor_assigned'  => !is_null($checkConsultation->doctor_id),
                'doctor'           => $checkConsultation->doctor ? [
                    'id'   => $checkConsultation->doctor->id,
                    'name' => $checkConsultation->doctor->name,
                    'photo'=> $checkConsultation->doctor->profile_photo_path
                        ? asset('storage/' . $checkConsultation->doctor->profile_photo_path)
                        : null,
                ] : null,
            ] : null,

            'consultation_history' => $consultationDatas->map(fn($item) => [
                'id'               => $item->id,
                'date'             => $item->date,
                'time'             => $item->time,
                'status'           => $item->status,
                'location'         => $item->location,
                'meeting_category' => $item->meeting_category,
                'diagnosis'        => $item->diagnosis,
                'medicine'         => $item->medicine,
                'description'      => $item->description,
                'doctor'           => $item->doctor ? [
                    'id'   => $item->doctor->id,
                    'name' => $item->doctor->name,
                    'photo'=> $item->doctor->profile_photo_path
                        ? asset('storage/' . $item->doctor->profile_photo_path)
                        : null,
                ] : null,
            ]),
        ]);
    }

    public function requestConsultation(Request $request)
    {
        $patient = $request->user()->patient;

        $existingConsultation = Meeting::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->first();

        if ($existingConsultation) {
            return response()->json([
                'message' => 'There is already a pending consultation schedule.',
            ], 409);
        }

        Meeting::create([
            'patient_id' => $patient->id,
            'status'     => 'pending',
        ]);

        broadcast(new UpdateEvent('Request Consultation'))->toOthers();

        return response()->json([
            'message' => 'Consultation request has been sent successfully.',
        ], 201);
    }
}
