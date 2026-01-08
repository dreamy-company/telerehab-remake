<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - Telerehab' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#17B8A6', 600: '#0d9488' }, // Teal Color
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        .goog-te-banner-frame.skiptranslate { display: none !important; }
        body { top: 0px !important; }
        .goog-tooltip, .goog-logo-link { display: none !important; }
        .goog-te-gadget { height: 0 !important; overflow: hidden; }

        /* Animasi Background Blob */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased flex items-center justify-center min-h-screen overflow-y-auto p-4 relative overflow-hidden">

    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-3xl mix-blend-multiply opacity-60 animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-blue-400/10 rounded-full blur-3xl mix-blend-multiply opacity-60 animate-blob animation-delay-2000"></div>
    </div>

    <div class="absolute top-12 right-6 z-50">
        <div x-data="{
                open: false,
                currentLang: 'EN', // Default awal adalah Inggris
                flag: '🇺🇸',

                init() {
                    // 1. Cek Local Storage (Prioritas Utama)
                    const storedLang = localStorage.getItem('app_lang');
                    if (storedLang) {
                        this.setLangState(storedLang);
                        // Trigger translate hanya jika ID, karena default page sudah EN
                        if(storedLang === 'ID') setTimeout(() => triggerGoogleTranslate('id'), 500);
                    } else {
                        // 2. Cek Browser Device (Jika belum ada settingan)
                        const userLang = navigator.language || navigator.userLanguage;

                        // Jika device berbahasa Indonesia -> Ubah ke ID
                        if (userLang.toLowerCase().includes('id')) {
                            this.setLangState('ID');
                            setTimeout(() => triggerGoogleTranslate('id'), 1500);
                        }
                        // ELSE (selain ID) -> Biarkan tetap pada default 'EN'
                    }
                },

                setLangState(lang) {
                    this.currentLang = lang;
                    this.flag = (lang === 'ID') ? '🇮🇩' : '🇺🇸';
                },

                switchLang(lang) {
                    this.setLangState(lang);
                    localStorage.setItem('app_lang', lang); // Simpan pilihan user
                    triggerGoogleTranslate(lang.toLowerCase());
                    this.open = false; // Tutup dropdown
                }
             }"
             class="relative notranslate"> <button @click="open = !open"
                    @click.away="open = false"
                    class="flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-full shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20">

                <span class="text-lg leading-none" x-text="flag"></span>
                <span class="text-sm font-bold text-slate-600 w-6" x-text="currentLang"></span>

                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                   :class="{'rotate-180': open}"></i>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50"
                 style="display: none;">

                <button @click="switchLang('EN')"
                        class="w-full text-left px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-brand-500/5 hover:text-brand-500 flex items-center gap-3 transition-colors">
                    <span class="text-lg">🇺🇸</span> English
                </button>

                <button @click="switchLang('ID')"
                        class="w-full text-left px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-brand-500/5 hover:text-brand-500 flex items-center gap-3 transition-colors">
                    <span class="text-lg">🇮🇩</span> Indonesia
                </button>
            </div>

        </div>
    </div>

    <div class="w-full max-w-5xl relative z-10 overflow-y-auto">
        {{ $slot }}
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div id="google_translate_element" class="hidden"></div>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,id',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function triggerGoogleTranslate(lang) {
            const select = document.querySelector('.goog-te-combo');
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            }
        }

        // Listener Alert
        document.addEventListener('livewire:init', function() {
            Livewire.on('alert-error', (message) => {
                Swal.fire({ title: "Gagal!", text: message, icon: "error", confirmButtonColor: '#17B8A6' });
            });
            Livewire.on('alert-success', (message) => {
                Swal.fire({ title: "Success!", text: message, icon: "success", confirmButtonColor: '#17B8A6' });
            });
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
