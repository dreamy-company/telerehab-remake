<div class="space-y-6">
    <!-- Hero Section: Current Rehabilitation -->
    <div
        class="relative w-full rounded-3xl overflow-hidden bg-white shadow-xl shadow-teal-900/5 border border-slate-100 group">

        <div
            class="absolute top-0 right-0 w-2/3 h-full bg-gradient-to-l from-teal-50 to-transparent opacity-60 transition-opacity group-hover:opacity-80">
        </div>
        <img src="{{ asset('assets/images/hero-img.png') }}"
            class="absolute bottom-0 right-0 h-full w-auto object-cover opacity-20 mix-blend-multiply translate-y-4 translate-x-4 md:translate-x-0 transition-transform duration-700 group-hover:scale-105"
            alt="Rehabilitation Hero">

        <div class="relative z-10 p-8 md:p-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="max-w-xl">

                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-100/80 border border-teal-200 text-teal-700 text-[11px] font-bold uppercase tracking-wider mb-4">
                        <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                        Current Program
                    </div>

                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        {{ $checkRehabilitation->rehabilitation->name ?? 'Start Your Journey' }}
                    </h3>

                    @if($checkRehabilitation)
                        <p class="text-slate-500 text-lg leading-relaxed mb-8 max-w-lg">
                            "Every small step is a big victory towards recovery."
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('patient.rehabilitation.exercise', ['id' => $checkRehabilitation->id]) }}"
                                class="inline-flex gap-4 items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-teal-600 rounded-xl hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <i class="fas fa-play-circle mr-2.5 text-lg"></i>
                                Start Session
                            </a>
                        </div>

                    @else
                        @if($checkConsultation)
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 max-w-lg">
                                @if(is_null($checkConsultation->doctor_id))
                                    <div class="flex items-center text-blue-800 font-semibold">
                                        <i class="fas fa-clock mr-2 animate-spin-slow"></i>
                                        <span>Schedule Requested</span>
                                    </div>
                                    <p class="text-blue-600/80 text-sm mt-1">Please wait for doctor approval.</p>
                                @else
                                    <div class="flex items-center text-teal-800 font-semibold">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        <span>Schedule Confirmed</span>
                                    </div>
                                    <p class="text-teal-600/80 text-sm mt-1">Please check the doctor schedule below.</p>
                                @endif
                            </div>

                        @else
                            <p class="text-slate-500 text-lg leading-relaxed mb-8 max-w-lg">
                                You don't have any rehabilitation program yet. Request a schedule for meeting a doctor to start
                                your journey.
                            </p>

                            <div class="inline-block animate-bounce">
                                <button onclick="requestConsultation()"
                                    class="group animate-pulse inline-flex gap-4 items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-slate-900 rounded-xl hover:bg-slate-800 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">

                                    <span>Request Schedule</span>
                                    <i class="fas fa-calendar-plus transition-transform group-hover:scale-110"></i>

                                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                                    </span>
                                </button>
                            </div>
                        @endif

                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Consultation Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Card 1: Progress Analytics -->
        <div
            class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-xl font-bold text-slate-900">Recovery Progress</h4>
                    <p class="text-sm text-slate-400 mt-1">Daily routine tracking</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fas fa-chart-pie text-lg"></i>
                </div>
            </div>

            @if($checkRehabilitation != null)
                @php
                    $start = \Carbon\Carbon::parse($checkRehabilitation->created_at)->startOfDay();
                    $end = \Carbon\Carbon::parse($checkRehabilitation->target)->startOfDay();
                    $today = now()->startOfDay();

                    $totalDays = (int) $start->diffInDays($end) + 1;
                    $completedDays = (int) $checkRehabilitation->routineResults
                        ->filter(fn($item) => $item->created_at->startOfDay()->between($start, min($today, $end)))
                        ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                        ->count();
                    $remainingDays = max(0, (int) ($totalDays - $completedDays));
                    $progressPercentage = $totalDays > 0 ? (int) round(($completedDays / $totalDays) * 100) : 0;
                @endphp

                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <!-- Circular Chart -->
                    <div class="relative w-32 h-32 shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-teal-500 drop-shadow-md transition-all duration-1000 ease-out"
                                stroke-dasharray="{{ $progressPercentage }}, 100" stroke-width="3" stroke-linecap="round"
                                stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-black text-slate-800">{{ $progressPercentage }}%</span>
                        </div>
                    </div>

                    <!-- Detailed Stats -->
                    <div class="grid grid-cols-2 gap-4 w-full">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Done</div>
                            <div class="text-2xl font-bold text-slate-800">{{ $completedDays }} <span
                                    class="text-sm text-slate-400 font-normal">days</span></div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Left</div>
                            <div class="text-2xl font-bold text-slate-800">{{ $remainingDays }} <span
                                    class="text-sm text-slate-400 font-normal">days</span></div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center mb-3 text-slate-400">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <p class="text-slate-500 font-medium">No active program found</p>
                    <p class="text-xs text-slate-400 mt-1">Your stats will appear here once you begin.</p>
                </div>
            @endif
        </div>

        <div
            class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden flex flex-col h-full">

            <div class="flex items-center justify-between mb-6 relative z-10">
                <div>
                    <h4 class="text-xl font-bold text-slate-900">Doctor Consultation</h4>
                    <p class="text-sm text-slate-400 mt-1">Next scheduled appointment</p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <i class="fas fa-user-md text-lg"></i>
                </div>
            </div>

            <div class="flex-grow flex flex-col justify-center">
                @if($checkConsultation)
                    @if(is_null($checkConsultation->doctor_id))
                        <div class="flex flex-col items-center justify-center py-4 text-center">
                            <div
                                class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mb-4 animate-pulse text-amber-500">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                            <h5 class="text-lg font-bold text-slate-800">Waiting for Confirmation</h5>
                            <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">We have received your request. We are
                                currently matching you with an available specialist.</p>
                        </div>
                    @else
                        <div class="w-full">

                            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                                <div class="flex flex-col md:flex-row md:items-center gap-5">

                                    <div
                                        class="flex items-center gap-4 pb-5 md:pb-0 border-b md:border-b-0 md:border-r border-slate-100 md:pr-5 flex-shrink-0">
                                        <div
                                            class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center flex-shrink-0">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">
                                                {{ \Carbon\Carbon::parse($checkConsultation->date)->format('M') }}
                                            </span>
                                            <span class="text-2xl font-black text-slate-800 leading-none mt-0.5">
                                                {{ \Carbon\Carbon::parse($checkConsultation->date)->format('d') }}
                                            </span>
                                        </div>

                                        <div>
                                            <p class="text-sm font-bold text-slate-800">
                                                {{ \Carbon\Carbon::parse($checkConsultation->time)->format('H:i') }} WIB
                                            </p>
                                            <div class="flex items-center mt-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                                <p class="text-xs text-slate-500 font-medium">Confirmed</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="relative flex-shrink-0">
                                            <div
                                                class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-100">
                                                @if($checkConsultation->doctor?->profile_photo_path)
                                                    <img src="{{ Storage::url($checkConsultation->doctor->profile_photo_path) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-user-md text-slate-400"></i>
                                                @endif
                                            </div>
                                            <div
                                                class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-[8px] text-white"></i>
                                            </div>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-slate-900 truncate text-sm md:text-base">
                                                {{ $checkConsultation->doctor?->name ?? 'Specialist Assigned' }}
                                            </p>
                                            <p class="text-xs font-semibold text-teal-600 uppercase tracking-wide mb-0.5">
                                                {{ $checkConsultation->meeting_category }}
                                            </p>
                                            <div class="flex items-center text-xs text-slate-400 truncate">
                                                <i class="fas fa-map-marker-alt mr-1.5"></i>
                                                <span class="truncate">{{ $checkConsultation->location }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-4 text-center md:text-right">
                                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <div
                        class="bg-gradient-to-br from-slate-50 to-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center justify-center text-center h-full">
                        <p class="text-slate-700 font-bold mb-1">Time for a checkup?</p>
                        <p class="text-xs text-slate-400 mb-6 max-w-[200px] leading-relaxed">Schedule a session with our
                            specialists to track your recovery progress.</p>

                        <button onclick="requestConsultation()"
                            class="w-full group relative flex items-center justify-center gap-2 px-6 py-3 bg-white text-slate-900 border-2 border-slate-100 rounded-xl font-bold transition-all hover:border-teal-500 hover:text-teal-600 hover:shadow-md cursor-pointer active:scale-95">
                            <span>Request Schedule</span>
                            <i class="fas fa-calendar-plus transition-transform group-hover:scale-110"></i>

                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <!-- Consultation History Section -->
        <div x-data="{ showModal: false, selectedItem: null }" class="col-span-1 lg:col-span-2 mt-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div
                    class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xl font-bold text-slate-900">Consultation History</h4>
                        <p class="text-sm text-slate-400 mt-1">Your past appointments and medical records</p>
                    </div>
                    <div
                        class="hidden md:flex w-12 h-12 rounded-full bg-indigo-50 items-center justify-center text-indigo-600">
                        <i class="fas fa-history text-xl"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider font-semibold border-b border-slate-100">
                                <th class="px-6 py-4">Date & Time</th>
                                <th class="px-6 py-4">Doctor</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($consultationDatas as $item)
                                                        <tr class="hover:bg-slate-50/80 transition-colors duration-200 group">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex flex-col">
                                                                    <span
                                                                        class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</span>
                                                                    <span
                                                                        class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($item->time)->format('H:i') }}
                                                                        WIB</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center gap-3">
                                                                    <div
                                                                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden text-slate-400 text-xs shadow-sm border border-slate-200">
                                                                        @if($item->doctor && $item->doctor->profile_photo_path)
                                                                            <img src="{{ Storage::url($item->doctor->profile_photo_path) }}"
                                                                                class="w-full h-full object-cover">
                                                                        @else
                                                                            <i class="fas fa-user-md text-lg"></i>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex flex-col">
                                                                        <span
                                                                            class="font-bold text-slate-700">{{ $item->doctor->name ?? 'Waiting Assignment' }}</span>
                                                                        <span
                                                                            class="text-xs text-slate-400">{{ $item->location ?? 'Online' }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize shadow-sm
                                                                {{ $item->meeting_category === 'online' ? 'bg-sky-50 text-sky-700 border border-sky-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                                                    @if($item->meeting_category == 'online')
                                                                        <i class="fas fa-video mr-1.5"></i>
                                                                    @else
                                                                        <i class="fas fa-hospital mr-1.5"></i>
                                                                    @endif
                                                                    {{ $item->meeting_category }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @php
                                                                    $statusColors = [
                                                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                                        'done' => 'bg-teal-50 text-teal-700 border-teal-100',
                                                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                                    ];
                                                                    $colorClass = $statusColors[$item->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                                                @endphp
                                                                <span
                                                                    class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                                                                    {{ ucfirst($item->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                                <button @click="selectedItem = {{ json_encode([
                                    'id' => $item->id,
                                    'date' => $item->date,
                                    'time' => $item->time,
                                    'status' => $item->status,
                                    'location' => $item->location,
                                    'meeting_category' => $item->meeting_category,
                                    'diagnosis' => $item->diagnosis,
                                    'medicine' => $item->medicine,
                                    'description' => $item->description,
                                    'doctor_name' => $item->doctor->name ?? 'Waiting Assignment',
                                    'doctor_id' => $item->doctor_id,
                                ]) }}; showModal = true"
                                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-teal-600 hover:border-teal-400 hover:bg-teal-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                                    title="View Details">
                                                                    <i class="fas fa-eye text-xs"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                <i class="fas fa-history text-2xl"></i>
                                            </div>
                                            <h6 class="text-slate-900 font-medium">No History Found</h6>
                                            <p class="text-slate-400 text-sm mt-1">You haven't had any consultations yet.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detailed Info Modal (Popup) -->
            <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[99] overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">

                <!-- Backdrop -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 transition-opacity backdrop-blur-sm"
                    @click="showModal = false"></div>

                <!-- Modal Panel -->
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <div x-show="showModal" x-transition:enter="ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-100">

                        <!-- Modal Header -->
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-xl">
                            </div>
                            <div class="flex items-center justify-between relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        Consultation Details
                                    </h3>
                                    <p class="text-slate-400 text-xs mt-1">Full medical record information</p>
                                </div>
                                <button @click="showModal = false"
                                    class="text-white/60 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <template x-if="selectedItem">
                                <div class="space-y-6">

                                    <!-- Status & Date Row -->
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-dashed border-slate-200">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-500 border border-slate-100">
                                                <i class="fas fa-calendar-alt text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">
                                                    Date & Time</p>
                                                <p class="font-bold text-slate-800 text-lg">
                                                    <span
                                                        x-text="new Date(selectedItem.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })"></span>
                                                    <span class="text-slate-300 mx-1">|</span>
                                                    <span class="text-base font-medium"
                                                        x-text="selectedItem.time.substring(0,5)"></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-left sm:text-right">
                                            <span
                                                class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide"
                                                :class="{
                                                'bg-teal-50 text-teal-600 border border-teal-100': selectedItem.status === 'done',
                                                'bg-amber-50 text-amber-600 border border-amber-100': selectedItem.status === 'pending',
                                                'bg-rose-50 text-rose-600 border border-rose-100': selectedItem.status === 'cancelled'
                                              }">
                                                <span class="w-2 h-2 rounded-full mr-2" :class="{
                                                        'bg-teal-500': selectedItem.status === 'done',
                                                        'bg-amber-500': selectedItem.status === 'pending',
                                                        'bg-rose-500': selectedItem.status === 'cancelled'
                                                    }"></span>
                                                <span x-text="selectedItem.status"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Doctor Info -->
                                    <div
                                        class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm border border-slate-200">
                                            <i class="fas fa-user-md text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                                                Doctor</p>
                                            <p class="font-bold text-slate-800" x-text="selectedItem.doctor_name"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                                                Location</p>
                                            <p class="font-bold text-slate-800 text-sm"
                                                x-text="selectedItem.location || 'Online'"></p>
                                        </div>
                                    </div>

                                    <!-- Diagnosis Section -->
                                    <div x-show="selectedItem.diagnosis" class="animate-fadeIn">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-clipboard-check text-indigo-500"></i>
                                            <p class="text-sm font-bold text-slate-900">Diagnosis</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 text-slate-700 text-sm leading-relaxed"
                                            x-text="selectedItem.diagnosis"></div>
                                    </div>

                                    <!-- Medicine Section -->
                                    <div x-show="selectedItem.medicine" class="animate-fadeIn">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-prescription-bottle-alt text-teal-500"></i>
                                            <p class="text-sm font-bold text-slate-900">Prescriptions</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-teal-50/50 border border-teal-100 text-slate-700 text-sm leading-relaxed"
                                            x-text="selectedItem.medicine"></div>
                                    </div>

                                    <!-- Notes Section -->
                                    <div x-show="selectedItem.description">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-sticky-note text-amber-500"></i>
                                            <p class="text-sm font-bold text-slate-900">Additional Notes</p>
                                        </div>
                                        <p class="text-slate-600 text-sm bg-slate-50 p-4 rounded-xl border border-slate-100"
                                            x-text="selectedItem.description"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex justify-end rounded-b-3xl">
                            <button type="button"
                                class="inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-900 transition-all w-full sm:w-auto"
                                @click="showModal = false">
                                Close Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @push('scripts')
            <script>
                function requestConsultation() {
                    Swal.fire({
                        title: "Request Consultation?",
                        text: "Our team will match you with the best available doctor for your rehabilitation phase.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#0d9488', // Teal-600
                        cancelButtonColor: '#94a3b8', // Slate-400
                        confirmButtonText: "Yes, Request Now",
                        cancelButtonText: "Cancel",
                        customClass: {
                            popup: 'rounded-2xl font-sans',
                            confirmButton: 'rounded-xl px-6 py-3',
                            cancelButton: 'rounded-xl px-6 py-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('requestConsultation');
                        }
                    });
                }
            </script>
        @endpush