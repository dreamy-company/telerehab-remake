<div x-data="{ mobileMenuOpen: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)">

    <nav :class="{ 'bg-white/90 backdrop-blur-xl shadow-sm border-b border-slate-200/50': scrolled, 'bg-white border-b border-transparent': !scrolled }"
         class="sticky top-0 left-0 right-0 z-40 w-full transition-all duration-300 h-20">

        <div class="container mx-auto px-4 lg:px-8 h-full flex justify-between items-center">

            <a href="/" class="flex-shrink-0 transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('assets/images/logo_telerehab.jpeg') }}" alt="Telerehab logo" class="h-10 w-auto object-contain">
            </a>

            <div class="hidden md:flex items-center gap-5">

                <div x-data="{
                        open: false,
                        currentLang: 'EN',
                        flag: '🇺🇸',

                        init() {
                            const storedLang = localStorage.getItem('app_lang');
                            if (storedLang) {
                                this.setLangState(storedLang);
                                if(storedLang === 'ID') setTimeout(() => triggerGoogleTranslate('id'), 500);
                            } else {
                                const userLang = navigator.language || navigator.userLanguage;
                                if (userLang.toLowerCase().includes('id')) {
                                    this.setLangState('ID');
                                    setTimeout(() => triggerGoogleTranslate('id'), 1500);
                                }
                            }
                        },

                        setLangState(lang) {
                            this.currentLang = lang;
                            this.flag = (lang === 'ID') ? '🇮🇩' : '🇺🇸';
                        },

                        switchLang(lang) {
                            this.setLangState(lang);
                            localStorage.setItem('app_lang', lang);
                            triggerGoogleTranslate(lang.toLowerCase());
                            this.open = false;
                        }
                     }"
                     class="relative notranslate">

                    <button @click="open = !open"
                            @click.away="open = false"
                            class="flex items-center gap-2 px-3 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-full shadow-sm transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-[#17B8A6]/20">
                        <span class="text-lg leading-none filter drop-shadow-sm" x-text="flag"></span>
                        <span class="text-sm font-bold text-slate-600 group-hover:text-slate-800 w-5" x-text="currentLang"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                           :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-3 w-40 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden z-50 origin-top-right"
                         style="display: none;">
                        <div class="py-1">
                            <button @click="switchLang('EN')" class="w-full text-left px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-[#17B8A6]/5 hover:text-[#17B8A6] flex items-center gap-3 transition-colors">
                                <span class="text-lg">🇺🇸</span> English
                            </button>
                            <button @click="switchLang('ID')" class="w-full text-left px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-[#17B8A6]/5 hover:text-[#17B8A6] flex items-center gap-3 transition-colors">
                                <span class="text-lg">🇮🇩</span> Indonesia
                            </button>
                        </div>
                    </div>
                </div>

                <div class="h-8 w-px bg-slate-200"></div>

                <div class="flex items-center gap-3">

                                @if (Auth::user())
                                <a href="{{ route('dashboard') }}" class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-600 font-bold flex justify-center hover:border-[#17B8A6] hover:text-[#17B8A6]">Dashboard</a>
                                
                                @else
                                <a href="{{ route('auth.login') }}" class="w-full py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold flex justify-center hover:border-[#17B8A6] hover:text-[#17B8A6]">Sign In</a>
                                <a href="{{ route('auth.register') }}" class="w-full py-3 rounded-xl bg-[#17B8A6] text-white font-bold flex justify-center shadow-lg shadow-[#17B8A6]/20">Create Account</a>
                                @endif
                </div>
            </div>

            <button @click="mobileMenuOpen = true"
                    class="md:hidden p-2 text-slate-600 hover:text-[#17B8A6] transition-colors focus:outline-none rounded-lg hover:bg-slate-100">
                <i class="fas fa-bars text-xl"></i>
            </button>

        </div>
    </nav>

    <div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-show="mobileMenuOpen" style="display: none;">

        <div x-show="mobileMenuOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm transition-opacity"
             @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

                    <div x-show="mobileMenuOpen"
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="pointer-events-auto w-screen max-w-xs relative z-50">

                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl">
                            <div class="px-6 py-6 flex items-center justify-between border-b border-slate-100 h-20">
                                <h2 class="text-lg font-bold text-slate-800">Menu</h2>
                                <button @click="mobileMenuOpen = false" class="text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <div class="px-6 py-6 flex flex-col gap-4">
                                <div class="h-px bg-slate-100 my-2"></div>

                                @if (Auth::user())
                                <a href="{{ route('dashboard') }}" class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-600 font-bold flex justify-center hover:border-[#17B8A6] hover:text-[#17B8A6]">Dashboard</a>
                                
                                @else
                                <a href="{{ route('auth.login') }}" class="w-full py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold flex justify-center hover:border-[#17B8A6] hover:text-[#17B8A6]">Sign In</a>
                                <a href="{{ route('auth.register') }}" class="w-full py-3 rounded-xl bg-[#17B8A6] text-white font-bold flex justify-center shadow-lg shadow-[#17B8A6]/20">Create Account</a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
