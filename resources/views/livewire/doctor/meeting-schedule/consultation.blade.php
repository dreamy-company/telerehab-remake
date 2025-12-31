<div class="shadow-md text-center bg-white">


    <!-- Header -->
    <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-primary-500 to-primary-400 text-white">
        <h3 class="font-bold text-lg flex items-center gap-2">
            <i class="fas fa-calendar-check"></i> Consultaion Schedule for {{ $patientData?->patient->user->name }}
        </h3>
    </div>

    <div class="flex flex-col lg:flex-row">

        <!-- LEFT : PATIENT INFO -->
        <aside class="lg:w-1/3 bg-gray-50 p-6 border-r space-y-5">

            <div class="flex items-start justify-center gap-4">
                <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 text-xl">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="flex flex-col items-start">
                    <p class="font-bold text-lg">{{ $patientData?->patient->user->name }}</p>
                    <p class="font-semibold">Medical Record: {{ $patientData?->patient->medical_record_number }}</p>
                    <p><span class="font-semibold">BPJS:</span> {{ $patientData?->patient->bpjs_number }}</p>
                    <p><span class="font-semibold">Prosthetic:</span> {{ $patientData?->patient->prosthetic }}</p>
                    <p><span class="font-semibold">Since:</span> {{ $patientData ? \Carbon\Carbon::parse($patientData->patient->prosthetic_since)->format('d F Y') : '-' }}</p>
                </div>
            </div>

            <div class="divider"></div>

            <div class="space-y-3 text-sm">

            </div>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ $patientData ? Storage::url($patientData->patient->bpjs_card) : '#' }}"
                    target="_blank"
                    class="btn btn-sm btn-outline btn-primary">
                    <i class="fas fa-id-card"></i> BPJS Card
                </a>

                @foreach($patientData?->patient->photos ?? [] as $photo)
                <a href="{{ Storage::url($photo->url) }}"
                    target="_blank"
                    class="btn btn-sm btn-outline btn-secondary">
                    <i class="fas fa-image"></i> Condition
                </a>
                @endforeach
            </div>

        </aside>

        <!-- RIGHT : FORM -->
        <section class="lg:w-2/3 p-6 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div wire:ignore>
                    <label class="label font-semibold">Phase *</label>
                    <select wire:model="phase"
                        class="select2 select-bordered w-full @error('phase') select-error @enderror" onchange="@this.set('phase',this.value);">
                        <option value="">Select Phase</option>
                        @foreach ($phases as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('phase') <p class="text-error text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label font-semibold">Rehabilitation *</label>
                    @if($phase)
                    <div>
                        <select
                            wire:model="rehabilitation"
                            class="select2-rehab select-bordered w-full @error('rehabilitation') select-error @enderror">
                            <option value="">Select Rehabilitation</option>
                            @foreach ($rehabilitations as $rehab)
                            <option value="{{ $rehab->id }}">{{ $rehab->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('rehabilitation') <p class="text-error text-sm">{{ $message }}</p> @enderror
                    @else
                    <p>Please select a phase first.</p>
                    @endif
                </div>

                <div>
                    <label class="label font-semibold">Target Date *</label>
                    <input type="date"
                        wire:model="targetDate"
                        class="input input-bordered w-full @error('targetDate') input-error @enderror">
                    @error('targetDate') <p class="text-error text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label font-semibold">Goal *</label>
                    <input type="text"
                        wire:model="goal"
                        class="input input-bordered w-full @error('goal') input-error @enderror"
                        placeholder="Expected recovery goal">
                    @error('goal') <p class="text-error text-sm">{{ $message }}</p> @enderror
                </div>



            </div>
            <div class="flex flex-col items-start">
                <label class="label font-semibold">Diagnosis *</label>
                <textarea
                    wire:model="diagnosis"
                    class="textarea textarea-bordered w-full @error('diagnosis') textarea-error @enderror"
                    placeholder="Enter diagnosis"></textarea>
                @error('diagnosis') <p class="text-error text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col items-start">
                <label class="label font-semibold">Medicine Recommendation *</label>
                <input type="text"
                    wire:model="medicine"
                    class="input input-bordered w-full @error('medicine') input-error @enderror" placeholder="Enter Medicine Recommendation">
                @error('medicine') <p class="text-error text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button class="btn btn-ghost" onclick="consultationModal.close()">Cancel</button>
                <button class="btn bg-primary-300" wire:click="saveSchedule">
                    <i class="fas fa-save mr-1"></i> Save Consultation
                </button>
            </div>

        </section>
    </div>

</div>