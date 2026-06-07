<section class="space-y-6 animate-in fade-in duration-500" aria-label="Movement Tracking Session">

    {{-- Config exposed to tracking.js --}}
    <script>
        window.REHAB_CONFIG = {
            sessionId:   {{ $session->id }},
            livewireId:  null, // set after Livewire init
            targetJoint: @json($session->exercise->target_joint),
            minAngle:    {{ $session->exercise->thresholds['min_angle'] }},
            maxAngle:    {{ $session->exercise->thresholds['max_angle'] }},
            targetReps:  {{ $session->exercise->thresholds['target_reps'] }},
        };
    </script>

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}"
           class="btn bg-primary-500 text-white hover:bg-primary-600 rounded-md text-sm font-bold"
           aria-label="Go back">
            <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> Back
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">
                Live Movement Tracking
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $session->exercise->name }} &mdash;
                Target: <strong>{{ $session->exercise->thresholds['target_reps'] }} reps</strong>
            </p>
        </div>
    </div>

    {{-- Done banner --}}
    @if($status === 'done' || $session->status === 'completed')
    <div class="alert bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3" role="alert">
        <i class="fas fa-check-circle text-2xl text-green-600" aria-hidden="true"></i>
        <div>
            <p class="font-bold text-lg">Session Completed!</p>
            <p class="text-sm">Your movement data has been saved. Your doctor will review it shortly.</p>
        </div>
    </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-3 gap-4" aria-live="polite" aria-atomic="true">
        <div class="bg-white rounded-2xl shadow p-5 text-center border border-gray-100">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Reps Done</p>
            <p id="rep-display"
               class="text-5xl font-black text-teal-600 leading-none"
               aria-label="Repetitions done">{{ $repCount }}</p>
            <p class="text-xs text-gray-400 mt-1">of {{ $session->exercise->thresholds['target_reps'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-5 text-center border border-gray-100">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Joint Angle</p>
            <p id="angle-display"
               class="text-5xl font-black text-indigo-600 leading-none"
               aria-label="Current joint angle in degrees">{{ number_format($currentAngle, 0) }}&deg;</p>
            <p class="text-xs text-gray-400 mt-1">
                Range: {{ $session->exercise->thresholds['min_angle'] }}&deg; &ndash;
                {{ $session->exercise->thresholds['max_angle'] }}&deg;
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow p-5 text-center border border-gray-100">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
            <p id="status-display"
               class="text-2xl font-black text-gray-700 leading-none mt-2"
               aria-label="Tracking status">
               @if($status === 'done' || $session->status === 'completed')
                   <span class="text-green-600"><i class="fas fa-check-circle" aria-hidden="true"></i> Done</span>
               @elseif($status === 'tracking')
                   <span class="text-teal-600"><i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Tracking</span>
               @else
                   <span class="text-gray-500"><i class="fas fa-hourglass-start" aria-hidden="true"></i> Ready</span>
               @endif
            </p>
        </div>
    </div>

    {{-- Camera + skeleton overlay --}}
    <div class="bg-black rounded-2xl overflow-hidden shadow-xl relative"
         style="aspect-ratio: 16/9; max-height: 520px;"
         aria-label="Webcam feed with pose skeleton overlay">
        <video id="rehab-video"
               autoplay
               playsinline
               muted
               class="w-full h-full object-cover"
               aria-label="Webcam feed">
        </video>
        <canvas id="rehab-canvas"
                class="absolute inset-0 w-full h-full"
                aria-hidden="true">
        </canvas>

        {{-- Overlay when not tracking --}}
        <div id="camera-placeholder"
             class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80 text-white"
             aria-live="polite">
            <i class="fas fa-camera text-5xl mb-4 text-teal-400" aria-hidden="true"></i>
            <p class="text-xl font-bold">Camera not started</p>
            <p class="text-sm text-gray-300 mt-1">Press <strong>Start Tracking</strong> to begin</p>
        </div>
    </div>

    {{-- Instruction text --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800" role="note">
        <p class="font-bold mb-1">
            <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
            Exercise: {{ $session->exercise->description ?? $session->exercise->name }}
        </p>
        <p>Perform the motion slowly and clearly in front of the camera.
           A rep is counted when you complete a full cycle (down &rarr; up &rarr; down).</p>
    </div>

    {{-- Action buttons --}}
    @if($status !== 'done' && $session->status !== 'completed')
    <div class="flex flex-col sm:flex-row gap-4">
        <button id="start-btn"
                onclick="startTracking()"
                class="flex-1 bg-teal-600 hover:bg-teal-700 active:scale-95 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-3"
                aria-label="Start movement tracking with webcam">
            <i class="fas fa-play-circle text-xl" aria-hidden="true"></i>
            Start Tracking
        </button>

        <button wire:click="completeSession"
                wire:loading.attr="disabled"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:bg-gray-300 disabled:text-gray-500 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-3"
                aria-label="Complete and save this tracking session">
            <span wire:loading.remove wire:target="completeSession">
                <i class="fas fa-flag-checkered text-xl mr-2" aria-hidden="true"></i>Complete Session
            </span>
            <span wire:loading wire:target="completeSession">
                <i class="fas fa-circle-notch fa-spin mr-2" aria-hidden="true"></i>Saving...
            </span>
        </button>
    </div>
    @else
    <a href="{{ url()->previous() }}"
       class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all">
        <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> Return to Exercise Page
    </a>
    @endif

    {{-- Rep log table --}}
    @if($session->logs->isNotEmpty())
    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-ol text-teal-500" aria-hidden="true"></i>
                Rep Log
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm" aria-label="Movement repetition log">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th scope="col">Rep</th>
                        <th scope="col">Angle</th>
                        <th scope="col">In Range</th>
                        <th scope="col">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->logs->sortBy('rep_number') as $log)
                    <tr class="{{ $log->within_threshold ? '' : 'bg-red-50' }}">
                        <td class="font-bold">{{ $log->rep_number }}</td>
                        <td>{{ number_format($log->joint_angle, 1) }}&deg;</td>
                        <td>
                            @if($log->within_threshold)
                                <span class="badge bg-green-100 text-green-700 border-green-200 font-bold gap-1">
                                    <i class="fas fa-check" aria-hidden="true"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-red-100 text-red-700 border-red-200 font-bold gap-1">
                                    <i class="fas fa-times" aria-hidden="true"></i> No
                                </span>
                            @endif
                        </td>
                        <td class="text-gray-400">{{ $log->recorded_at->format('H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</section>

@push('scripts')
@vite(['resources/js/tracking.js'])
@endpush
