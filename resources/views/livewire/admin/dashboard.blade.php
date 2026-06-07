<div class="space-y-8">

    {{-- ===== TOP STAT CARDS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        {{-- Patients --}}
        <a href="{{ route('admin.patient') }}"
            class="relative overflow-hidden bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-300 transition-all duration-300 block">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-blue-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Patients</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalPatients }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-injured"></i>
                </div>
            </div>
        </a>

        {{-- Doctors --}}
        <a href="{{ route('admin.user') }}"
            class="relative overflow-hidden bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-indigo-300 transition-all duration-300 block">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-indigo-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Doctors</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalDoctors }}</h3>
                    <p class="text-[10px] text-indigo-500 font-medium mt-1">+ {{ $totalTherapists }} therapists</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-md"></i>
                </div>
            </div>
        </a>

        {{-- Rehab Programs --}}
        <a href="{{ route('admin.rehabilitation') }}"
            class="relative overflow-hidden bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-orange-300 transition-all duration-300 block">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-orange-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rehab Programs</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalRehabilitations }}</h3>
                    <p class="text-[10px] text-orange-500 font-medium mt-1">{{ $totalRehabTypes }} phases</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-notes-medical"></i>
                </div>
            </div>
        </a>

        {{-- Meetings --}}
        <a href="{{ route('admin.meetings') }}"
            class="relative overflow-hidden bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-teal-300 transition-all duration-300 block">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 h-20 bg-teal-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Meetings</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalMeetings }}</h3>
                    @if($pendingMeetings > 0)
                    <p class="text-[10px] text-amber-600 font-bold mt-1">{{ $pendingMeetings }} pending</p>
                    @endif
                </div>
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </a>

    </div>

    {{-- ===== SECONDARY STAT CARDS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <a href="{{ route('admin.rehab-routines') }}"
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-rose-300 transition-all block">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Active Routines</p>
                    <h3 class="text-2xl font-black text-rose-600">{{ $activeRoutines }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $completedRoutines }} completed</p>
                </div>
                <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-dumbbell"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.schedules') }}"
            class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 group hover:border-violet-300 transition-all block">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Schedules</p>
                    <h3 class="text-2xl font-black text-violet-600">{{ $totalSchedules }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Consultation requests</p>
                </div>
                <div class="w-10 h-10 bg-violet-50 text-violet-500 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </a>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Video Uploads</p>
                    <h3 class="text-2xl font-black text-cyan-600">{{ $totalRoutineResults }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Exercise videos</p>
                </div>
                <div class="w-10 h-10 bg-cyan-50 text-cyan-500 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Movement Sessions</p>
                    <h3 class="text-2xl font-black text-green-600">{{ $completedSessions }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">of {{ $totalMovingSessions }} total</p>
                </div>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-lg">
                    <i class="fas fa-running"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== RECENT DATA TABLES ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Patients --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-500"></i> Recent Patients
                </h3>
                <a href="{{ route('admin.patient') }}" class="text-xs font-bold text-primary-600 hover:text-primary-800">View all →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentPatients as $p)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ substr($p->user?->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $p->user?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-400">MR: {{ $p->medical_record_number }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400">{{ $p->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="px-6 py-8 text-center text-sm text-slate-400 italic">No patients yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Meetings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-teal-500"></i> Recent Meetings
                </h3>
                <a href="{{ route('admin.meetings') }}" class="text-xs font-bold text-primary-600 hover:text-primary-800">View all →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentMeetings as $m)
                @php
                    $mc = match($m->status) {
                        'pending'   => 'bg-yellow-50 text-yellow-700',
                        'approved'  => 'bg-blue-50 text-blue-700',
                        'completed' => 'bg-green-50 text-green-700',
                        'cancelled' => 'bg-red-50 text-red-700',
                        default     => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $m->title ?? 'Consultation' }}</p>
                        <p class="text-xs text-slate-400">
                            {{ $m->patient?->user?->name ?? '—' }} ↔ Dr. {{ $m->doctor?->name ?? '—' }}
                        </p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $mc }} capitalize">
                        {{ $m->status ?? 'N/A' }}
                    </span>
                </div>
                @empty
                <p class="px-6 py-8 text-center text-sm text-slate-400 italic">No meetings yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ===== RECENT REHAB ROUTINES ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-file-medical-alt text-rose-500"></i> Recent Rehab Routines
            </h3>
            <a href="{{ route('admin.rehab-routines') }}" class="text-xs font-bold text-primary-600 hover:text-primary-800">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Patient</th>
                        <th class="px-6 py-3 text-left">Program</th>
                        <th class="px-6 py-3 text-left">Doctor</th>
                        <th class="px-6 py-3 text-left">Target</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentRoutines as $r)
                    @php
                        $rc = match($r->status) {
                            'complete' => 'bg-green-50 text-green-700',
                            'process'  => 'bg-blue-50 text-blue-700',
                            default    => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 font-medium text-slate-800">{{ $r->patient?->user?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $r->rehabilitation?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $r->doctor?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-slate-500">{{ $r->target ? \Carbon\Carbon::parse($r->target)->format('d M Y') : '—' }}</td>
                        <td class="px-6 py-3">
                            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $rc }} capitalize">{{ $r->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400 italic">No rehab routines yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== MASTERDATA QUICK LINKS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $quickLinks = [
                ['label' => 'Users', 'icon' => 'fa-users', 'color' => 'purple', 'route' => 'admin.user'],
                ['label' => 'Rehab Phases', 'icon' => 'fa-layer-group', 'color' => 'teal', 'route' => 'admin.rehabilitation-phase'],
                ['label' => 'Countries', 'icon' => 'fa-globe-asia', 'color' => 'emerald', 'route' => 'admin.country'],
                ['label' => 'Movement Exercises', 'icon' => 'fa-running', 'color' => 'indigo', 'route' => 'admin.movement-exercise'],
            ];
        @endphp
        @foreach($quickLinks as $ql)
        <a href="{{ route($ql['route']) }}"
            class="bg-white p-4 rounded-2xl border border-slate-200 hover:border-{{ $ql['color'] }}-300 hover:shadow-md transition-all flex items-center gap-3 group">
            <div class="w-10 h-10 bg-{{ $ql['color'] }}-50 text-{{ $ql['color'] }}-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas {{ $ql['icon'] }}"></i>
            </div>
            <span class="text-sm font-bold text-slate-700">{{ $ql['label'] }}</span>
        </a>
        @endforeach
    </div>

</div>
