<div class="shadow-md p-12 text-center bg-white">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <label class="input w-full sm:w-auto">
            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g
                    stroke-linejoin="round"
                    stroke-linecap="round"
                    stroke-width="2.5"
                    fill="none"
                    stroke="currentColor">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </g>
            </svg>
            <input type="search" required placeholder="Search" wire:model.live.debounce="search" />
        </label>
    </div>

    <div>
        <table class="w-full border-collapse table-auto">
            <thead class="bg-primary-400">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">No</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Prosthetic</th>
                    <th class="border border-gray-300 px-4 py-2">Request Date </th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->patient?->user?->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->patient?->prosthetic }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->created_at->format('d F Y, H:i') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button class="btn m-1 bg-[#94B9C5] text-white" onclick="consultationModal.showModal()" wire:click="setConsultation({{ $item->id }})"> Create Schedule</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">No data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <dialog id="consultationModal" class="modal" wire:ignore.self>
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Set Schedule</h3>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-col items-start">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Patient Name</label>
                        <input type="text" disabled class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" value="{{ $patientData ? $patientData->patient->user->name : '' }}">
                    </div>
                    <div class="flex w-full gap-4 justify-between">
                        <div class="w-1/2 flex flex-col items-start">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date<span class="text-red-500">*</span> @error('date') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </label>
                            <input type="date" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" wire:model="date">
                        </div>
                        <div class="w-1/2 flex flex-col items-start">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Time<span class="text-red-500">*</span> @error('time') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </label>
                            <input type="time" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" wire:model="time">
                        </div>
                    </div>
                    <div class="flex flex-col items-start">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category<span class="text-red-500">*</span> @error('category') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="flex gap-4">
                            <label>
                                <input type="radio" wire:model="category" value="offline" class="mr-2"> Offline
                            </label>
                            <label>
                                <input type="radio" wire:model="category" value="online" class="mr-2"> Online
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col items-start">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location <span class="text-red-500">*</span> @error('location') <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </label>
                        <input type="text" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" wire:model="location">
                    </div>
                </div>

                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn" wire:click="$set('patientData', null)">Close</button>
                    </form>
                    <button class="btn bg-primary-400" wire:click="saveSchedule">Save</button>
                </div>
            </div>
        </dialog>
    </div>
</div>