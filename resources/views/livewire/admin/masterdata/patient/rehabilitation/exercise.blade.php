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
    <dialog id="feedbackModal" class="modal backdrop-blur-sm transition-all duration-300" wire:ignore.self>
        <div class="modal-box max-w-2xl p-0 overflow-hidden bg-white rounded-2xl shadow-2xl border border-gray-100">
            <!-- Header -->
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
                <!-- Video Upload Section -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Upload Video Feedback</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-video text-teal-500 group-focus-within:text-teal-600"></i>
                        </div>
                        <input type="file" wire:model="video" 
                            class="file-input file-input-bordered w-full pl-11 rounded-xl border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-sm h-12" />
                    </div>
                    <div wire:loading wire:target="video" class="flex items-center gap-2 text-xs text-teal-600 font-bold mt-2 animate-pulse">
                        <i class="fas fa-spinner fa-spin"></i> Processing video upload...
                    </div>
                </div>

                <!-- Review Text Section -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Clinical Evaluation</label>
                    <textarea
                        wire:model="review"
                        rows="4"
                        class="textarea textarea-bordered w-full rounded-xl border-gray-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-sm p-4 leading-relaxed"
                        placeholder="Provide detailed feedback on patient's form, range of motion, and progress..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-50">
                    <button wire:click="submitFeedback" 
                        wire:loading.attr="disabled"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 transition-all flex items-center justify-center gap-2">
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