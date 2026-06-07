<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600 shadow-sm">
                <i class="fas fa-file-medical-alt"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 leading-tight">Rehab Routines</h2>
                <p class="text-xs text-slate-500">All prescribed rehabilitation programs for patients.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select wire:model.live="filterStatus"
                class="border border-slate-300 rounded-xl py-2 px-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                <option value="">All Statuses</option>
                <option value="process">In Process</option>
                <option value="complete">Completed</option>
            </select>
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce="search"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition shadow-sm"
                    placeholder="Search patient, doctor, program...">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Doctor</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Program</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Goal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $item)
                    @php
                        $statusColor = match($item->status) {
                            'complete' => 'bg-green-50 text-green-700 border-green-200',
                            'process'  => 'bg-blue-50 text-blue-700 border-blue-200',
                            default    => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-medium">
                            {{ $data->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ substr($item->patient?->user?->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $item->patient?->user?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">MR: {{ $item->patient?->medical_record_number ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $item->doctor?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $item->rehabilitation?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 max-w-[180px] truncate" title="{{ $item->goal }}">{{ $item->goal }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $item->target ? \Carbon\Carbon::parse($item->target)->format('d M Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $statusColor }} capitalize">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.patient.rehabilitation', $item->patient_id) }}"
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-blue-500 hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm tooltip" data-tip="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button wire:click="delete({{ $item->id }})"
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition-all shadow-sm tooltip" data-tip="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">No Rehab Routines Found</h3>
                                <p class="text-sm mt-1">No prescribed rehabilitation routines exist yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs text-slate-500">Total {{ $data->total() }} routines</span>
        {{ $data->links() }}
    </div>
</div>
