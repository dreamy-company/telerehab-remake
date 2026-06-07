<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div
        class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div
                class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 shadow-sm">
                <i class="fas fa-running"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 leading-tight">Movement Exercises</h2>
                <p class="text-xs text-slate-500">Manage joint movement exercises and angle thresholds.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce="search"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                    placeholder="Search exercise...">
            </div>

            <a href="{{ route('admin.movement-exercise.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none shadow-md shadow-primary-500/20 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Add Exercise
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Exercise
                        Name</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Target
                        Joint</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Min&deg;
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Max&deg;
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Target
                        Reps</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                        Description</th>
                    <th scope="col"
                        class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions
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
                            <span class="text-sm font-bold text-slate-900">{{ $item->name }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <i class="fas fa-bone mr-1.5"></i>
                                {{ str_replace('_', ' ', ucwords($item->target_joint, '_')) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                            {{ $item->thresholds['min_angle'] ?? '-' }}&deg;
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                            {{ $item->thresholds['max_angle'] ?? '-' }}&deg;
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                            {{ $item->thresholds['target_reps'] ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600 max-w-xs truncate"
                                title="{{ $item->description }}">
                                {{ $item->description ?? '-' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.movement-exercise.edit', $item->id) }}"
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-amber-500 hover:bg-amber-50 hover:border-amber-300 transition-all shadow-sm tooltip"
                                    data-tip="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button wire:click="delete({{ $item->id }})"
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-300 transition-all shadow-sm tooltip"
                                    onclick="return confirm('Are you sure you want to delete this exercise?') || event.stopImmediatePropagation()"
                                    data-tip="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-running text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">No Exercises Found</h3>
                                <p class="text-sm mt-1">Try adjusting your search or add a new movement exercise.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div
        class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
        <span>Showing {{ count($data) }} exercises</span>
    </div>
</div>
