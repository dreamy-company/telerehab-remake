<section class="space-y-8 animate-in fade-in duration-500">
    <a href="{{ route(Auth::user()->role === 'doctor' ? 'doctor.patient' : 'therapist.patient') }}" class="btn bg-primary-500 text-sm font-bold text-white hover:bg-primary-600 mb-4 rounded-md"><i class="fas fa-arrow-left mr-2"></i> Back to Patients</a>

    <div class="card-modern p-8 bg-white border-l-8 border-teal-500">
        <div class="flex items-start justify-between">
            <div class="flex gap-6">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Stefani" class="w-24 h-24 rounded-2xl bg-gray-100">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">{{ $patientData->user?->name }}</h2>
                    <p class="text-gray-500 font-medium">{{$patientData->medical_record_number}}</p>
                    <div class="flex gap-2 mt-4">
                        <!-- <button class="px-4 py-2 bg-teal-100 text-teal-700 rounded-lg text-xs font-bold" onclick="showToast('Sesi Dimulai', 'Membuka ruang konsultasi video...')"><i class="fas fa-video mr-2"></i> Mulai Konsultasi</button>
                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold"><i class="fas fa-file-medical mr-2"></i> Rekam Medis</button> -->
                    </div>
                </div>
            </div>
            <!-- <div class="text-right">
                <p class="text-xs font-black text-gray-400 uppercase">Status Rehab</p>
                <p class="text-xl font-bold text-green-500">Minggu ke-4</p>
            </div> -->
        </div>
    </div>
    <div class="">
        <!-- Diagnosis & Plan -->
        <div class="card-modern p-8">
            <h4 class="font-bold text-xl mb-6 text-gray-800"><i class="fas fa-stethoscope text-teal-500 mr-2"></i> Rehabilitations</h4>
            @forelse($rehabilitations as $item)
                <div class="mb-4 p-4 border-2 rounded-lg shadow-lg relative {{ $loop->first ? 'border-teal-500 bg-teal-50/50' : 'border-gray-200' }}">
                    @if($loop->first)
                        <span class="absolute top-4 right-4 bg-teal-500 text-white text-[10px] font-black px-2 py-1 rounded uppercase tracking-wider shadow-sm">Current Program</span>
                    @endif
                    
                    <h5 class="font-semibold text-teal-600 flex items-center">
                        <i class="fas {{ $loop->first ? 'fa- calendar-check' : 'fa-clipboard-list' }} mr-2"></i>{{ $item->rehabilitation?->name }}
                    </h5>
                    <p class="text-sm text-gray-700">{{ $item->rehabilitation?->description }}</p>
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">Rehabilitation Date: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y') }}</span></p>
                        <p class="text-xs text-gray-500">Target Completion Date: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($item->target)->format('F j, Y') }}</span></p>
                    </div>

                    <a href="{{ route((Auth::user()->role === 'doctor' ? 'doctor' : 'therapist') . '.patient.rehabilitation.exercise', ['id' => $patientData->id, 'rehabRoutineId' => $item->id]) }}" 
                       class="mt-4 inline-block btn {{ $loop->first ? 'bg-teal-600' : 'bg-teal-500' }} hover:bg-teal-700 text-white text-xs font-bold rounded-md px-4 py-2 transition duration-300">
                        <i class="fas fa-arrow-right mr-1"></i> View Exercises
                    </a>
                </div>
            @empty
                <p class="text-gray-500 italic">No rehabilitation programs found.</p>
            @endforelse
        </div> 
    </div>
</section>