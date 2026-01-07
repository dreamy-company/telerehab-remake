<div class="max-w-7xl mx-auto my-6">
    <div
        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col lg:flex-row min-h-[600px]">

        <aside class="lg:w-1/3 bg-slate-900 text-white p-8 flex flex-col relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-primary-600 rounded-full blur-3xl opacity-20 pointer-events-none">
            </div>

            <div class="relative z-10">
                <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-6">Patient Information</h3>

                <div class="flex items-center gap-5 mb-8">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-2xl shadow-lg shadow-primary-500/30">
                        {{ substr($patientData?->patient->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold leading-tight">{{ $patientData?->patient->user->name }}</h2>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700 mt-2">
                            {{ $patientData?->patient->medical_record_number }}
                        </span>
                    </div>
                </div>

                <div class="space-y-5 border-t border-slate-800 pt-6">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">BPJS Number</p>
                        <p class="text-base font-mono text-slate-200">{{ $patientData?->patient->bpjs_number ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Prosthetic Used</p>
                        <p class="text-sm text-slate-200">{{ $patientData?->patient->prosthetic ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Since</p>
                        <p class="text-sm text-slate-200">
                            {{ $patientData ? \Carbon\Carbon::parse($patientData->patient->prosthetic_since)->format('d F Y') : '-' }}
                        </p>
                    </div>
                </div>

                @if($currentRehabilitation)
                    <div class="mt-8 bg-slate-800/50 rounded-xl p-4 border border-slate-700 backdrop-blur-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-xs font-bold text-emerald-400 uppercase">Active Program</p>
                        </div>
                        <p class="text-sm font-semibold text-white leading-relaxed">
                            {{ $currentRehabilitation->rehabilitation->name ?? 'N/A' }}
                        </p>
                    </div>
                @endif

                <div class="mt-auto pt-8 flex gap-3">
                    <a href="{{ $patientData ? Storage::url($patientData->patient->bpjs_card) : '#' }}" target="_blank"
                        class="flex-1 btn btn-sm bg-slate-800 hover:bg-slate-700 border-slate-700 text-slate-300 font-normal">
                        <i class="fas fa-id-card mr-2"></i> BPJS
                    </a>
                    @if(count($patientData?->patient->photos ?? []) > 0)
                        <a href="{{ Storage::url($patientData->patient->photos[0]->url) }}" target="_blank"
                            class="flex-1 btn btn-sm bg-slate-800 hover:bg-slate-700 border-slate-700 text-slate-300 font-normal">
                            <i class="fas fa-image mr-2"></i> Photo
                        </a>
                    @endif
                </div>
            </div>
        </aside>

        <section class="lg:w-2/3 bg-white p-8 lg:p-10 relative">
            <div class="max-w-3xl mx-auto space-y-8">

                <div class="flex items-center justify-between border-b border-slate-100 pb-5">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-primary-600"></i> Consultation Result
                    </h3>
                    <span class="text-xs font-medium text-slate-400">{{ now()->format('d M Y') }}</span>
                </div>

                <div class="space-y-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-slate-700">Diagnosis Summary <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <textarea wire:model="diagnosis" rows="3"
                            class="textarea w-full bg-slate-50 border-slate-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-base"
                            placeholder="Describe the patient's condition..."></textarea>
                        @error('diagnosis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-bold text-slate-700">Medicine Recommendation <span
                                    class="text-red-500">*</span></span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-capsules text-slate-400"></i>
                            </div>
                            <input type="text" wire:model="medicine"
                                class="input w-full pl-10 bg-slate-50 border-slate-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                placeholder="e.g. Paracetamol 500mg (3x1)">
                        </div>
                        @error('medicine') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="label">
                        <span class="label-text font-bold text-slate-700">Rehabilitation Action <span
                                class="text-red-500">*</span></span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="rehabilitationStatus" value="continue"
                                class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-slate-100 bg-white hover:border-slate-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2">
                                <i
                                    class="fas fa-forward text-xl text-slate-400 peer-checked:text-blue-600 group-hover:text-slate-600"></i>
                                <span
                                    class="font-bold text-sm text-slate-600 peer-checked:text-blue-800">Continue</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="rehabilitationStatus" value="complete"
                                class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-slate-100 bg-white hover:border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2">
                                <i
                                    class="fas fa-check-circle text-xl text-slate-400 peer-checked:text-emerald-600 group-hover:text-slate-600"></i>
                                <span
                                    class="font-bold text-sm text-slate-600 peer-checked:text-emerald-800">Complete</span>
                            </div>
                        </label> -->

                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="rehabilitationStatus" value="new" class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-slate-100 bg-white hover:border-slate-300 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2">
                                <i
                                    class="fas fa-plus-circle text-xl text-slate-400 peer-checked:text-primary-600 group-hover:text-slate-600"></i>
                                <span class="font-bold text-sm text-slate-600 peer-checked:text-primary-800">New
                                    Program</span>
                            </div>
                        </label>
                    </div>
                    @error('rehabilitationStatus') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                @if($rehabilitationStatus === 'new')
                    <div
                        class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100 animate-in fade-in slide-in-from-top-4 duration-300">
                        <h4 class="text-sm font-bold text-indigo-800 uppercase tracking-wide mb-5 flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i> Setup New Program
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="form-control w-full">
                                <label class="label py-1">
                                    <span class="label-text font-bold text-slate-700">1. Select Phase <span
                                            class="text-red-500">*</span></span>
                                </label>
                                <select wire:model.live="phase"
                                    class="select w-full bg-white border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Choose Phase --</option>
                                    @foreach ($phases as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('phase') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full relative">
                                <label class="label py-1">
                                    <span class="label-text font-bold text-slate-700">2. Select Rehabilitation <span
                                            class="text-red-500">*</span></span>
                                    <span wire:loading wire:target="phase"
                                        class="loading loading-spinner loading-xs text-indigo-500"></span>
                                </label>

                                <select wire:model="rehabilitation"
                                    class="select w-full bg-white border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-slate-100 disabled:text-slate-400"
                                    @if(!$phase) disabled @endif>
                                    <option value="">-- Choose Program --</option>
                                    @foreach ($rehabilitations as $rehab)
                                        <option value="{{ $rehab->id }}">{{ $rehab->name }}</option>
                                    @endforeach
                                </select>

                                @if(!$phase)
                                    <p class="text-xs text-slate-400 mt-1 italic">Please select a phase first.</p>
                                @endif
                                @error('rehabilitation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label py-1">
                                    <span class="label-text font-bold text-slate-700">Target Completion <span
                                            class="text-red-500">*</span></span>
                                </label>
                                <input type="date" wire:model="targetDate"
                                    class="input w-full bg-white border-slate-300 focus:border-indigo-500">
                                @error('targetDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control w-full">
                                <label class="label py-1">
                                    <span class="label-text font-bold text-slate-700">Specific Goal <span
                                            class="text-red-500">*</span></span>
                                </label>
                                <input type="text" wire:model="goal"
                                    class="input w-full bg-white border-slate-300 focus:border-indigo-500"
                                    placeholder="e.g. Full range of motion">
                                @error('goal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                @endif

                <div class="pt-6 mt-8 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('doctor.meeting-schedule') }}"
                        class="btn btn-ghost text-slate-500 hover:bg-slate-100 font-bold">
                        Cancel
                    </a>
                    <button wire:click="saveSchedule" wire:loading.attr="disabled"
                        class="btn bg-primary-600 hover:bg-primary-700 text-white border-none font-bold px-8 shadow-lg shadow-primary-500/30">
                        <span wire:loading.remove><i class="fas fa-save mr-2"></i> Save Consultation</span>
                        <span wire:loading><span class="loading loading-spinner loading-sm"></span> Saving...</span>
                    </button>
                </div>

            </div>
        </section>
    </div>
</div>