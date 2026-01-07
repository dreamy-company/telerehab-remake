<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div
        class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Upcoming Consultations</h2>
            <p class="text-xs text-slate-500">Manage your scheduled appointments.</p>
        </div>

        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="search" wire:model.live.debounce="search"
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150 ease-in-out sm:text-sm shadow-sm"
                placeholder="Search patient...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient
                        Details</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prosthetic
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Request
                        Date</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Scheduled
                        At</th>
                    <th scope="col"
                        class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-medium">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm mr-3 shadow-sm border border-indigo-100">
                                    {{ substr($item->patient?->user?->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">
                                        {{ $item->patient?->user?->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        MR: <span
                                            class="font-mono">{{ $item->patient?->medical_record_number ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                <i class="fas fa-crutch mr-1.5 text-slate-400"></i>
                                {{ \Illuminate\Support\Str::limit($item->patient?->prosthetic ?? '-', 20) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $item->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex flex-col items-center justify-center bg-blue-50 border border-blue-100 rounded-lg p-1.5 min-w-[50px]">
                                    <span
                                        class="text-[10px] font-bold text-blue-400 uppercase leading-none">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('M') : '-' }}</span>
                                    <span
                                        class="text-lg font-black text-slate-800 leading-none">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d') : '-' }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800 flex items-center">
                                        <i class="far fa-clock text-slate-400 mr-1.5 text-xs"></i>
                                        {{ $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i') : '-' }} WIB
                                    </div>
                                    <div
                                        class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 rounded inline-block mt-0.5">
                                        Confirmed
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('doctor.meeting-schedule.consultation', ['id' => $item->id]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold uppercase tracking-wide rounded-xl shadow-lg shadow-primary-500/20 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <span>Start Session</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="far fa-calendar-check text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold">No Scheduled Consultations</h3>
                                <p class="text-sm mt-1">You don't have any upcoming appointments at the moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div
        class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
        <span>Showing {{ count($data) }} records</span>
    </div>
</div>