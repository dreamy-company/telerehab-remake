<section class="space-y-8 animate-in fade-in duration-500">
    <a href="{{ route(Auth::user()->role === 'doctor' ? 'doctor.patient' : 'therapist.patient') }}" class="btn bg-primary-500 text-sm font-bold text-white hover:bg-primary-600 mb-4 rounded-md"><i class="fas fa-arrow-left mr-2"></i> Back to Patients</a>

    <div class="card-modern p-8">
        <h4 class="font-bold text-xl mb-6 text-gray-800"><i class="fas fa-comments text-teal-500 mr-2"></i> Monitoring & Feedback</h4>
        <p class="text-xs text-gray-400 mb-4">Exercise videos from patient</p>

        @foreach ($routineResults as $item)
        <!-- Video Item -->
        <div class="p-4 border border-gray-100 rounded-2xl mb-4 hover:shadow-lg transition-all bg-white">
            <div class="flex gap-4 mb-3">
                <a href="{{ Storage::url($item->video_url) }}" class="w-20 h-14 bg-black rounded-lg flex items-center justify-center text-white"><i class="fas fa-play"></i></a>
                <div>
                    <p class="font-bold text-sm text-gray-800">{{ $item->routine?->rehabilitation?->name ?? 'Unknown Exercise' }}</p>
                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($item->date)->format('d F Y') }}</p>
                    
                    @if($item->ratingResponse && $item->ratingResponse->doctor)
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 flex gap-3 items-start">
                        <div class="w-6 h-6 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-md"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-blue-700 uppercase mb-1">{{ $item->ratingResponse?->doctor?->name }}</p>
                            <p class="text-xs text-blue-900 leading-relaxed italic">"{{ $item->ratingResponse?->review_doctor }}"</p>
                            <a href="{{ Storage::url($item->ratingResponse?->video_doctor) }}" target="_blank" class="btn bg-blue-500 text-white text-xs rounded-md px-2 py-1">View Video</a>
                        </div>
                    </div>
                    @endif
                    @if($item->ratingResponse && $item->ratingResponse->therapist)
                    <div class="bg-orange-50 p-3 rounded-xl border border-orange-100 flex gap-3 items-start">
                        <div class="w-6 h-6 rounded-full bg-orange-200 flex items-center justify-center text-orange-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-nurse"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-orange-700 uppercase mb-1">{{$item->ratingResponse?->therapist?->name}}</p>
                            <p class="text-xs text-orange-900 leading-relaxed italic">"{{$item->ratingResponse?->review_therapist}}"</p>
                            <a href="{{ Storage::url($item->ratingResponse?->video_therapist) }}" target="_blank" class="btn bg-orange-500 text-white text-xs rounded-md px-2 py-1">View Video</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>


            <button wire:click="openModal({{ $item->id }})" onclick="feedbackModal.showModal()" class="w-full py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-xs font-semibold transition-colors duration-200">Review</button>
        </div>
        @endforeach
    </div>
    <dialog id="feedbackModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Submit Feedback</h3>

            <input type="file" wire:model="video" class="file-input file-input-bordered w-full mb-3" />

            <textarea
                wire:model="review"
                class="textarea textarea-bordered w-full"
                placeholder="Write your review..."></textarea>

            <div class="modal-action">
                <button wire:click="submitFeedback" class="btn bg-primary-500 text-white">Submit</button>
                <form method="dialog">
                    <button class="btn">Cancel</button>
                </form>
            </div>
        </div>
    </dialog>


</section>