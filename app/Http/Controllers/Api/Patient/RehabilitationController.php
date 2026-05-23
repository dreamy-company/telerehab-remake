<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Models\RehabRoutine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RehabilitationController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        $data = RehabRoutine::where('patient_id', $patient->id)
            ->with('rehabilitation')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data->map(fn($item) => [
                'id'     => $item->id,
                'status' => $item->status,
                'target' => $item->target,
                'goal'   => $item->goal,
                'is_overdue' => $item->status === 'process'
                    && now()->greaterThan(Carbon::parse($item->target)),
                'rehabilitation' => [
                    'name'        => $item->rehabilitation?->name,
                    'description' => $item->rehabilitation?->description,
                    'video_url'   => $item->rehabilitation?->video_url,
                ],
            ]),
        ]);
    }
}
