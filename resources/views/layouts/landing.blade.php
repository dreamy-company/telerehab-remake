<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telerehab - Solusi Pemulihan Tangan</title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        // Script Theme Mode Sederhana
        const defaultThemeMode = 'light';
        let themeMode;
        if (document.documentElement) {
            if (localStorage.getItem('theme')) {
                themeMode = localStorage.getItem('theme');
            } else if (document.documentElement.hasAttribute('data-theme-mode')) {
                themeMode = document.documentElement.getAttribute('data-theme-mode');
            } else {
                themeMode = defaultThemeMode;
            }
            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.classList.add(themeMode);
        }
    </script>

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

</head>

<body>

    <!-- LOAD EXTERNAL RESOURCES -->
    <!-- FontAwesome (Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Navbar Include (Wrapper) -->
    <!-- Mengganti bg-white menjadi bg-slate-50 agar tidak terlalu silau -->
        @include('layouts.partials.landing.navbar')

    <main class="">
        <div id="mainContainer">
            {{ $slot }}
        </div>
    </main>
    <style>
    .goog-te-banner-frame.skiptranslate { display: none !important; }
    body { top: 0px !important; }
    .goog-tooltip { display: none !important; }
    .goog-tooltip:hover { display: none !important; }
    .goog-text-highlight { background-color: transparent !important; box-shadow: none !important; }
    .goog-logo-link { display: none !important; }
    .goog-te-gadget { height: 0 !important; overflow: hidden; }
</style>

    @livewireScripts



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
                Swal.fire({ title: "Berhasil!", text: message, icon: "success", confirmButtonColor: '#17B8A6' });
            });
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
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
                Swal.fire({ title: "Berhasil!", text: message, icon: "success", confirmButtonColor: '#17B8A6' });
            });
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>

</html>
