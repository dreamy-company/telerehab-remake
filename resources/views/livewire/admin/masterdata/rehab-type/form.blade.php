<div class="max-w-lg mx-auto mt-10">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">

        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Phase Management</h2>
                <p class="text-xs text-slate-500">Create or update rehabilitation stage.</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-8">

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Phase Name <span class="text-red-500">*</span>
                </label>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>

                    <input type="text" wire:model="name" required autocomplete="off"
                        placeholder="e.g. Phase 1: Initial Recovery"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 focus:outline-none transition-all font-medium text-slate-700 placeholder-slate-400">
                </div>

                @error('name')
                    <div class="flex items-center gap-1.5 text-red-500 text-xs mt-1 animate-pulse">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.rehabilitation-phase') }}"
                    class="px-5 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all text-sm">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Phase
                </button>
            </div>

        </form>
    </div>
</div>