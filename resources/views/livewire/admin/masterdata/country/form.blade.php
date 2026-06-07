<div class="w-full px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">

        <div class="bg-white border-b border-slate-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-globe-asia"></i>
                    </div>
                    {{ $idCountry ? 'Edit Country' : 'Add Country' }}
                </h2>
                <p class="text-slate-500 text-sm mt-1 ml-14">Enter the country name and ISO 3166-1 alpha-2 code.</p>
            </div>
            <a href="{{ route('admin.country') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <form wire:submit.prevent="save" class="p-8 space-y-6">

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Country Name <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>
                    <input type="text" wire:model="name" required placeholder="e.g. Indonesia"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700">
                </div>
                @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">ISO Code (2 letters) <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-flag text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                    </div>
                    <input type="text" wire:model="code" required placeholder="e.g. ID" maxlength="2"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700 uppercase tracking-widest">
                </div>
                <p class="text-xs text-slate-400">ISO 3166-1 alpha-2 standard (e.g. ID, US, AU)</p>
                @error('code') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button type="button" onclick="history.back()"
                    class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:-translate-y-0.5 transition-all flex items-center gap-2"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="fas fa-save mr-1"></i> Save Country</span>
                    <span wire:loading><i class="fas fa-circle-notch fa-spin mr-1"></i> Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
