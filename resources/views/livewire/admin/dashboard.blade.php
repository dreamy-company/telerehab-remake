<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div
        class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-300 transition-all duration-300">
        <div
            class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity">
        </div>

        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Patients</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPatients }}</h3>
                <p class="text-xs text-blue-500 font-medium mt-2 flex items-center gap-1">
                    <i class="fas fa-chart-line"></i> Active Users
                </p>
            </div>
            <div
                class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm transition-transform group-hover:scale-110">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div
        class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-200 group hover:border-orange-300 transition-all duration-300">
        <div
            class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity">
        </div>

        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Rehabilitations</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalRehabilitations }}</h3>
                <p class="text-xs text-orange-500 font-medium mt-2 flex items-center gap-1">
                    <i class="fas fa-file-medical-alt"></i> Programs Created
                </p>
            </div>
            <div
                class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl shadow-sm transition-transform group-hover:scale-110">
                <i class="fas fa-notes-medical"></i>
            </div>
        </div>
    </div>

    <div
        class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-200 group hover:border-teal-300 transition-all duration-300">
        <div
            class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-teal-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity">
        </div>

        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rehab Phases</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalRehabTypes }}</h3>
                <p class="text-xs text-teal-600 font-medium mt-2 flex items-center gap-1">
                    <i class="fas fa-layer-group"></i> Stages Available
                </p>
            </div>
            <div
                class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl shadow-sm transition-transform group-hover:scale-110">
                <i class="fas fa-stream"></i>
            </div>
        </div>
    </div>

</div>