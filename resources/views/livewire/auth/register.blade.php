<div class="w-full max-w-6xl bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-2xl shadow-slate-300/50 overflow-hidden border border-slate-200 grid grid-cols-1 md:grid-cols-12 relative min-h-[auto] md:min-h-[600px]">

    <!-- LEFT SIDE: Progress Sidebar -->
    <!-- Mobile: Tampil di atas sebagai Header, Desktop: Sidebar kiri -->
    <div class="col-span-1 md:col-span-4 bg-[#17B8A6] relative p-6 md:p-12 text-white flex flex-col justify-between overflow-hidden">
        <!-- Decoration (Hidden on mobile to save space) -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl hidden md:block"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-900/20 rounded-full -ml-16 -mb-16 blur-3xl hidden md:block"></div>

        <div class="relative z-10">
            <!-- Brand -->
            <div class="flex items-center gap-3 mb-6 md:mb-10">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                    <i class="fas fa-heartbeat text-lg md:text-xl"></i>
                </div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight">Telerehab</h2>
            </div>

            <!-- Header Info -->
            <div class="mb-6 md:mb-12">
                <h1 class="text-2xl md:text-3xl font-bold mb-1 md:mb-2">{{ $this->stepTitle }}</h1>
                <p class="text-teal-50 opacity-90 text-xs md:text-sm leading-relaxed">{{ $this->stepDescription }}</p>
            </div>

            <!-- Steps Indicator (Responsive: Horizontal on Mobile, Vertical on Desktop) -->
            <div class="flex md:flex-col items-center md:items-stretch justify-between md:justify-start gap-0 md:gap-6 relative">
                <!-- Mobile Connector Line (Only visible on mobile) -->
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-teal-900/20 -z-0 md:hidden -translate-y-1/2"></div>

                <!-- Step 1 Indicator -->
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-2 md:gap-4 {{ $currentStep >= 1 ? 'opacity-100' : 'opacity-50' }} transition-opacity duration-300">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 flex items-center justify-center text-xs md:text-sm font-bold shadow-sm {{ $currentStep > 1 ? 'bg-white text-[#17B8A6] border-white' : ($currentStep == 1 ? 'bg-white/20 border-white text-white' : 'bg-[#17B8A6] border-teal-200/50 text-teal-100') }}">
                            @if($currentStep > 1) <i class="fas fa-check"></i> @else 1 @endif
                        </div>
                        <!-- Desktop Line -->
                        <div class="hidden md:block w-0.5 h-10 {{ $currentStep > 1 ? 'bg-white/50' : 'bg-teal-200/20' }}"></div>
                    </div>
                    <div class="hidden md:block pt-1"> <!-- Text hidden on mobile -->
                        <h4 class="font-bold text-sm">Account & Contact</h4>
                        <p class="text-xs text-teal-100">Basic user data</p>
                    </div>
                </div>

                <!-- Step 2 Indicator -->
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-2 md:gap-4 {{ $currentStep >= 2 ? 'opacity-100' : 'opacity-50' }} transition-opacity duration-300">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 flex items-center justify-center text-xs md:text-sm font-bold shadow-sm {{ $currentStep > 2 ? 'bg-white text-[#17B8A6] border-white' : ($currentStep == 2 ? 'bg-white/20 border-white text-white' : 'bg-[#17B8A6] border-teal-200/50 text-teal-100') }}">
                            @if($currentStep > 2) <i class="fas fa-check"></i> @else 2 @endif
                        </div>
                        <div class="hidden md:block w-0.5 h-10 {{ $currentStep > 2 ? 'bg-white/50' : 'bg-teal-200/20' }}"></div>
                    </div>
                    <div class="hidden md:block pt-1">
                        <h4 class="font-bold text-sm">Medical Data</h4>
                        <p class="text-xs text-teal-100">Address & condition</p>
                    </div>
                </div>

                <!-- Step 3 Indicator -->
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-2 md:gap-4 {{ $currentStep >= 3 ? 'opacity-100' : 'opacity-50' }} transition-opacity duration-300">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 flex items-center justify-center text-xs md:text-sm font-bold shadow-sm {{ $currentStep == 3 ? 'bg-white/20 border-white text-white' : 'bg-[#17B8A6] border-teal-200/50 text-teal-100' }}">
                            3
                        </div>
                    </div>
                    <div class="hidden md:block pt-1">
                        <h4 class="font-bold text-sm">Document & BPJS (Optional, Indonesia Only)</h4>
                        <p class="text-xs text-teal-100">Upload documents</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Left (Desktop Only) -->
        <div class="relative z-10 mt-auto pt-8 hidden md:block">
            <p class="text-xs text-teal-100 opacity-70">&copy; {{ date('Y') }} Telerehab System. Secure Registration.</p>
        </div>
    </div>

    <!-- RIGHT SIDE: Form Area -->
    <div class="col-span-1 md:col-span-8 bg-slate-50 p-6 md:p-12 lg:p-16 flex flex-col justify-center relative">
        <form wire:submit.prevent="register">

            <!-- STEP 1: Account & Contact -->
            <div class="{{ $currentStep != 1 ? 'hidden' : 'block' }} space-y-4 md:space-y-5 animate-fade-in-up">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <!-- Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <!-- Hapus atribut 'required' HTML agar tombol Lanjut berfungsi -->
                        <input type="text" wire:model="name" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="Masukkan nama sesuai KTP">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="contoh@email.com">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"> WhatsApp/HP Number<span class="text-red-500">*</span></label>
                        <input type="tel" wire:model="telephone" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="0812...">
                        @error('telephone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-span-1 md:col-span-2" x-data="{ show: false }">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all pr-10" placeholder="Minimal 6 karakter">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- STEP 2: Medical Info -->
            <div class="{{ $currentStep != 2 ? 'hidden' : 'block' }} space-y-4 md:space-y-5 animate-fade-in-up">
                <!-- Address -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Full Address <span class="text-red-500">*</span></label>
                    <textarea wire:model="address" rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <!-- Medical Record -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Medical Record Number (Optional)</label>
                        <input type="text" wire:model="medical_record_number" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="RM-XXXXXX">
                        @error('medical_record_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Prosthetic Since -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Using Prosthetic Since <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="prosthetic_since" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all">
                        @error('prosthetic_since') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Prosthetic Description -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Type of Prosthetic Used <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="prosthetic" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="Contoh: Kaki Palsu Bawah Lutut (Transtibial)">
                        @error('prosthetic') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- STEP 3: Documents -->
            <div class="{{ $currentStep != 3 ? 'hidden' : 'block' }} space-y-5 md:space-y-6 animate-fade-in-up">

                <!-- BPJS Number -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">BPJS Number (Optional, Indonesia Only)</label>
                    <input type="number" wire:model="bpjs_number" class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all" placeholder="000123456789">
                    @error('bpjs_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <!-- Upload Patient Condition -->
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 md:p-6 text-center hover:border-[#17B8A6] hover:bg-teal-50/50 transition-all cursor-pointer relative group">
                        <input type="file" wire:model="patient_condition" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <i class="fas fa-camera text-lg md:text-xl"></i>
                            </div>
                            <h5 class="text-xs md:text-sm font-bold text-slate-700">Patient Condition Photos</h5>
                            <p class="text-[10px] md:text-xs text-slate-400">Upload multiple photos (Max 2MB/photo)</p>
                        </div>

                        <!-- Preview Count -->
                        @if($patient_condition)
                            <div class="mt-2 text-[10px] md:text-xs text-green-600 font-bold bg-green-50 py-1 px-2 rounded-lg inline-block">
                                <i class="fas fa-check-circle"></i> {{ count($patient_condition) }} file
                            </div>
                        @endif
                        @error('patient_condition.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Upload BPJS Card -->
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 md:p-6 text-center hover:border-[#17B8A6] hover:bg-teal-50/50 transition-all cursor-pointer relative group">
                        <input type="file" wire:model="bpjs_card" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <i class="fas fa-id-card text-lg md:text-xl"></i>
                            </div>
                            <h5 class="text-xs md:text-sm font-bold text-slate-700">BPJS Card Photo (Optional, Indonesia Only)</h5>
                            <p class="text-[10px] md:text-xs text-slate-400">Upload (Max 2MB)</p>
                        </div>
                        @if($bpjs_card)
                            <div class="mt-2 text-[10px] md:text-xs text-green-600 font-bold bg-green-50 py-1 px-2 rounded-lg inline-block">
                                <i class="fas fa-check-circle"></i> Selected
                            </div>
                        @endif
                        @error('bpjs_card') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fas fa-info-circle text-yellow-600 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-yellow-700 leading-relaxed">
                        Make sure the data is correct. Patient condition photos help doctors' assessment.
                    </p>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between mt-8 md:mt-10 pt-6 border-t border-slate-200">
                <!-- Back Button -->
                @if($currentStep > 1)
                    <button type="button" wire:click="previousStep" wire:loading.attr="disabled" class="px-4 md:px-6 py-2 md:py-3 rounded-xl border-2 border-slate-200 text-slate-600 text-sm md:text-base font-bold hover:bg-slate-100 hover:text-slate-800 transition-all flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                @else
                    <div class="text-xs md:text-sm text-slate-500">
                        Already have an account? <a href="{{ route('auth.login') }}" class="text-[#17B8A6] font-bold hover:underline">Login</a>
                    </div>
                @endif

                <!-- Next / Submit Button -->
                @if($currentStep < $totalSteps)
                    <!-- Tambahkan wire:loading.attr="disabled" dan loading indicator -->
                    <button type="button" wire:click="nextStep" wire:loading.attr="disabled" class="px-6 md:px-8 py-2 md:py-3 rounded-xl bg-[#17B8A6] text-white text-sm md:text-base font-bold hover:bg-[#0d9488] shadow-lg shadow-[#17B8A6]/20 transition-all flex items-center gap-2 ml-auto">
                        <span wire:loading.remove wire:target="nextStep">Next</span>
                        <span wire:loading wire:target="nextStep"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                        <i class="fas fa-arrow-right" wire:loading.remove wire:target="nextStep"></i>
                    </button>
                @else
                    <button type="submit" class="px-6 md:px-8 py-2 md:py-3 rounded-xl bg-[#17B8A6] text-white text-sm md:text-base font-bold hover:bg-[#0d9488] shadow-lg shadow-[#17B8A6]/20 transition-all flex items-center gap-2 ml-auto" wire:loading.attr="disabled">
                        <span wire:loading.remove>Register Now</span>
                        <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Processing...</span>
                    </button>
                @endif
            </div>

        </form>
    </div>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
