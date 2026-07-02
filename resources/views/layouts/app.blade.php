<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<!-- Mirrored from telerehab-prototype-new.gretiva.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Dec 2025 08:40:47 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telerehab - Solusi Pemulihan Tangan</title>
    <script src="https://cdn.tailwindcss.com/"></script>
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body>

    <!-- Top Controls: Role & Lang Switcher -->
    <div class="top-controls">



    </div>

    <!-- Scroll Assist Buttons -->
    <!-- <div class="scroll-assist lg:flex hidden">
        <button onclick="scrollPage('up')" class="scroll-btn shadow-teal-50" title="Scroll Up">
            <i class="fas fa-chevron-up"></i>
        </button>
        <button onclick="scrollPage('down')" class="scroll-btn shadow-teal-50" title="Scroll Down">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div> -->

    <!-- Indikator Suara -->
    <!-- <div id="voiceStatus">
        <div id="micIcon" class="text-gray-400 text-lg"><i class="fas fa-microphone"></i></div>
        <div class="leading-tight">
            <p id="voiceMsg" class="text-[9px] font-extrabold uppercase tracking-tighter text-teal-600">Voice Control</p>
            <p id="transcriptText" class="text-[11px] font-medium text-gray-500">Siap mendengarkan...</p>
        </div>
    </div> -->

    <!-- Desktop Sidebar -->

    <!-- Bottom Navbar (Mobile) -->

    <!-- Main Content Area -->
    <main class="   ">

        <!-- Header -->


        <div id="mainContainer">
            {{ $slot }}
        </div>
    </main>

    <!-- Modal Feedback / Generic Modal -->
    <div id="genericModal" class="fixed inset-0 bg-black bg-opacity-95 z-[9999] hidden items-center justify-center p-6">
        <div class="w-full max-w-4xl relative">
            <button onclick="closeModal()" class="absolute -top-16 right-0 text-white text-5xl hover:text-teal-400 transition-colors">
                <i class="fas fa-times-circle"></i>
            </button>
            <div id="modalContent" class="w-full">
                <!-- Content Injected -->
            </div>
        </div>
    </div>

    <!-- Alert Sistem -->
    <div id="toastAlert" class="fixed top-10 left-1/2 transform -translate-x-1/2 bg-white p-6 rounded-3xl shadow-2xl border border-teal-100 z-[9999] hidden items-center gap-5 min-w-[320px]">
        <div class="w-14 h-14 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-2xl"><i class="fas fa-check-circle"></i></div>
        <div>
            <h5 class="font-black text-gray-900" id="alertHead">Success!</h5>
            <p class="text-xs text-gray-400" id="alertBody">Data telah diperbarui.</p>
        </div>
    </div>

    <script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>
    @if(session()->has('success-alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire("Success!", "{{ session('success-alert') }}", "success");
        });
    </script>
    @endif

    @if(session()->has('error-alert'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire("Failed!", "{{ session('error-alert') }}", "error");
        });
    </script>
    @endif
    <script data-navigate-once>
        document.addEventListener('livewire:init', function() {

            Livewire.on('success', (message, isClose = true, type = 'success') => {
                toastr[type](message);

                if (isClose) {
                    $('.modal').modal('hide');
                }
            });

            Livewire.on('delete-success', (message) => {
                Swal.fire({
                    title: "Success!",
                    text: message,
                    icon: "success",
                    confirmButtonText: "OK",
                });
            });

            Livewire.on('error', (message) => {
                toastr.error(message);
            });

            Livewire.on('alert-error', (message) => {
                Swal.fire({
                    title: message,
                    icon: "error",
                    confirmButtonText: "OK",
                });
            });

            Livewire.on('alert-success', (message) => {
                Swal.fire(message, "success");
            });

        });
    </script>
    @stack('scripts')
    @livewireScripts

</body>

<!-- Mirrored from telerehab-prototype-new.gretiva.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Dec 2025 08:40:48 GMT -->

</html>
