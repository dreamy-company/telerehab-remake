<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50">
        <h2 class="text-lg font-bold text-slate-800 mb-3 sm:mb-0">Consultation Requests</h2>

        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="search" wire:model.live.debounce="search"
                class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150 ease-in-out sm:text-sm"
                placeholder="Search patient name...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient
                        Name</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prosthetic
                        Need</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Requested
                        On</th>
                    <th scope="col"
                        class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-9 w-9 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-700 font-bold text-xs mr-3 shadow-sm border border-primary-100">
                                    {{ substr($item->patient?->user?->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-slate-900">
                                    {{ $item->patient?->user?->name ?? 'Unknown' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $item->patient?->prosthetic ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-700">
                                    {{ $item->created_at->format('d M Y') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="consultationModal.showModal()" wire:click="setConsultation({{ $item->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold uppercase tracking-wide rounded-lg shadow-sm hover:shadow-md transition-all transform active:scale-95">
                                <i class="fas fa-calendar-plus"></i>
                                Set Schedule
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="far fa-calendar-times text-4xl mb-3"></i>
                                <p class="text-sm font-medium">No consultation requests found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <dialog id="consultationModal" class="modal backdrop:backdrop-blur-sm" wire:ignore.self>
        <div class="modal-box w-11/12 max-w-md bg-white rounded-2xl shadow-2xl p-0 overflow-hidden">

            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Set Appointment</h3>
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
                            {{ $patientData ? $patientData->patient->user->name : 'Loading...' }}
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
                <button wire:click="saveSchedule"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/40 transform active:scale-95 transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-check"></i> Confirm Schedule
                </button>
            </div>

        </div>
    </dialog>
</div>