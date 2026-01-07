<div class="w-full px-4 sm:px-6 lg:px-8 py-8">

    <div
        class="max-w-7xl mx-auto bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">

        <div
            class="bg-white border-b border-slate-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    User Management
                </h2>
                <p class="text-slate-500 text-sm mt-1 ml-14">Add new users or manage existing access permissions.</p>
            </div>

            <a href="{{ route('admin.user') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <form wire:submit.prevent="save">
            <div class="flex flex-col lg:flex-row">

                <div class="flex-1 p-8 lg:p-10">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i class="fas fa-id-badge"></i> Account Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Full Name <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-user text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                                </div>
                                <input type="text" wire:model="name" required placeholder="Enter full name"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700">
                            </div>
                            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Email Address <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-envelope text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                                </div>
                                <input type="email" wire:model="email" required placeholder="name@example.com"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700">
                            </div>
                            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Phone Number</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-phone text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                                </div>
                                <input type="tel" wire:model="telephone" placeholder="+62 812..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700">
                            </div>
                            @error('telephone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2" x-data="{ show: false }">
                            <label class="text-sm font-bold text-slate-700">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-lock text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                                </div>
                                <input :type="show ? 'text' : 'password'" wire:model="password" placeholder="••••••••"
                                    class="w-full pl-10 pr-12 py-3 rounded-xl border border-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all font-medium text-slate-700">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="hidden lg:flex items-center gap-4 mt-12 pt-6 border-t border-slate-100">
                        <button type="button" onclick="history.back()"
                            class="px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-3 rounded-xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Save User
                        </button>
                    </div>
                </div>

                <div class="lg:w-96 bg-slate-50/80 border-t lg:border-t-0 lg:border-l border-slate-200 p-8 lg:p-10">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Access Role
                    </h3>

                    <div class="space-y-4">
                        <label class="relative cursor-pointer group block">
                            <input type="radio" wire:model="role" value="admin" class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-white bg-white shadow-sm hover:border-purple-300 peer-checked:border-purple-500 peer-checked:ring-2 peer-checked:ring-purple-500/20 transition-all flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800">Administrator</span>
                                    <span class="text-xs text-slate-500">Full access to system settings and user
                                        management.</span>
                                </div>
                                <div class="ml-auto opacity-0 peer-checked:opacity-100 text-purple-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group block">
                            <input type="radio" wire:model="role" value="doctor" class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-white bg-white shadow-sm hover:border-blue-300 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500/20 transition-all flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800">Doctor</span>
                                    <span class="text-xs text-slate-500">Can manage patients, consultations, and
                                        diagnosis.</span>
                                </div>
                                <div class="ml-auto opacity-0 peer-checked:opacity-100 text-blue-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group block">
                            <input type="radio" wire:model="role" value="therapist" class="peer sr-only">
                            <div
                                class="p-4 rounded-xl border-2 border-white bg-white shadow-sm hover:border-teal-300 peer-checked:border-teal-500 peer-checked:ring-2 peer-checked:ring-teal-500/20 transition-all flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800">Therapist</span>
                                    <span class="text-xs text-slate-500">Manage rehabilitation programs and
                                        therapies.</span>
                                </div>
                                <div class="ml-auto opacity-0 peer-checked:opacity-100 text-teal-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('role') <p class="text-red-500 text-xs mt-2 text-center font-bold">{{ $message }}</p>
                    @enderror

                    <div class="lg:hidden mt-8 pt-6 border-t border-slate-200 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-primary-600 text-white font-bold shadow-lg">
                            Save User
                        </button>
                        <button type="button" onclick="history.back()"
                            class="w-full py-3.5 rounded-xl border border-slate-300 text-slate-600 font-bold bg-white">
                            Cancel
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>