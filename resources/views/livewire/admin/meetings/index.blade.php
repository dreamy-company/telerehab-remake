<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 leading-tight">Meetings</h2>
                <p class="text-xs text-slate-500">All doctor–patient consultation meetings.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <select wire:model.live="filterStatus"
                class="border border-slate-300 rounded-xl py-2 px-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 shadow-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce="search"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition shadow-sm"
                    placeholder="Search doctor, patient...">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Doctor</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $item)
                    @php
                        $statusColors = [
                            'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'approved'  => 'bg-blue-50 text-blue-700 border-blue-200',
                            'completed' => 'bg-green-50 text-green-700 border-green-200',
                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        ];
                        $sc = $statusColors[$item->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-medium">
                            {{ $data->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900">{{ $item->title ?? '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->meeting_category ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-slate-700">{{ $item->patient?->user?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-slate-700">{{ $item->doctor?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-slate-700">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i') : '' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $sc }} capitalize">
                                {{ $item->status ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button wire:click="delete({{ $item->id }})"
                                class="p-2 bg-white border border-slate-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition-all shadow-sm tooltip" data-tip="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-times text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">No Meetings Found</h3>
                                <p class="text-sm mt-1">No meeting records match your search.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs text-slate-500">Total {{ $data->total() }} meetings</span>
        {{ $data->links() }}
    </div>
</div>
