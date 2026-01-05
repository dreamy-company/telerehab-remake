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
        <a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient.create') }}" @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.patient.create') }}" @endif class="w-full sm:w-auto px-6 py-2 bg-primary-500 text-white rounded-lg transition-colors hover:cursor-pointer hover:bg-primary-600">Add Patient</a>
    </div>

    <div>
        <table class="w-full border-collapse table-auto">
            <thead class="bg-primary-400">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">No</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Medical Record Number</th>
                    <th class="border border-gray-300 px-4 py-2">BPJS Number</th>
                    <th class="border border-gray-300 px-4 py-2">Prosthetic</th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->medical_record_number }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->bpjs_number }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $item->prosthetic }}</td>

                    <td class="border border-gray-300 px-4 py-2">
                        <!-- change popover-1 and --anchor-1 names. Use unique names for each dropdown -->
                        <div class="dropdown dropdown-start">
                            <div tabindex="0" role="button" class="btn m-1 bg-[#94B9C5] text-white">Action</div>
                            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-10 w-40 p-2 shadow-sm">
                                <li><a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient.edit', $item->id) }}" @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.patient.edit', $item->id) }}" @endif class="bg-yellow-400 hover:bg-yellow-500 "><i class="fas fa-edit"></i> Edit</a></li>
                                <li><button wire:click="detail({{ $item->id }})" onclick="patientModal.showModal()" class="bg-blue-400 hover:bg-blue-500 "><i class="fas fa-eye"></i> Detail</button></li>
                                <li>
                                    @if(Auth::user()->role === 'doctor')
                                    <a href="{{ route('doctor.patient.rehabilitation', ['id' => $item->id]) }}" class="bg-primary-600 hover:bg-primary-700 text-white"><i class="fas fa-stethoscope"></i> Rehabilitation</a>
                                    @elseif(Auth::user()->role === 'therapist')
                                    <a href="{{ route('therapist.patient.rehabilitation', ['id' => $item->id]) }}" class="bg-primary-600 hover:bg-primary-700 text-white"><i class="fas fa-stethoscope"></i> Rehabilitation</a>
                                    @endif
                                </li>
                                <li><a href="#" class="bg-red-500 hover:bg-red-600 text-white"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">No data found</td>
                </tr>
                @endforelse

                <dialog id="patientModal" class="modal" wire:ignore.self>
                    <div class="modal-box">
                        <h3 class="font-bold text-lg mb-4">Patient Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Name:</span>
                                <span>{{ $patientData->user?->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Medical Record Number:</span>
                                <span>{{ $patientData->medical_record_number ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">BPJS Number:</span>
                                <span>{{ $patientData->bpjs_number ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Email:</span>
                                <span>{{ $patientData->user?->email ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Telephone:</span>
                                <span>{{ $patientData->user?->telephone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">BPJS Card:</span>
                                <a href="{{ $patientData?->bpjs_card ? Storage::url($patientData->bpjs_card) : '#' }}" target="_blank" class="btn bg-[#94B9C5] text-white"><i class="fas fa-image"></i> View</a>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Prosthetic:</span>
                                <span>{{ $patientData->prosthetic ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="font-semibold text-gray-700">Prosthetic Since:</span>
                                <span>{{ $patientData?->prosthetic_since ? \Carbon\Carbon::parse($patientData->prosthetic_since)->format('d F Y') : '-' }}</span>
                            </div>
                            <div class="flex flex-col border-b pb-2">
                                <span class="font-semibold text-gray-700 mb-1">Address:</span>
                                <span class="text-sm text-gray-600">{{ $patientData->address ?? '-' }}</span>
                            </div>
                            @if($patientData)
                            <span class="font-semibold text-gray-700">Patient Condition Photo:</span>
                            <div class="flex justify-start border-b pb-2">
                                @foreach($patientData->photos as $photo)
                                <a href="{{ Storage::url($photo->url) }}" target="_blank" class="btn bg-[#94B9C5] text-white"><i class="fas fa-image"></i> View</a>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="modal-action">
                            <form method="dialog">
                                <button class="btn" wire:click="$set('patientData', null)">Close</button>
                            </form>
                        </div>
                    </div>
                </dialog>
            </tbody>
        </table>
    </div>
</div>