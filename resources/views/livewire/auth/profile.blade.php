<div>
    @if(Auth::user()->role === 'patient')
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">

        <div class="bg-slate-50 border-b border-slate-200 px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-injured text-primary-600"></i>
                    Patient Admission Form
                </h2>
                <p class="text-sm text-slate-500">Create or edit patient medical records.</p>
            </div>
            <div class="hidden md:flex gap-3">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 font-bold hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button form="patientForm" type="submit"
                    class="px-6 py-2 rounded-lg bg-primary-600 text-white font-bold hover:bg-primary-700 shadow-md transition-colors flex items-center gap-2 hover:cursor-pointer">
                    <i class="fas fa-save"></i> Save Record
                </button>
            </div>
        </div>

        <form id="patientForm" wire:submit.prevent="updatePatient" enctype="multipart/form-data">
            <div class="flex flex-col lg:flex-row h-full">

                <div class="w-full lg:w-2/3 p-6 lg:p-8 space-y-8 border-b lg:border-b-0 lg:border-r border-slate-200">

                    <div>
                        <h3
                            class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-id-card"></i> Identity Information
                        </h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1 md:col-span-1 col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Full Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                                    placeholder="Patient Full Name">
                            </div>
                            <div class="space-y-1 md:col-span-1 col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                                    placeholder="email@example.com">
                            </div>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 col-span-2"
                        x-data="{ 
        iti: null,
        countries: [],
        search: '',
        open: false,
        selectedCountry: @entangle('country'),
        selectedIso: '',

        get filteredCountries() {
            return this.countries.filter(c => 
                c.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        init() {
            // 1. Inisialisasi library
            this.iti = window.intlTelInput(this.$refs.phone, {
                initialCountry: 'id',
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/js/utils.js',
            });

            // 2. Ambil list negara untuk dropdown
            this.countries = window.intlTelInput.getCountryData();

            // 3. Fungsi Sinkronisasi (Hanya kirim ke Livewire tanpa render ulang input)
            const syncData = () => {
                let countryData = this.iti.getSelectedCountryData();
                let inputNumber = this.$refs.phone.value.replace(/^0+/, '').replace(/\D/g, '');
                
                // Gunakan .set dengan parameter 'false' agar tidak selalu memicu re-render total jika tidak perlu
                @this.set('telephone', countryData.dialCode + inputNumber, false);
            };

            // Listener: Gunakan input agar real-time tapi tidak merusak fokus
            this.$refs.phone.addEventListener('input', syncData);
            
            // Listener: Saat bendera di input phone berubah
            this.$refs.phone.addEventListener('countrychange', () => {
                syncData();
                // Opsional: Jika ingin country dropdown kiri ikut berubah otomatis:
                // let data = this.iti.getSelectedCountryData();
                // this.selectedCountry = data.name;
                // this.selectedIso = data.iso2;
            });

            // 4. Set Initial State untuk Edit Profile
            if (this.selectedCountry) {
                let found = this.countries.find(c => c.name === this.selectedCountry);
                if (found) this.selectedIso = found.iso2;
            }

            let initialPhone = @js($telephone);
            if (initialPhone) {
                // Berikan jeda sedikit agar utilsScript siap
                setTimeout(() => {
                    let formatted = initialPhone.startsWith('+') ? initialPhone : '+' + initialPhone;
                    this.iti.setNumber(formatted);
                }, 100);
            }
        }
     }">

                        <div class="w-full relative">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Country Origin <span class="text-red-500">*</span></label>

                            <div @click="open = !open"
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer flex justify-between items-center transition-all hover:border-slate-300"
                                :class="open ? 'border-[#17B8A6] ring-4 ring-[#17B8A6]/10' : ''">
                                <div class="flex items-center gap-3">
                                    <template x-if="selectedIso">
                                        <div :class="'iti__flag iti__' + selectedIso"></div>
                                    </template>
                                    <span x-text="selectedCountry || 'Select Country'" :class="!selectedCountry ? 'text-slate-400' : 'text-slate-700'"></span>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                @error('country') <span class="text-red-500 text-xs mt-1 block absolute -bottom-5 left-0">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute z-[60] mt-2 w-full bg-white border-2 border-slate-100 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-50">
                                    <input type="text" x-model="search" placeholder="Search country..."
                                        class="w-full px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-[#17B8A6]/20 text-sm">
                                </div>
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    <template x-for="c in filteredCountries" :key="c.iso2">
                                        <div @click="selectedCountry = c.name; selectedIso = c.iso2; open = false; search = ''"
                                            class="px-4 py-2 text-sm text-slate-600 hover:bg-[#17B8A6]/10 hover:text-[#17B8A6] cursor-pointer flex items-center gap-3 transition-colors"
                                            :class="selectedCountry === c.name ? 'bg-slate-50 font-bold text-[#17B8A6]' : ''">
                                            <div :class="'iti__flag iti__' + c.iso2"></div>
                                            <span x-text="c.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- PENTING: wire:ignore harus di pembungkus paling luar input phone agar Livewire tidak menyentuh DOM ini --}}
                        <div class="w-full" wire:ignore>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                WhatsApp Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    x-ref="phone"
                                    type="tel"
                                    {{-- JANGAN pakai wire:model di sini karena merusak fokus saat re-render --}}
                                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all"
                                    placeholder="812xxxx">
                            </div>
                        </div>
                        @error('telephone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <style>
                        .iti {
                            width: 100%;
                        }

                        .iti__flag {
                            transform: scale(1.2);
                        }

                        [x-cloak] {
                            display: none !important;
                        }

                        .custom-scrollbar::-webkit-scrollbar {
                            width: 5px;
                        }

                        .custom-scrollbar::-webkit-scrollbar-thumb {
                            background: #e2e8f0;
                            border-radius: 10px;
                        }
                    </style>
                            <div class="space-y-1 col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Password (Keep it blank if you don't want to change)</label>
                                <input type="password" wire:model="password"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"
                                    placeholder="••••••">
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Address <span
                                        class="text-red-500">*</span></label>
                                <textarea wire:model="address" rows="2"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all resize-none"
                                    placeholder="Full residential address"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div>
                        <h3
                            class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-stethoscope"></i> Medical Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Medical Record No.</label>
                                <input type="text" wire:model="medical_record_number"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 font-mono text-slate-800"
                                    placeholder="RM-00000">
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">BPJS Number</label>
                                <input type="text" wire:model="bpjs_number"
                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 font-mono text-slate-800"
                                    placeholder="000123456">
                            </div>
                            <div
                                class="md:col-span-2 p-4 bg-blue-50/50 rounded-xl border border-blue-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-blue-800">Prosthetic Type</label>
                                    <input type="text" wire:model="prosthetic"
                                        class="w-full px-4 py-2.5 rounded-lg border border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none bg-white"
                                        placeholder="e.g. Kaki Palsu Bawah Lutut">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-blue-800">Installed Since</label>
                                    <input type="date" wire:model="prosthetic_since"
                                        class="w-full px-4 py-2.5 rounded-lg border border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none bg-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/3 bg-slate-50/50 p-6 lg:p-8 space-y-8">

                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <label class="text-sm font-bold text-slate-700">Patient Condition Photos</label>
                            <span class="text-xs text-slate-400">Max {{ config('upload.max_size_mb') }}MB/Image</span>
                        </div>

                        <div
                            class="relative group w-full h-32 border-2 border-dashed border-slate-300 rounded-xl hover:border-primary-500 hover:bg-primary-50 transition-all cursor-pointer flex flex-col items-center justify-center text-center mb-4">
                            <input type="file" wire:model="patient_condition" multiple accept="{{ config('upload.image_accept') }}"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <i
                                class="fas fa-camera text-2xl text-slate-400 group-hover:text-primary-500 mb-2 transition-colors"></i>
                            <p class="text-xs font-semibold text-slate-500 group-hover:text-primary-600">Click to upload
                                photos</p>
                        </div>

                        @error('patient_condition.*')
                            <span class="text-red-500 text-xs mb-3 block">{{ $message }}</span>
                        @enderror

                        @if((is_array($patient_condition) && count($patient_condition) > 0) || ($old_patient_condition && count($old_patient_condition) > 0))
                            <div class="grid grid-cols-2 gap-3">

                                @if($patient_condition && !is_string($patient_condition))
                                    @foreach($patient_condition as $photo)
                                        <div
                                            class="relative group aspect-square rounded-lg overflow-hidden shadow-sm border border-emerald-300">
                                            <span
                                                class="absolute top-1 left-1 bg-emerald-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm z-10">NEW</span>

                                            <img src="{{ $photo->temporaryUrl() }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if($old_patient_condition)
                                    @foreach($old_patient_condition as $photo)
                                        <div
                                            class="relative group aspect-square rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                            <img src="{{ Storage::url($photo['url']) }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                            <a href="{{ Storage::url($photo['url']) }}" target="_blank"
                                                class="absolute inset-0 bg-black/0 group-hover:bg-black/40 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                                <i class="fas fa-expand-alt text-white"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="text-center p-6 border border-slate-100 rounded-xl bg-white">
                                <p class="text-xs text-slate-400 italic">No photos available yet.</p>
                            </div>
                        @endif
                    </div>

                    <hr class="border-slate-200">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">BPJS Card Scan</label>

                        <div class="flex flex-col gap-4">
                            <div
                                class="relative flex items-center gap-3 p-3 border border-slate-200 rounded-lg bg-white">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                                    <i class="far fa-id-card"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-xs font-semibold text-slate-700">Change Card File</p>
                                    <p class="text-[10px] text-slate-400">JPG/PNG/PDF, max {{ config('upload.max_size_mb') }}MB</p>
                                </div>
                                <input type="file" wire:model="bpjs_card" accept="{{ config('upload.document_accept') }}"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>

                            <div
                                class="w-full aspect-video bg-slate-200 rounded-xl overflow-hidden border border-slate-300 relative group">
                                @if($bpjs_card && !is_string($bpjs_card))
                                    <div
                                        class="absolute top-2 right-2 z-10 bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded">
                                        New File</div>
                                    <x-document-preview :url="$bpjs_card->temporaryUrl()"
                                        :pdf="$bpjs_card->getMimeType() === 'application/pdf'" />
                                @elseif($old_bpjs_card)
                                    <x-document-preview :url="asset('storage/'.$old_bpjs_card)"
                                        :pdf="str_ends_with(strtolower($old_bpjs_card), '.pdf')" />
                                    <a href="{{ asset('storage/'.$old_bpjs_card) }}" target="_blank"
                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span
                                            class="text-white text-xs font-bold border border-white px-3 py-1 rounded-full">View
                                            Full</span>
                                    </a>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                        <i class="far fa-image text-3xl mb-2"></i>
                                        <span class="text-xs">No card uploaded</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="md:hidden p-4 border-t border-slate-200 bg-white sticky bottom-0 z-20">
                <button type="submit" class="w-full py-3 rounded-xl bg-primary-600 text-white font-bold shadow-lg">
                    Save Record
                </button>
            </div>
        </form>
    </div>
    @else
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

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 col-span-2"
                        x-data="{ 
        iti: null,
        countries: [],
        search: '',
        open: false,
        selectedCountry: @entangle('country'),
        selectedIso: '',

        get filteredCountries() {
            return this.countries.filter(c => 
                c.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },

        init() {
            // 1. Inisialisasi library
            this.iti = window.intlTelInput(this.$refs.phone, {
                initialCountry: 'id',
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/js/utils.js',
            });

            // 2. Ambil list negara untuk dropdown
            this.countries = window.intlTelInput.getCountryData();

            // 3. Fungsi Sinkronisasi (Hanya kirim ke Livewire tanpa render ulang input)
            const syncData = () => {
                let countryData = this.iti.getSelectedCountryData();
                let inputNumber = this.$refs.phone.value.replace(/^0+/, '').replace(/\D/g, '');
                
                // Gunakan .set dengan parameter 'false' agar tidak selalu memicu re-render total jika tidak perlu
                @this.set('telephone', countryData.dialCode + inputNumber, false);
            };

            // Listener: Gunakan input agar real-time tapi tidak merusak fokus
            this.$refs.phone.addEventListener('input', syncData);
            
            // Listener: Saat bendera di input phone berubah
            this.$refs.phone.addEventListener('countrychange', () => {
                syncData();
                // Opsional: Jika ingin country dropdown kiri ikut berubah otomatis:
                // let data = this.iti.getSelectedCountryData();
                // this.selectedCountry = data.name;
                // this.selectedIso = data.iso2;
            });

            // 4. Set Initial State untuk Edit Profile
            if (this.selectedCountry) {
                let found = this.countries.find(c => c.name === this.selectedCountry);
                if (found) this.selectedIso = found.iso2;
            }

            let initialPhone = @js($telephone);
            if (initialPhone) {
                // Berikan jeda sedikit agar utilsScript siap
                setTimeout(() => {
                    let formatted = initialPhone.startsWith('+') ? initialPhone : '+' + initialPhone;
                    this.iti.setNumber(formatted);
                }, 100);
            }
        }
     }">

                        <div class="w-full relative">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Country Origin</label>

                            <div @click="open = !open"
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-white cursor-pointer flex justify-between items-center transition-all hover:border-slate-300"
                                :class="open ? 'border-[#17B8A6] ring-4 ring-[#17B8A6]/10' : ''">
                                <div class="flex items-center gap-3">
                                    <template x-if="selectedIso">
                                        <div :class="'iti__flag iti__' + selectedIso"></div>
                                    </template>
                                    <span x-text="selectedCountry || 'Select Country'" :class="!selectedCountry ? 'text-slate-400' : 'text-slate-700'"></span>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                                @error('country') <span class="text-red-500 text-xs mt-1 block absolute -bottom-5 left-0">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute z-[60] mt-2 w-full bg-white border-2 border-slate-100 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-50">
                                    <input type="text" x-model="search" placeholder="Search country..."
                                        class="w-full px-3 py-2 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-[#17B8A6]/20 text-sm">
                                </div>
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    <template x-for="c in filteredCountries" :key="c.iso2">
                                        <div @click="selectedCountry = c.name; selectedIso = c.iso2; open = false; search = ''"
                                            class="px-4 py-2 text-sm text-slate-600 hover:bg-[#17B8A6]/10 hover:text-[#17B8A6] cursor-pointer flex items-center gap-3 transition-colors"
                                            :class="selectedCountry === c.name ? 'bg-slate-50 font-bold text-[#17B8A6]' : ''">
                                            <div :class="'iti__flag iti__' + c.iso2"></div>
                                            <span x-text="c.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- PENTING: wire:ignore harus di pembungkus paling luar input phone agar Livewire tidak menyentuh DOM ini --}}
                        <div class="w-full" wire:ignore>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                WhatsApp Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    x-ref="phone"
                                    type="tel"
                                    {{-- JANGAN pakai wire:model di sini karena merusak fokus saat re-render --}}
                                    class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-[#17B8A6] focus:ring-[#17B8A6]/20 bg-white transition-all"
                                    placeholder="812xxxx">
                            </div>
                        </div>
                        @error('telephone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <style>
                        .iti {
                            width: 100%;
                        }

                        .iti__flag {
                            transform: scale(1.2);
                        }

                        [x-cloak] {
                            display: none !important;
                        }

                        .custom-scrollbar::-webkit-scrollbar {
                            width: 5px;
                        }

                        .custom-scrollbar::-webkit-scrollbar-thumb {
                            background: #e2e8f0;
                            border-radius: 10px;
                        }
                    </style>

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
                <button type="submit" class="w-full bg-[#17B8A6] hover:bg-[#13978b] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#17B8A6]/20 hover:shadow-[#17B8A6]/40 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group mt-6 disabled:opacity-50 disabled:cursor-not-allowed hover:cursor-pointer" wire:loading.attr="disabled">
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
    @endif
</div>
