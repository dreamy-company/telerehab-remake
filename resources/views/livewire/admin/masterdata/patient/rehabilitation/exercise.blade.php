<section class="space-y-8 animate-in fade-in duration-500">
    <a href="{{ url()->previous() }}" class="btn bg-primary-500 text-sm font-bold text-white hover:bg-primary-600 mb-4 rounded-md"><i class="fas fa-arrow-left mr-2"></i> Back to Patients</a>

    <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100">
        <h4 class="font-bold text-xl mb-8 flex items-center gap-3 text-gray-800">
            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-history"></i>
            </div>
            Video History & Feedback
        </h4>

        @forelse ($routineResults as $item)
        <div class="group border-b border-gray-100 pb-8 mb-8 last:border-0 last:mb-0 last:pb-0">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Video Thumbnail/Trigger -->
                <div class="w-full md:w-48 h-28 rounded-2xl overflow-hidden relative flex-shrink-0 bg-slate-900 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                    <button onclick="video_modal_{{ $item->id }}.showModal()"
                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 hover:bg-black/20 transition-all text-white group/btn cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center mb-2 group-hover/btn:scale-110 transition-transform">
                            <i class="fas fa-play text-sm"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Play Session</span>
                    </button>

                    <dialog id="video_modal_{{ $item->id }}" class="modal backdrop-blur-sm">
                        <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                            <div class="flex items-center justify-between p-4 bg-white/10 backdrop-blur-md absolute top-0 left-0 right-0 z-10">
                                <h3 class="text-white font-bold text-sm">Patient Exercise Video - {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : \Carbon\Carbon::parse($item->date)->format('d M Y, H:i') }}</h3>
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
                                </form>
                            </div>
                            <video controls class="w-full aspect-video outline-none">
                                <source src="{{ Storage::url($item->video_url) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <form method="dialog" class="modal-backdrop"><button>close</button></form>
                    </dialog>
                </div>

                <div class="flex-1 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="font-extrabold text-gray-900 text-lg leading-tight">{{ $item->routine?->rehabilitation?->name ?? 'Exercise Session' }}</h5>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-teal-600 font-black uppercase tracking-tighter bg-teal-50 px-2 py-0.5 rounded">{{ $item->created_at ? $item->created_at->format('l, d F Y') : \Carbon\Carbon::parse($item->date)->format('l, d F Y') }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->created_at ? $item->created_at->format('H:i') : '' }}</span>
                            </div>
                        </div>
                        @if($item->ratingResponse)
                        <span class="flex items-center gap-1.5 bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-100">
                            <i class="fas fa-check-circle"></i> Reviewed
                        </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @if($item->ratingResponse && $item->ratingResponse->doctor)
                        <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0"><i class="fas fa-user-md"></i></div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black text-indigo-700 uppercase mb-1">{{ $item->ratingResponse->doctor->name }} (Doctor)</p>
                                <p class="text-xs text-indigo-900 leading-relaxed italic line-clamp-2 mb-3">"{{ $item->ratingResponse->review_doctor }}"</p>
                                <button onclick="doctor_video_{{ $item->id }}.showModal()" class="inline-flex items-center gap-2 text-[10px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors cursor-pointer">
                                    <i class="fas fa-play-circle"></i> WATCH REVIEW
                                </button>
                                <dialog id="doctor_video_{{ $item->id }}" class="modal backdrop-blur-sm">
                                    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                                        <div class="flex items-center justify-between p-4 bg-indigo-600 absolute top-0 left-0 right-0 z-10">
                                            <h3 class="text-white font-bold text-sm">Doctor's Video Feedback</h3>
                                            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
                                        </div>
                                        <video controls class="w-full aspect-video outline-none pt-12">
                                            <source src="{{ Storage::url($item->ratingResponse->video_doctor) }}" type="video/mp4">
                                        </video>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                            </div>
                        </div>
                        @endif

                        @if($item->ratingResponse && $item->ratingResponse->therapist)
                        <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100 flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0"><i class="fas fa-user-nurse"></i></div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[10px] font-black text-orange-700 uppercase mb-1">{{ $item->ratingResponse->therapist->name }} (Therapist)</p>
                                <p class="text-xs text-orange-900 leading-relaxed italic line-clamp-2 mb-3">"{{ $item->ratingResponse->review_therapist }}"</p>
                                <button onclick="therapist_video_{{ $item->id }}.showModal()" class="inline-flex items-center gap-2 text-[10px] font-bold text-white bg-orange-600 hover:bg-orange-700 px-3 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-play-circle"></i> WATCH REVIEW
                                </button>
                                <dialog id="therapist_video_{{ $item->id }}" class="modal backdrop-blur-sm">
                                    <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden bg-black rounded-2xl">
                                        <div class="flex items-center justify-between p-4 bg-orange-600 absolute top-0 left-0 right-0 z-10">
                                            <h3 class="text-white font-bold text-sm">Therapist's Video Feedback</h3>
                                            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost text-white">✕</button></form>
                                        </div>
                                        <video controls class="w-full aspect-video outline-none pt-12">
                                            <source src="{{ Storage::url($item->ratingResponse->video_therapist) }}" type="video/mp4">
                                        </video>
                                    </div>
                                    <form method="dialog" class="modal-backdrop"><button>close</button></form>
                                </dialog>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Review Button (Preserved Functionality) -->
                     @if(Auth::user()->role === 'therapist' || Auth::user()->role === 'doctor')
                    <div class="pt-2">
                        <button wire:click="openModal({{ $item->id }})" onclick="feedbackModal.showModal()" class="w-full sm:w-auto px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-edit"></i> Write / Edit Review
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-10">
            <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <i class="fas fa-video-slash text-3xl"></i>
            </div>
            <p class="text-gray-500 font-medium">No exercise videos uploaded yet.</p>
        </div>
        @endforelse
        {{-- ================================================================
     MOVEMENT TRACKING DATA PANEL
     ================================================================ --}}
