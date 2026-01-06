<div>
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div
        class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <input type="search" wire:model.live.debounce="search" placeholder="Search patient..."
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out">
        </div>

        <a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient.create') }}"
        @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.patient.create') }}" @endif
            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i> Add Patient
        </a>
    </div>

    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Medical
                        Record</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">BPJS No
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prosthetic
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-xs mr-3">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-bold text-slate-900">{{ $item->user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-mono font-semibold">
                                {{ $item->medical_record_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->bpjs_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->prosthetic }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="detail({{ $item->id }})" onclick="patientModal.showModal()"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors tooltip"
                                    data-tip="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient.edit', $item->id) }}"
                                @elseif(Auth::user()->role === 'doctor')
                                    href="{{ route('doctor.patient.edit', $item->id) }}" @endif
                                    class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors tooltip"
                                    data-tip="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'therapist')
                                    @php
                                        $rehabRoute = Auth::user()->role === 'doctor'
                                            ? route('doctor.patient.rehabilitation', ['id' => $item->id])
                                            : route('therapist.patient.rehabilitation', ['id' => $item->id]);
                                    @endphp
                                    <a href="{{ $rehabRoute }}"
                                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors tooltip"
                                        data-tip="Rehabilitation">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                @endif

                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors tooltip"
                                    data-tip="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="far fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                <p>No patients found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden bg-slate-50 p-4 space-y-4">
        @forelse($data as $index => $item)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                <div class="flex justify-between items-start mb-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold">
                            {{ substr($item->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $item->user->name }}</h3>
                            <p class="text-xs text-slate-500">No: {{ $index + 1 }}</p>
                        </div>
                    </div>
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded">
                        {{ $item->medical_record_number }}
                    </span>
                </div>

                <div class="space-y-2 text-sm text-slate-600 mb-4">
                    <div class="flex justify-between">
                        <span class="text-slate-400">BPJS:</span>
                        <span class="font-medium">{{ $item->bpjs_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Prosthetic:</span>
                        <span class="font-medium">{{ $item->prosthetic }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="detail({{ $item->id }})" onclick="patientModal.showModal()"
                        class="flex items-center justify-center py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium">
                        <i class="fas fa-eye"></i>
                    </button>

                    <a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient.edit', $item->id) }}" @else
                    href="{{ route('doctor.patient.edit', $item->id) }}" @endif
                        class="flex items-center justify-center py-2 bg-amber-50 text-amber-600 rounded-lg text-sm font-medium">
                        <i class="fas fa-edit"></i>
                    </a>

                    @if(Auth::user()->role === 'doctor' || Auth::user()->role === 'therapist')
                        @php
                            $rehabRoute = Auth::user()->role === 'doctor'
                                ? route('doctor.patient.rehabilitation', ['id' => $item->id])
                                : route('therapist.patient.rehabilitation', ['id' => $item->id]);
                        @endphp
                        <a href="{{ $rehabRoute }}"
                            class="flex items-center justify-center py-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-medium">
                            <i class="fas fa-stethoscope"></i>
                        </a>
                    @else
                        <div class="hidden"></div>
                    @endif

                    <button
                        class="flex items-center justify-center py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-500">No data found</div>
        @endforelse
    </div>
</div>

<dialog id="patientModal" class="modal backdrop:backdrop-blur-sm" wire:ignore.self>
    <div class="modal-box w-11/12 max-w-2xl bg-white rounded-2xl shadow-2xl p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Patient Details</h3>
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
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Full Name</label>
                        <p class="text-base font-semibold text-slate-900">{{ $patientData->user?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Medical Record
                            No.</label>
                        <p
                            class="text-base font-mono font-medium text-slate-700 bg-slate-100 inline-block px-2 py-1 rounded">
                            {{ $patientData->medical_record_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Email</label>
                        <p class="text-sm text-slate-700">{{ $patientData->user?->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Telephone</label>
                        <p class="text-sm text-slate-700">{{ $patientData->user?->telephone ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">BPJS Number</label>
                        <p class="text-base font-medium text-slate-900">{{ $patientData->bpjs_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Prosthetic Type</label>
                        <p class="text-base font-medium text-slate-900">{{ $patientData->prosthetic ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Since</label>
                        <p class="text-sm text-slate-700">
                            {{ $patientData?->prosthetic_since ? \Carbon\Carbon::parse($patientData->prosthetic_since)->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Address</label>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $patientData->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-100 pt-6">
                <h4 class="font-bold text-slate-800 mb-4 text-sm">Documents & Photos</h4>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ $patientData?->bpjs_card ? Storage::url($patientData->bpjs_card) : '#' }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold hover:bg-blue-100 transition-colors border border-blue-100">
                        <i class="far fa-id-card"></i> BPJS Card
                    </a>

                    @if($patientData && $patientData->photos)
                        @foreach($patientData->photos as $photo)
                            <a href="{{ Storage::url($photo->url) }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-bold hover:bg-slate-200 transition-colors border border-slate-200">
                                <i class="far fa-image"></i> Photo {{ $loop->iteration }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end">
            <form method="dialog">
                <button
                    class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-transform active:scale-95 text-sm"
                    wire:click="$set('patientData', null)">
                    Close
                </button>
            </form>
        </div>
    </div>
</dialog>
</div>