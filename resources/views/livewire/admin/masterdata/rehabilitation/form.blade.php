<div class="max-w-2xl mx-auto mt-6">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">

        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 shadow-sm">
                <i class="fas fa-heartbeat text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Rehabilitation Program</h2>
                <p class="text-sm text-slate-500">Create or edit rehabilitation details.</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Program Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i
                                class="fas fa-dumbbell text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                        <input type="text" wire:model="name" required placeholder="e.g. Leg Strengthening"
                            autocomplete="off"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 focus:outline-none transition-all font-medium text-slate-700 placeholder-slate-400">
                    </div>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Rehab Phase <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i
                                class="fas fa-layer-group text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                        </div>
                        <select wire:model="rehabilitationPhase" required
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 focus:outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer">
                            <option value="" disabled selected>Select Phase</option>
                            @foreach ($rehabType as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('rehabilitationPhase') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Video Guide URL
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fab fa-youtube text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                    </div>
                    <input type="url" wire:model="videoUrl" placeholder="https://youtube.com/..." autocomplete="off"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 focus:outline-none transition-all font-medium text-slate-700 placeholder-slate-400">
                </div>
                @error('videoUrl') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Description
                </label>
                <div class="relative group">
                    <textarea wire:model="description" rows="4" placeholder="Explain the rehabilitation steps..."
                        class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 focus:outline-none transition-all font-medium text-slate-700 placeholder-slate-400 resize-none"></textarea>
                </div>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                <a href="{{ route('admin.rehabilitation') }}"
                    class="px-6 py-3 rounded-xl text-slate-600 font-bold hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-sm">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Program
                </button>
            </div>

        </form>
    </div>
</div>