<section class="space-y-8 animate-in fade-in duration-500">

    <!-- Video Panduan & Upload Video Side by Side -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Video Tutorial (2 of 3 cols) -->
        <div id="videoTutorial" class="lg:col-span-2 bg-white shadow-lg rounded-md p-5 w-full">
            <h4 class="font-bold text-gray-800 mb-4 px-3 flex items-center gap-2">
                <i class="fas fa-video text-teal-500"></i> {{ __('Video Tutorial For') }} {{ $rehabData->rehabilitation->name }}
            </h4>

            <div class="relative aspect-video rounded-md overflow-hidden">
                <iframe src="{{ $rehabData->rehabilitation->video_url }}" class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer;  clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        <!-- Upload Zone (Side Info & Action) -->
        <div id="uploadZone" class="bg-white shadow-lg rounded-xl p-8 border border-gray-100 flex flex-col h-full">
            <div class="mb-6">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-widest mb-1 block">{{ __('Active Program') }}</span>
                <h3 class="text-2xl font-extrabold text-gray-900 leading-tight mb-3">
                    {{ $rehabData->rehabilitation->name }}
                </h3>
                <p class="text-sm text-gray-500 leading-relaxed italic">
                    "{{ $rehabData->rehabilitation->description }}"
                </p>
            </div>

            <div class="space-y-4 mb-8">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">{{ __('Goal') }}</span>
                        <span
                            class="text-sm font-semibold text-gray-700">{{ $rehabData->goal}}</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">{{ __('Target') }}</span>
                        <span
                            class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($rehabData->target)->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- <div class="flex items-center justify-between bg-teal-50/50 p-3 rounded-lg border border-teal-100">
                    <span class="text-xs font-bold text-teal-800 uppercase">Current Status</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-teal-100 text-teal-700 uppercase">
                        {{ $rehabData->status }}
                    </span>
                </div> -->
            </div>

            <div class="mt-auto pt-6 border-t border-dashed border-gray-200">
                <div class="flex flex-col items-center text-center mb-6">
                    <div
                        class="w-14 h-14 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center text-xl mb-3 shadow-sm">
                        <i class="fas fa-camera-retro"></i>
                    </div>
                    <p class="text-xs text-gray-400 leading-snug px-4">
                        {{ __('Record your progress and upload it here for review by your therapist.') }}
                    </p>
                </div>
                <button {{ $rehabData->target && $rehabData->status === 'process' && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($rehabData->target)) ? 'disabled' : '' }} {{ $rehabData->status === 'complete' ? 'disabled' : '' }} onclick="uploadModal.showModal()"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-3 shadow-md hover:shadow-lg cursor-pointer active:scale-[0.98] {{ $rehabData->target && $rehabData->status === 'process' && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($rehabData->target)) ? 'opacity-50 cursor-not-allowed' : '' }} {{ $rehabData->status === 'complete' ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                    <span>{{ __('UPLOAD VIDEO') }}</span>
                </button>
                @if($rehabData->target && $rehabData->status === 'process' && now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($rehabData->target)))
                <p class="text-sm text-red-600 font-bold my-4 text-center">
                    ⚠️ {{ __('This rehabilitation routine is overdue. Please upload your exercise video as soon as possible.') }}
                </p>
                @elseif($rehabData->status === 'complete')
                <p class="text-sm text-green-600 font-bold my-4 text-center">
                    ✅ {{ __('This rehabilitation routine has been completed. Thank you for your dedication!') }}
                </p>
                @endif
            </div>
        </div>

    </div>
    {{-- Live Movement Tracking --}}
    <div id="trackingZone" class="bg-white shadow-lg rounded-xl p-8 border border-gray-100 flex flex-col">
        <div class="mb-4">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-1 block">
                <i class="fas fa-running mr-1" aria-hidden="true"></i> {{ __('Live Movement Tracking') }}
            </span>
            <h3 class="text-lg font-extrabold text-gray-900 leading-tight mb-1">
                {{ __('Track Your Movement in Real Time') }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ __('Use your webcam to count repetitions and measure joint angles automatically. Data is saved and visible to your doctor.') }}
            </p>
        </div>

        <div class="space-y-3 mt-2">
            <label for="movementExerciseSelect" class="text-xs font-black text-gray-500 uppercase tracking-widest">
                {{ __('Select Exercise Type') }}
            </label>
            <select id="movementExerciseSelect"
                    wire:model="selectedMovementExerciseId"
                    class="select select-bordered w-full rounded-xl text-sm"
                    aria-label="{{ __('Select movement exercise type') }}">
                <option value="">{{ __('-- Choose an exercise --') }}</option>
                @foreach($movementExercises as $me)
                    <option value="{{ $me->id }}">{{ $me->name }}</option>
                @endforeach
            </select>
            @error('selectedMovementExerciseId')
                <span class="text-red-600 text-sm" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <button wire:click="startTracking"
                wire:loading.attr="disabled"
                {{ $rehabData->status === 'complete' ? 'disabled' : '' }}
                class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3.5 px-4 rounded-xl transition flex items-center justify-center gap-3 shadow-md"
                aria-label="Start live movement tracking session">
            <span wire:loading.remove wire:target="startTracking">
                <i class="fas fa-camera mr-2" aria-hidden="true"></i> {{ __('Start Live Tracking') }}
            </span>
            <span wire:loading wire:target="startTracking">
                <i class="fas fa-circle-notch fa-spin mr-2" aria-hidden="true"></i> {{ __('Starting...') }}
            </span>
        </button>
    </div>

    {{-- ================================================================
         YOUR MOVEMENT TRACKING HISTORY (read-only)
         ================================================================ --}}
    @if($movementSessions->isNotEmpty())
    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden mt-8">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h4 class="font-bold text-xl flex items-center gap-3 text-gray-800">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line" aria-hidden="true"></i>
                </div>
                {{ __('Your Movement Tracking History') }}
            </h4>
            <span class="badge badge-outline text-indigo-600 border-indigo-300 font-bold px-3 py-2">
                {{ trans_choice(':count session|:count sessions', $movementSessions->count(), ['count' => $movementSessions->count()]) }}
            </span>
        </div>

        <div class="divide-y divide-gray-100">
        @foreach($movementSessions as $ms)
        <div class="p-6 space-y-4">

            {{-- Session header --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="font-extrabold text-gray-900 text-base">
                        {{ $ms->exercise->name }}
                    </h5>
                    <span class="text-xs text-gray-400 font-bold">
                        {{ $ms->session_date->format('d F Y') }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusColors = [
                            'completed'   => 'bg-green-100 text-green-700 border-green-200',
                            'flagged'     => 'bg-red-100 text-red-700 border-red-200',
                            'in_progress' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        ];
                    @endphp
                    <span class="badge border font-bold text-xs px-3 py-2 {{ $statusColors[$ms->status] ?? '' }}">
                        @if($ms->status === 'completed') <i class="fas fa-check-circle mr-1" aria-hidden="true"></i> {{ __('Completed') }}
                        @elseif($ms->status === 'flagged') <i class="fas fa-flag mr-1" aria-hidden="true"></i> {{ __('Flagged') }}
                        @else <i class="fas fa-clock mr-1" aria-hidden="true"></i> {{ __('In Progress') }}
                        @endif
                    </span>
                </div>
            </div>

            {{-- Summary stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">{{ __('Total Reps') }}</p>
                    <p class="text-2xl font-black text-teal-600">{{ $ms->total_reps }}</p>
                    <p class="text-[10px] text-gray-400">/ {{ $ms->exercise->thresholds['target_reps'] }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">{{ __('Completion') }}</p>
                    <p class="text-2xl font-black text-indigo-600">{{ $ms->completion_rate }}%</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">{{ __('Avg Angle') }}</p>
                    <p class="text-2xl font-black text-gray-700">
                        {{ $ms->avg_angle !== null ? number_format($ms->avg_angle, 1) . '°' : '—' }}
                    </p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">{{ __('Max Angle') }}</p>
                    <p class="text-2xl font-black text-gray-700">
                        {{ $ms->max_angle !== null ? number_format($ms->max_angle, 1) . '°' : '—' }}
                    </p>
                </div>
            </div>

            {{-- Rep-by-rep log table --}}
            @if($ms->logs->isNotEmpty())
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="table table-zebra table-sm w-full text-sm" aria-label="Rep-by-rep movement log">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th scope="col">{{ __('Rep') }}</th>
                            <th scope="col">{{ __('Angle') }}</th>
                            <th scope="col">{{ __('In Range') }}</th>
                            <th scope="col">{{ __('Time') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ms->logs->sortBy('rep_number') as $log)
                        <tr class="{{ $log->within_threshold ? '' : 'bg-red-50' }}">
                            <td class="font-bold">{{ $log->rep_number }}</td>
                            <td>{{ number_format($log->joint_angle, 1) }}&deg;</td>
                            <td>
                                @if($log->within_threshold)
                                    <span class="badge bg-green-100 text-green-700 border-green-200 font-bold text-xs gap-1">
                                        <i class="fas fa-check" aria-hidden="true"></i> {{ __('Yes') }}
                                    </span>
                                @else
                                    <span class="badge bg-red-100 text-red-700 border-red-200 font-bold text-xs gap-1">
                                        <i class="fas fa-times" aria-hidden="true"></i> {{ __('No') }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-gray-400">{{ $log->recorded_at->format('H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-gray-400 italic">{{ __('No rep logs recorded yet.') }}</p>
            @endif

            {{-- Clinical flags from your doctor / therapist --}}
            @if($ms->flags->isNotEmpty())
            <div class="space-y-2">
                <p class="text-xs font-black text-gray-500 uppercase">{{ __('Notes from your care team') }}</p>
                @foreach($ms->flags as $flag)
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-3">
                    <i class="fas fa-flag text-red-500 mt-0.5" aria-hidden="true"></i>
                    <div>
                        <p class="text-xs font-bold text-red-700 uppercase">
                            {{ str_replace('_', ' ', $flag->flag_type) }}
                            <span class="text-gray-400 font-normal ml-2">by {{ $flag->flaggedBy->name }}</span>
                        </p>
                        @if($flag->note)
                        <p class="text-sm text-gray-700 mt-0.5">{{ $flag->note }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
        @endforeach
        </div>
    </div>
    @endif

    <dialog id="uploadModal" class="modal" wire:ignore.self>
        <form method="dialog" class="modal-box w-full max-w-lg"
            x-data="{ isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true"
            x-on:livewire-upload-finish="isUploading = false"
            x-on:livewire-upload-error="isUploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <h3 class="font-bold text-lg mb-4">{{ __('Upload Exercise Video') }}</h3>

            <div>
                <label for="video" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Choose Your Exercise Video') }} <span class="font-bold text-primary-500">{{ __('Max 10 MB') }}</span></label>

                <div class="relative w-full">
                    <label for="video"
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-teal-500 rounded-lg cursor-pointer hover:bg-teal-50 transition relative overflow-hidden bg-white">

                        <div x-show="!isUploading" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-upload text-teal-500 text-3xl mb-2"></i>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                            <p class="text-xs text-gray-500">{{ __('Video files (MP4, MOV, etc)') }}</p>
                        </div>

                        <div x-show="isUploading" class="absolute inset-0 flex flex-col items-center justify-center bg-white z-10">
                            <div class="relative w-20 h-20">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200" />

                                    <circle cx="40" cy="40" r="36" stroke="currentColor" stroke-width="8" fill="transparent"
                                        :stroke-dasharray="2 * Math.PI * 36"
                                        :stroke-dashoffset="2 * Math.PI * 36 - (progress / 100) * 2 * Math.PI * 36"
                                        class="text-teal-500 transition-all duration-300 ease-out" />
                                </svg>

                                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-teal-600" x-text="progress + '%'"></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('Uploading...') }}</p>
                        </div>

                        <input type="file" id="video" name="video" wire:model="video" accept="video/*" class="hidden">
                    </label>
                </div>

                @if($video)
                <div class="mt-2 flex items-center gap-2 text-sm text-teal-700 bg-teal-50 p-2 rounded border border-teal-200" x-show="!isUploading">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('Video selected successfully.') }}</span>
                </div>
                @endif

                @error('video')
                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="">
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1 mt-4">{{ __('Your Feedback') }}</label>
                <textarea id="feedback" name="feedback" wire:model="feedback" rows="3"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="{{ __('Add Your Feedback') }}"></textarea>
                @error('feedback')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-action">
                <button type="button" wire:click="uploadVideo"
                    class="btn bg-primary-500 text-white hover:bg-primary-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isUploading"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="uploadVideo">{{ __('Upload') }}</span>
                    <span wire:loading wire:target="uploadVideo">{{ __('Saving...') }}</span>
                </button>

                <button type="button" class="btn btn-ghost rounded-lg" onclick="uploadModal.close()">{{ __('Cancel') }}</button>
            </div>
        </form>
    </dialog>


    <!-- Riwayat Upload & Feedback Full Width Below -->
    <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
        <h4 class="font-bold text-xl mb-8 flex items-center gap-3 text-gray-800">
            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-history"></i>
            </div>
            {{ __('Video History & Feedback') }}
        </h4>

        @forelse ($results as $result)
        <div class="group border-b border-gray-100 pb-8 mb-8 last:border-0 last:mb-0 last:pb-0">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Video Thumbnail/Trigger -->
                <div
                    class="w-full md:w-48 h-28 rounded-2xl overflow-hidden relative flex-shrink-0 bg-slate-900 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                    <button onclick="video_modal_{{ $result->id }}.showModal()"
                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 hover:bg-black/20 transition-all text-white group/btn cursor-pointer">
                        <div
                            class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-2 group-hover/btn:scale-110 transition-transform">
                            <i class="fas fa-play text-sm"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Play Session') }}</span>
                    </button>

                    <dialog id="video_modal_{{ $result->id }}" class="modal backdrop-blur-sm">
                        <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                            <div
                                class="flex items-center justify-between p-4 bg-white/10 backdrop-blur-md absolute top-0 left-0 right-0 z-10">
                                <h3 class="text-white font-bold text-sm">{{ __('Your Exercise Video') }} -
                                    {{ $result->created_at->format('d M Y, H:i') }}
                                </h3>
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
                                </form>
                            </div>
                            <video controls class="w-full aspect-video outline-none">
                                <source src="{{ Storage::url($result->video_url) }}" type="video/mp4">
                                {{ __('Your browser does not support the video tag.') }}
                            </video>
                        </div>
                        <form method="dialog" class="modal-backdrop"><button>close</button></form>
                    </dialog>
                </div>

                <div class="flex-1 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="font-extrabold text-gray-900 text-lg leading-tight">
                                {{ $result->routine?->rehabilitation?->name ?? __('Exercise Session') }}
                            </h5>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-[10px] text-teal-600 font-black uppercase tracking-tighter bg-teal-50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($result->created_at)->format('l, d F Y') }}</span>
                                <span
                                    class="text-[10px] text-gray-400 font-bold uppercase">{{ \Carbon\Carbon::parse($result->created_at)->format('H:i') }}</span>
                            </div>
                        </div>
                        @if($result->ratingResponse)
                        <span
                            class="flex items-center gap-1.5 bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-100">
                            <i class="fas fa-check-circle"></i> {{ __('Reviewed') }}
                        </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @if($result->ratingResponse && $result->ratingResponse->doctor)
                        <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex gap-4 items-start">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black text-indigo-700 uppercase mb-1">{{ __("Doctor's Feedback") }}</p>
                                <span
                                    class="text-[10px] text-indigo-600 font-black uppercase tracking-tighter bg-indigo-50 px-2 py-0.5 mb-4 rounded">{{ \Carbon\Carbon::parse($result->ratingResponse->created_at)->format('l, d F Y') }}
                                    {{ \Carbon\Carbon::parse($result->ratingResponse->created_at)->format('H:i') }}</span>
                                <p class="text-xs text-indigo-900 leading-relaxed italic line-clamp-2 mb-3">
                                    "{{ $result->ratingResponse->review_doctor }}"</p>
                                <button onclick="doctor_video_{{ $result->id }}.showModal()"
                                    class="inline-flex items-center gap-2 text-[10px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                    <i class="fas fa-play-circle"></i> {{ __('WATCH REVIEW') }}
                                </button>
                                <dialog id="doctor_video_{{ $result->id }}" class="modal backdrop-blur-sm">
                                    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                                        <div
                                            class="flex items-center justify-between p-4 bg-indigo-600 absolute top-0 left-0 right-0 z-10">
                                            <h3 class="text-white font-bold text-sm">{{ __("Doctor's Video Feedback") }}</h3>
                                            <form method="dialog"><button
                                                    class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
                                        </div>
                                        <video controls class="w-full aspect-video outline-none pt-12">
                                            <source src="{{ Storage::url($result->ratingResponse->video_doctor) }}"
                                                type="video/mp4">
                                        </video>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                            </div>
                        </div>
                        @endif

                        @if($result->ratingResponse && $result->ratingResponse->therapist)
                        <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100 flex gap-4 items-start">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                                <i class="fas fa-user-nurse"></i>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black text-orange-700 uppercase mb-1">{{ __("Therapist's Feedback") }}
                                </p>
                                <span
                                    class="text-[10px] text-orange-600 font-black uppercase tracking-tighter bg-orange-50 px-2 py-0.5 mb-4 rounded">{{ \Carbon\Carbon::parse($result->ratingResponse->created_at)->format('l, d F Y') }}
                                    {{ \Carbon\Carbon::parse($result->ratingResponse->created_at)->format('H:i') }}</span>
                                <p class="text-xs text-orange-900 leading-relaxed italic line-clamp-2 mb-3">
                                    "{{ $result->ratingResponse->review_therapist }}"</p>
                                <button onclick="therapist_video_{{ $result->id }}.showModal()"
                                    class="inline-flex items-center gap-2 text-[10px] font-bold text-white bg-orange-600 hover:bg-orange-700 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-play-circle"></i> {{ __('WATCH REVIEW') }}
                                </button>
                                <dialog id="therapist_video_{{ $result->id }}" class="modal backdrop-blur-sm">
                                    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                                        <div
                                            class="flex items-center justify-between p-4 bg-orange-600 absolute top-0 left-0 right-0 z-10">
                                            <h3 class="text-white font-bold text-sm">{{ __("Therapist's Video Feedback") }}</h3>
                                            <form method="dialog"><button
                                                    class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
                                        </div>
                                        <video controls class="w-full aspect-video outline-none pt-12">
                                            <source src="{{ Storage::url($result->ratingResponse->video_therapist) }}"
                                                type="video/mp4">
                                        </video>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center italic text-sm">{{ __('No video upload history available.') }}</p>
        @endforelse
    </div>

</section>