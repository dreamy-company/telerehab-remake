<div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10" @echo:update-channel,update-event.window="$wire.$refresh()">

    <div id="sonner-toaster"></div>

    <div x-data
        @echo:update-channel,.update-event.window="window.toast.success($event.detail.message)">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center group hover:border-blue-300 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Patients</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPatient }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>

        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center group hover:border-orange-300 transition-all">
            <div>
                <p class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-1">Action Needed</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalConsultationRequest }}</h3>
            </div>
            <div
                class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl relative">
                <i class="fas fa-inbox"></i>
                @if($totalConsultationRequest > 0)
                <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
                @endif
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center group hover:border-teal-300 transition-all">
            <div>
                <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">In Rehabilitation</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalRehabilitationPhases }}</h3>
            </div>
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-notes-medical"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Consultation Requests -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Consultation Requests</h2>
                        <span class="text-xs text-orange-600 font-bold">{{ count($dataConsultationRequest) }} pending</span>
                    </div>
                </div>
            </div>

            <div class="flex-grow overflow-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dataConsultationRequest as $item)
                        <tr class="hover:bg-orange-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($item->patient?->user?->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-slate-700">{{ $item->patient?->user?->name }}</span>
                                        <p class="text-xs text-slate-500">{{ $item->patient?->prosthetic ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="consultationModal.showModal()" wire:click="setConsultation({{ $item->id }})"
                                    class="text-xs font-bold px-3 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                                    Schedule
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-8 text-slate-400 text-sm">
                                <i class="far fa-inbox text-2xl mb-2"></i>
                                <p>No new requests</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Schedules -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Upcoming Schedules</h2>
                        <span class="text-xs text-blue-600 font-bold">{{ count($dataMeetingSchedule) }} scheduled</span>
                    </div>
                </div>
            </div>

            <div class="flex-grow overflow-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dataMeetingSchedule as $item)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($item->patient?->user?->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-slate-700">{{ $item->patient?->user?->name }}</span>
                                        <p class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="far fa-clock text-blue-500 text-[10px]"></i>
                                            {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M') : '-' }}, {{ $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i') : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right flex gap-1">

                                <button wire:click="scheduleDetail({{ $item->id }})" onclick="consultationModal.showModal()"
                                    class="text-xs font-bold px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors hover:cursor-pointer">
                                    Edit Schedule
                                </button>
                                <a href="{{ route('doctor.meeting-schedule.consultation', ['id' => $item->id]) }}"
                                    class="text-xs font-bold px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                    Start Consultation
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-8 text-slate-400 text-sm">
                                <i class="far fa-calendar text-2xl mb-2"></i>
                                <p>No upcoming schedules</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

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

                                <a href="{{ route('doctor.patient.edit', $item->id) }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors tooltip"
                                    data-tip="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                <a href="{{ route('doctor.patient.rehabilitation', ['id' => $item->id]) }}"
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

    <dialog id="consultationModal" class="modal backdrop:backdrop-blur-sm" wire:ignore.self>
        <div class="modal-box w-11/12 max-w-md bg-white rounded-2xl shadow-2xl p-0 overflow-hidden">

            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">{{ $meetingData ? 'Update Appointment' : 'Set Appointment' }}</h3>
                    <p class="text-xs text-slate-500">Confirm details for the patient.</p>
                </div>
                <form method="dialog">
                    <button class="text-slate-400 hover:text-slate-600 transition-colors"
                        wire:click="$set('patientData', null)">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </form>
            </div>

            <div class="p-6 space-y-5">

                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-600 shadow-sm border border-blue-50">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-0.5">Patient</p>
                        <h4 class="font-bold text-slate-800">
                            {{ $patientData ? $patientData->patient?->user?->name : 'Loading...' }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">Medical Record: <span
                                class="font-mono">{{ $patientData->patient->medical_record_number ?? '-' }}</span></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Date <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" required wire:model="date"
                                class="w-full pl-3 pr-3 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none text-sm font-medium text-slate-700 bg-white">
                        </div>
                        @error('date') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase">Time <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="time" required wire:model="time"
                                class="w-full pl-3 pr-3 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none text-sm font-medium text-slate-700 bg-white">
                        </div>
                        @error('time') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Meeting Type</label>
                    <input type="hidden" wire:model="category" value="offline">
                    <div
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center text-slate-500 cursor-not-allowed select-none">
                        <i class="fas fa-hospital-user mr-3 text-primary-500"></i>
                        <span class="text-sm font-semibold">Offline (In-Person Consultation)</span>
                        <i class="fas fa-lock ml-auto text-xs text-slate-300"></i>
                    </div>
                    @error('category') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Location / Room <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-slate-400"></i>
                        </div>
                        <input type="text" required wire:model="location" placeholder="e.g. Room 102, Main Building"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none text-sm font-medium text-slate-700">
                    </div>
                    @error('location') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                <form method="dialog">
                    <button
                        class="px-5 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-200 text-sm transition-colors"
                        wire:click="$set('patientData', null)">
                        Cancel
                    </button>
                </form>
                <button type="button" wire:click="{{ $meetingData ? 'updateSchedule' : 'saveSchedule' }}" wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/40 transform active:scale-95 transition-all text-sm flex items-center gap-2">
                    <span wire:loading.remove wire:target="{{ $meetingData ? 'updateSchedule' : 'saveSchedule' }}"><i class="fas fa-check"></i> {{ $meetingData ? 'Update' : 'Confirm' }} Schedule</span>
                    <span wire:loading wire:target="{{ $meetingData ? 'updateSchedule' : 'saveSchedule' }}"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                </button>
            </div>

        </div>
    </dialog>



</div>