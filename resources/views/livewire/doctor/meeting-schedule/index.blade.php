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
                    <th class="border border-gray-300 px-4 py-2">Request Date</th>
                    <th class="border border-gray-300 px-4 py-2">Schedule At</th>
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
                    <td class="border border-gray-300 px-4 py-2">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d F Y') : '' }}, {{ \Carbon\Carbon::parse($item->time)->format('H:i') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="{{ route('doctor.meeting-schedule.consultation', ['id' => $item->id]) }}" class="btn m-1 bg-[#94B9C5] text-white" > Start Consultation</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="border border-gray-300 px-4 py-2 text-center">No data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
      

    </div>
</div>