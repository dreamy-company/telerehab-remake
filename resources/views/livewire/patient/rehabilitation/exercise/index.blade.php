<section class="space-y-8 animate-in fade-in duration-500">

    <!-- Video Panduan & Upload Video Side by Side -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Video Tutorial (lebih besar) -->
        <div id="videoTutorial" class="lg:col-span-2 bg-white shadow-lg rounded-md p-5 w-full">
            <h4 class="font-bold text-gray-800 mb-4 px-3 flex items-center gap-2">
                <i class="fas fa-video text-teal-500"></i> Video Tutorial For {{ $rehabData->rehabilitation->name }}
            </h4>

            <div class="relative aspect-video rounded-md overflow-hidden">
                <iframe
                    src="{{ $rehabData->rehabilitation->video_url }}"
                    class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer;  clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Upload Zone (lebih kecil) -->
        <div id="uploadZone"
            class="bg-white shadow-lg rounded-md p-10 border-4 border-dashed border-teal-50
                flex flex-col items-center text-center py-20 w-full">
            <div class="w-24 h-24 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center text-4xl mb-6 shadow-inner">
                <i class="fas fa-camera"></i>
            </div>
            <h4 class="text-xl font-extrabold text-gray-900 mb-2">Upload Video</h4>
            <p class="text-sm text-gray-400 mb-10 leading-relaxed px-4">
                Record your exercise so that doctors & therapists can monitor your progress.
            </p>

            @if($rehabData->routineResults->where('date', now()->format('Y-m-d'))->count() > 0)
            <p class="text-green-600 font-bold mb-4"><i class="fas fa-check-circle mr-2"></i> Exercise Video for today has been uploaded. Thank you!</p>
            @else
            <button onclick="uploadModal.showModal()" class="btn bg-primary-500 text-white hover:bg-primary-600 rounded-lg w-full justify-center">
                <i class="fas fa-circle-dot mr-2"></i> UPLOAD VIDEO
            </button>
            @endif
        </div>

    </div>

    <dialog id="uploadModal" class="modal" wire:ignore.self>
        <form method="dialog" class="modal-box w-full max-w-lg">
            <h3 class="font-bold text-lg mb-4">Upload Video Latihan</h3>

            <div>
                <label for="video" class="block text-sm font-medium text-gray-700 mb-1">Choose Your Exercise Video</label>
                <div class="flex items-center justify-center w-full">
                    <label for="video" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-teal-500 rounded-lg cursor-pointer hover:bg-teal-50 transition">
                        <i class="fas fa-upload text-teal-500 text-3xl mb-2"></i>
                        <span class="text-gray-500">Drag & drop your video here or click to select</span>
                        <input type="file" id="video" name="video" wire:model="video" accept="video/*" class="hidden">
                    </label>
                </div>
                @error('video')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="">
                <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1 mt-4">Your Feedback</label>
                <textarea id="feedback" name="feedback" wire:model="feedback" rows="3" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Add Your Feedback"></textarea>
                @error('feedback')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="modal-action">
                <a wire:click="uploadVideo" class="btn bg-primary-500 text-white hover:bg-primary-600 rounded-lg">Upload</a>
                <button type="button" class="btn btn-ghost rounded-lg" onclick="uploadModal.close()">Batal</button>
            </div>
        </form>
    </dialog>


    <!-- Riwayat Upload & Feedback Full Width Below -->
    <div class="bg-white shadow-lg rounded-md p-8">
        <h4 class="font-bold text-xl mb-6 flex items-center gap-3"><i class="fas fa-history text-teal-500"></i> Video History & Feedback</h4>
        @forelse ($results as $result)
        <div class="border-b border-gray-100 pb-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-40 h-24 rounded-xl overflow-hidden relative flex-shrink-0 bg-black">
                    <a href="{{ Storage::url($result->video_url) }}"
                        target="_blank"
                        class="absolute inset-0 flex items-center justify-center
              bg-black/40  transition
              text-white text-xl">
                        <i class="fas fa-play"></i>
                    </a>
                </div>

                <div class="flex-1 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="font-bold text-gray-800 text-sm">{{ $result->routine?->rehabilitation?->name ?? 'Unknown Exercise' }}</h5>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ \Carbon\Carbon::parse($result->date)->format('d F Y') }}</p>
                        </div>
                        @if($result->ratingResponse)
                        <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">Reviewed</span>
                        @endif
                    </div>
                    @if($result->ratingResponse && $result->ratingResponse->doctor)
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 flex gap-3 items-start">
                        <div class="w-6 h-6 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-md"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-blue-700 uppercase mb-1">{{ $result->ratingResponse?->doctor?->name }}</p>
                            <p class="text-xs text-blue-900 leading-relaxed italic">"{{ $result->ratingResponse?->review_doctor }}"</p>
                            <a href="{{ Storage::url($result->ratingResponse?->video_doctor) }}" target="_blank" class="btn bg-blue-500 text-white text-xs rounded-md px-2 py-1">View Video</a>
                        </div>
                    </div>
                    @endif
                    @if($result->ratingResponse && $result->ratingResponse->therapist)
                    <div class="bg-orange-50 p-3 rounded-xl border border-orange-100 flex gap-3 items-start">
                        <div class="w-6 h-6 rounded-full bg-orange-200 flex items-center justify-center text-orange-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-nurse"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-orange-700 uppercase mb-1">{{$result->ratingResponse?->therapist?->name}}</p>
                            <p class="text-xs text-orange-900 leading-relaxed italic">"{{$result->ratingResponse?->review_therapist}}"</p>
                            <a href="{{ Storage::url($result->ratingResponse?->video_therapist) }}" target="_blank" class="btn bg-orange-500 text-white text-xs rounded-md px-2 py-1">View Video</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p class="text-center italic text-sm">No video upload history available.</p>
        @endforelse
    </div>

</section>