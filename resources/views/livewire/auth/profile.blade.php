<div>
    <div class="w-full max-w-2xl mx-auto bg-slate-50/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-300/50 overflow-hidden border border-slate-200 relative transform transition-all hover:shadow-brand-500/5">

        <!-- Center: Profile Form -->
        <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center relative bg-slate-50">
            
            <!-- Header Text -->
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-2">Profile</h1>
                <p class="text-slate-500 font-medium">Update your profile information.</p>
            </div>

            <!-- Form dengan wire:submit -->
            <form class="space-y-6" wire:submit.prevent="updateProfile">
                
                <!-- Name Input -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Full Name</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                            <i class="fas fa-user"></i>
                        </div>
                        <input type="text" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800"
                            placeholder="John Doe"
                            wire:model="name"
                            autocomplete="name">
                    </div>
                    @error('name') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800"
                            placeholder="hello@telerehab.id"
                            wire:model="email"
                            autocomplete="email">
                    </div>
                    @error('email') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

            
             <div 
     x-data="{ 
        iti: null,
        initialPhone: @js($telephone), 
        sync() {
            let countryData = this.iti.getSelectedCountryData();
            let dialCode = countryData.dialCode; 
            let countryName = countryData.name;
            
            let inputNumber = this.$refs.phone.value.replace(/^0+/, '').replace(/\D/g, '');
            let fullNumber = dialCode + inputNumber;
            
            this.$refs.hiddenPhone.value = fullNumber;
            this.$refs.hiddenPhone.dispatchEvent(new Event('input'));

            this.$refs.hiddenCountry.value = countryName;
            this.$refs.hiddenCountry.dispatchEvent(new Event('input'));
        },
        init() {
            this.iti = window.intlTelInput(this.$refs.phone, {
                initialCountry: 'auto', // Biarkan library mendeteksi dari nomor
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/js/utils.js',
                geoIpLookup: callback => {
                    fetch('https://ipapi.co/json')
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback('id'));
                },
            });

            if (this.initialPhone) {
                let formattedPhone = this.initialPhone.startsWith('+') ? this.initialPhone : '+' + this.initialPhone;
                this.iti.setNumber(formattedPhone);
            }

            setTimeout(() => this.sync(), 500);

            this.$refs.phone.addEventListener('input', () => this.sync());
            this.$refs.phone.addEventListener('countrychange', () => this.sync());
        }
     }"
     wire:ignore>

    <label class="block text-sm font-bold text-slate-700 mb-2">
        WhatsApp/HP Number 
    </label>

    <div class="relative">
        <input
            x-ref="phone"
            type="tel"
            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all"
            placeholder="882xxxx">
            
        <input type="hidden" x-ref="hiddenPhone" wire:model.live="telephone">
        <input type="hidden" x-ref="hiddenCountry" wire:model.live="country">
    </div>

    @error('telephone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    @error('country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
</div>

                <!-- Password Input -->
                <div x-data="{ show: false }">
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password <span class="text-slate-400 font-normal">(Leave blank to keep current)</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'"
                            class="w-full pl-12 pr-12 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800"
                            placeholder="••••••••"
                            wire:model="password"
                            id="password">
                        <button type="button" class="absolute right-0 inset-y-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" @click="show = !show">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password Input -->
                <div x-data="{ show: false }">
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'"
                            class="w-full pl-12 pr-12 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800"
                            placeholder="••••••••"
                            wire:model="password_confirmation"
                            id="password_confirmation">
                        <button type="button" class="absolute right-0 inset-y-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" @click="show = !show">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#17B8A6] hover:bg-[#13978b] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#17B8A6]/20 hover:shadow-[#17B8A6]/40 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group mt-6 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                    <span wire:loading.remove>Save Changes</span>
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Processing...</span>
                    <i class="fas fa-check group-hover:translate-x-1 transition-transform" wire:loading.remove></i>
                </button>
            </form>

            <!-- Back Button -->
            <div class="text-center mt-6">
                <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
