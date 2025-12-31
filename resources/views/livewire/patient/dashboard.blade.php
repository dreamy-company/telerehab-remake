<div class="space-y-6">
    <div class="card-modern p-10 bg-gradient-to-br from-white to-teal-50 relative overflow-hidden">
        <div class="relative z-10 lg:max-w-xl">
            <span class="bg-teal-100 text-teal-700 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Current Rehabilitation</span>
            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">{{$checkRehabilitation->rehabilitation->name ?? 'No Rehabilitation' }}</h3>
            @if($checkRehabilitation)
            <p class="text-gray-500 mb-10 leading-relaxed text-lg">"Every small step is a big victory towards recovery."</p>
            <button onclick="navigate('rehab')" class="btn btn-lg p-6 bg-secondary-300 text-white rounded-xl hover:bg-secondary-400"><i class="fas fa-circle-play mr-2"></i> START REHABILITATION</button>
            @else
            <p class="text-gray-500 mb-10 leading-relaxed text-lg">Let's get started on your rehabilitation journey today. Begin your path to recovery now!</p>
            <button onclick="startRehabitation()" class="btn btn-lg p-6 bg-teal-500 text-white rounded-xl hover:bg-teal-600"><i class="fas fa-arrow-right mr-2"></i> BEGIN REHABILITATION</button>
            @endif
        </div>
        <img src="{{asset('assets/images/hero-img.png')}}" class="absolute bottom-0 right-0 h-72 opacity-40 hidden md:block">
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="card-modern p-8">
            <h4 class="font-bold text-xl mb-6 flex items-center gap-3"><i class="fas fa-chart-line text-teal-500"></i> Progres Mingguan</h4>
            <div class="flex items-center gap-8">
                <div class="relative w-24 h-24">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <path stroke="#E8F1F3" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path stroke="#94B9C5" stroke-width="3" stroke-dasharray="75, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center font-black text-xl text-teal-600">75%</div>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">12 Sesi Selesai</p>
                    <p class="text-xs text-gray-400 mt-1">4 Sesi lagi.</p>
                </div>
            </div>
        </div>
        <div class="card-modern p-8 flex items-center justify-between">
            <div>
                <h4 class="font-bold text-xl mb-2">Doctor Consultation</h4>
                <div class="text-md text-teal-600 font-bold mt-2 uppercase tracking-widest">
                    @if($checkConsultation)
                    @if(is_null($checkConsultation->doctor_id))
                    Waiting for doctor to confirm schedule
                    @else
                    <div class="flex flex-col">
                        <p class="mt-3"><i class="fas fa-user-doctor mr-2"></i>{{ $checkConsultation->doctor?->name }}</p> 
                        <p class="mt-3"><i class="fas fa-calendar-alt mr-2"></i>{{ \Carbon\Carbon::parse($checkConsultation->date)->format('d F Y') }}</p> 
                        <p class="mt-3"><i class="fas fa-clock mr-2"></i>{{ \Carbon\Carbon::parse($checkConsultation->time)->format('H:i') }}</p>
                        <p class="mt-3 "><i class="fas fa-stethoscope text-teal-500 mr-2"></i>{{ $checkConsultation->meeting_category }}</p>
                        <p class="mt-3"><i class="fas fa-map-marker-alt text-teal-500 mr-2"></i>{{ $checkConsultation->location }}</p>
                    </div>
                    @endif
                    @else
                    There is no scheduled consultation
                    @endif
                </div>
            </div>
            @if(!$checkConsultation)
            <button onclick="requestConsultation()" class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl hover:bg-teal-100 transition-colors"><i class="fas fa-arrow-right"></i></button>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    function requestConsultation() {
        Swal.fire({
            title: "Request Consultation Schedule",
            text: "Would like to request a consultation schedule? ",
            icon: "question",
            confirmButtonText: "Yes",
            showCancelButton: true,
            cancelButtonTezt: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('requestConsultation');
            }
        });
    }
</script>
@endpush