@if($movementSessions->isNotEmpty())
<div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden mt-8">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h4 class="font-bold text-xl flex items-center gap-3 text-gray-800">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
            </div>
            Movement Tracking Data
        </h4>
        <span class="badge badge-outline text-indigo-600 border-indigo-300 font-bold px-3 py-2">
            {{ $movementSessions->count() }} session(s)
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
                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                        'flagged'   => 'bg-red-100 text-red-700 border-red-200',
                        'in_progress' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    ];
                @endphp
                <span class="badge border font-bold text-xs px-3 py-2 {{ $statusColors[$ms->status] ?? '' }}">
                    @if($ms->status === 'completed') <i class="fas fa-check-circle mr-1" aria-hidden="true"></i> Completed
                    @elseif($ms->status === 'flagged') <i class="fas fa-flag mr-1" aria-hidden="true"></i> Flagged
                    @else <i class="fas fa-clock mr-1" aria-hidden="true"></i> In Progress
                    @endif
                </span>
            </div>
        </div>

        {{-- Summary stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Total Reps</p>
                <p class="text-2xl font-black text-teal-600">{{ $ms->total_reps }}</p>
                <p class="text-[10px] text-gray-400">/ {{ $ms->exercise->thresholds['target_reps'] }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Completion</p>
                <p class="text-2xl font-black text-indigo-600">{{ $ms->completion_rate }}%</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Avg Angle</p>
                <p class="text-2xl font-black text-gray-700">
                    {{ $ms->avg_angle !== null ? number_format($ms->avg_angle, 1) . '°' : '—' }}
                </p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Max Angle</p>
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
                        <th scope="col">Rep</th>
                        <th scope="col">Angle</th>
                        <th scope="col">In Range</th>
                        <th scope="col">Time</th>
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
                                    <i class="fas fa-check" aria-hidden="true"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-red-100 text-red-700 border-red-200 font-bold text-xs gap-1">
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
        @else
        <p class="text-sm text-gray-400 italic">No rep logs recorded yet.</p>
        @endif

        {{-- Existing flags --}}
        @if($ms->flags->isNotEmpty())
        <div class="space-y-2">
            <p class="text-xs font-black text-gray-500 uppercase">Clinical Flags</p>
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

        {{-- Flag button --}}
        @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'therapist')
        <button wire:click="openFlagModal({{ $ms->id }})"
                onclick="flagModal.showModal()"
                class="btn btn-sm border border-red-300 text-red-600 hover:bg-red-50 rounded-lg font-bold flex items-center gap-2"
                aria-label="Flag this movement session">
            <i class="fas fa-flag" aria-hidden="true"></i> Flag Session
        </button>
        @endif

    </div>
    @endforeach
    </div>
</div>
@endif

{{-- Flag Modal --}}
<dialog id="flagModal" class="modal backdrop-blur-sm" wire:ignore.self>
    <div class="modal-box max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 p-0 overflow-hidden">
        <div class="bg-red-600 p-5 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-flag text-xl" aria-hidden="true"></i>
                <h3 class="font-bold text-lg">Flag Movement Session</h3>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost text-white" aria-label="Close">✕</button>
            </form>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="text-xs font-black text-gray-500 uppercase tracking-widest" for="flagTypeSelect">
                    Flag Type
                </label>
                <select id="flagTypeSelect"
                        wire:model="flagType"
                        class="select select-bordered w-full mt-1 rounded-xl"
                        aria-label="Select flag type">
                    <option value="wrong_form">Wrong Form</option>
                    <option value="incomplete_range">Incomplete Range of Motion</option>
                    <option value="missed_reps">Missed Reps</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-black text-gray-500 uppercase tracking-widest" for="flagNoteArea">
                    Clinical Note (optional)
                </label>
                <textarea id="flagNoteArea"
                          wire:model="flagNote"
                          rows="3"
                          class="textarea textarea-bordered w-full mt-1 rounded-xl text-sm"
                          placeholder="Describe the issue in detail..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button wire:click="submitFlag"
                        wire:loading.attr="disabled"
                        class="flex-1 bg-red-600 hover:bg-red-700 disabled:bg-gray-300 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2"
                        aria-label="Submit flag">
                    <span wire:loading.remove wire:target="submitFlag">
                        <i class="fas fa-flag mr-1" aria-hidden="true"></i> Submit Flag
                    </span>
                    <span wire:loading wire:target="submitFlag">
                        <i class="fas fa-circle-notch fa-spin mr-1" aria-hidden="true"></i> Saving...
                    </span>
                </button>
                <form method="dialog" class="flex-1">
                    <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold transition">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-gray-900/40 backdrop-blur-sm"><button>close</button></form>
</dialog>

<dialog id="feedbackModal" class="modal backdrop-blur-sm transition-all duration-300" wire:ignore.self>
    <div class="modal-box max-w-2xl p-0 overflow-hidden bg-white rounded-2xl shadow-2xl border border-gray-100">
        
        <div class="bg-teal-600 p-6 text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-medical text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-none">Submit Clinical Feedback</h3>
                    <p class="text-teal-100 text-[10px] uppercase font-bold tracking-wider mt-1">Reviewing Patient Session</p>
                </div>
            </div>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-black/10 text-white">✕</button>
            </form>
        </div>

        <div class="p-8 space-y-6">
            
            <div class="space-y-2"
                 x-data="{ isUploading: false, progress: 0 }"
                 x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="isUploading = false"
                 x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                 
                <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Upload Video Feedback</label>
                
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-opacity duration-200" 
                         x-show="!isUploading">
                        <i class="fas fa-video text-teal-500 group-focus-within:text-teal-600"></i>
                    </div>

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none" 
                         x-show="isUploading" style="display: none;">
                        <div class="relative w-6 h-6">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="transparent" class="text-gray-200" />
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="transparent" 
                                        :stroke-dasharray="2 * Math.PI * 10" 
                                        :stroke-dashoffset="2 * Math.PI * 10 - (progress / 100) * 2 * Math.PI * 10" 
                                        class="text-teal-500 transition-all duration-150 ease-out" />
                            </svg>
                        </div>
                    </div>

                    <input type="file" wire:model="video" 
                        class="file-input file-input-bordered w-full pl-11 rounded-xl border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-sm h-12 disabled:bg-gray-50 disabled:text-gray-400" 
                        :disabled="isUploading" />
                        
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none" 
                         x-show="isUploading" style="display: none;">
                        <span class="text-xs font-bold text-teal-600" x-text="progress + '%'"></span>
                    </div>
                </div>

                <div class="h-4 mt-1"> <div x-show="isUploading" class="flex items-center gap-2 text-xs text-teal-600 font-bold animate-pulse" style="display: none;">
                        <span>Uploading video... please wait.</span>
                    </div>
                    <div wire:loading wire:target="video" class="flex items-center gap-2 text-xs text-orange-500 font-bold">
                        <i class="fas fa-cog fa-spin"></i> Finalizing & Processing...
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Clinical Evaluation</label>
                <textarea
                    wire:model="review"
                    rows="4"
                    class="textarea textarea-bordered w-full rounded-xl border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-sm p-4 leading-relaxed"
                    placeholder="Provide detailed feedback on patient's form, range of motion, and progress..."></textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-50">
                <button wire:click="submitFeedback" 
                    x-bind:disabled="isUploading"
                    wire:loading.attr="disabled"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 disabled:text-gray-500 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 transition-all flex items-center justify-center gap-2">
                    
                    <span wire:loading.remove wire:target="submitFeedback">
                        <i class="fas fa-paper-plane mr-1"></i> Save & Send Review
                    </span>
                    <span wire:loading wire:target="submitFeedback">
                        <i class="fas fa-circle-notch fa-spin"></i> Saving Feedback...
                    </span>
                </button>

                <form method="dialog" class="sm:w-1/3">
                    <button class="w-full bg-gray-50 hover:bg-gray-100 text-gray-600 py-3.5 rounded-xl font-bold transition-all border border-gray-200">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-gray-900/40 backdrop-blur-sm"><button>close</button></form>
</dialog>


</section>