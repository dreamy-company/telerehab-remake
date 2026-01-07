<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total Patient</p>
                    <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $totalPatient }}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Total Rehabilitation Phases</p>
                    <h3 class="text-3xl font-extrabold text-orange-900 mt-1">{{ $totalRehabilitations }}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500"><i class="fas fa-notes-medical"></i></div>
            </div>
        </div>
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Total Rehabilitations</p>
                    <h3 class="text-3xl font-extrabold text-teal-900 mt-1">{{ $totalRehabilitationPhases }}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-500"><i class="fas fa-procedures"></i></div>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden my-4">

        <div
            class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-folder-open text-slate-400"></i> Patient Database
            </h2>

            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search"></i>
                </div>
                <input type="search" wire:model.live.debounce="search"
                    class="block w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="Search patient database...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-10">
                            No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Patient Profile</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Contacts</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Prosthetic</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($dataPatients as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-400 font-medium">{{ $index + 1 }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm mr-3 border border-indigo-200">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $item->user->name }}</div>
                                    <div
                                        class="text-xs text-slate-500 font-mono bg-slate-100 inline-block px-1 rounded mt-0.5">
                                        {{ $item->medical_record_number }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-600 flex items-center gap-1">
                                    <i class="far fa-id-card text-slate-400"></i> {{ $item->bpjs_number }}
                                </span>
                                <span class="text-xs text-slate-600 flex items-center gap-1">
                                    <i class="fas fa-phone text-slate-400 text-[10px]"></i>
                                    {{ $item->user->telephone ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100 max-w-[150px] truncate">
                                {{ $item->prosthetic }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-dumbbell text-slate-400"></i>
                                <span class="font-bold text-slate-700">
                                    {{ $item->active_routines_count }}
                                </span>
                                <span class="text-xs text-slate-500">Routines Submitted</span>
                                @if($item->rehab_routine_id)
                                <a href="{{ route('doctor.patient.rehabilitation.exercise', ['id' => $item->id, 'rehabRoutineId' => $item->rehab_routine_id]) }}"
                                    class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                                @endif
                            </div>

                            @if($item->latest_routine_at)
                            <p class="text-[10px] text-slate-400 mt-1">
                                Last update: {{ \Carbon\Carbon::parse($item->latest_routine_at)->diffForHumans() }}
                            </p>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div
                                class="flex items-center justify-end gap-2 opacity-100 lg:opacity-60 lg:group-hover:opacity-100 transition-opacity">

                                <button wire:click="detail({{ $item->id }})" onclick="patientModal.showModal()"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors tooltip"
                                    data-tip="View Details">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>

                                <a href="{{ route('therapist.patient.rehabilitation', ['id' => $item->id]) }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors tooltip"
                                    data-tip="Rehabilitation Program">
                                    <i class="fas fa-stethoscope text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="far fa-folder-open text-3xl mb-2"></i>
                            <p class="text-sm">No patients found in database.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-xs text-slate-500">
            Showing {{ count($dataPatients) }} patients
        </div>
    </div>

    <dialog id="patientModal" class="modal backdrop:backdrop-blur-sm" wire:ignore.self>
        <div class="modal-box w-11/12 max-w-2xl bg-white rounded-2xl shadow-2xl p-0 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-slate-600 shadow-sm border border-slate-200">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Patient Details</h3>
                        <p class="text-xs text-slate-500">Full medical profile information.</p>
                    </div>
                </div>
                <form method="dialog">
                    <button class="text-slate-400 hover:text-slate-600 transition-colors"
                        wire:click="$set('patientData', null)">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </form>
            </div>

            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="group">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full
                                Name</label>
                            <p class="text-base font-bold text-slate-800">{{ $patientData->user?->name ?? '-' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Record
                                Number</label>
                            <p
                                class="text-sm font-mono font-medium text-slate-600 bg-slate-100 inline-block px-2 py-0.5 rounded border border-slate-200">
                                {{ $patientData->medical_record_number ?? '-' }}
                            </p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact
                                Info</label>
                            <p class="text-sm text-slate-600"><i class="far fa-envelope mr-1"></i>
                                {{ $patientData->user?->email ?? '-' }}
                            </p>
                            <p class="text-sm text-slate-600 mt-1"><i class="fas fa-phone mr-1"></i>
                                {{ $patientData->user?->telephone ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="group">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">BPJS
                                Number</label>
                            <p class="text-sm font-medium text-slate-800">{{ $patientData->bpjs_number ?? '-' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Prosthetic &
                                Since</label>
                            <p class="text-sm font-medium text-slate-800">{{ $patientData->prosthetic ?? '-' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Installed:
                                {{ $patientData?->prosthetic_since ? \Carbon\Carbon::parse($patientData->prosthetic_since)->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="group">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Address</label>
                            <p class="text-sm text-slate-600 leading-snug">{{ $patientData->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                        <i class="far fa-images text-primary-500"></i> Medical Documents
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $patientData?->bpjs_card ? Storage::url($patientData->bpjs_card) : '#' }}"
                            target="_blank"
                            class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-white hover:border-primary-300 hover:text-primary-600 transition-all">
                            <i class="far fa-id-card text-lg"></i> BPJS Scan
                        </a>
                        @if($patientData && $patientData->photos)
                        @foreach($patientData->photos as $photo)
                        <a href="{{ Storage::url($photo->url) }}" target="_blank"
                            class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-white hover:border-primary-300 hover:text-primary-600 transition-all">
                            <i class="far fa-image text-lg"></i> Photo {{ $loop->iteration }}
                        </a>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end">
                <form method="dialog">
                    <button
                        class="px-6 py-2 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-700 transition-transform active:scale-95 text-sm"
                        wire:click="$set('patientData', null)">Close</button>
                </form>
            </div>
        </div>
    </dialog>
</div>