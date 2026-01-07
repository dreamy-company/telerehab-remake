<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total Patient</p>
                    <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{$totalPatients}}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Total Rehabilitations</p>
                    <h3 class="text-3xl font-extrabold text-orange-900 mt-1">{{$totalRehabilitations}}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500"><i class="fas fa-notes-medical"></i></div>
            </div>
        </div>
        <div class="card-modern p-6 bg-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Total Rehabilitations Phases</p>
                    <h3 class="text-3xl font-extrabold text-teal-900 mt-1">{{$totalRehabTypes}}</h3>
                </div>
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-500"><i class="fas fa-layer-group"></i></div>
            </div>
        </div>
    </div>
</div>