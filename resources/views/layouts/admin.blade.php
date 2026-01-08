<!DOCTYPE html>
<html lang="id" data-theme="light">

<!-- Mirrored from telerehab-prototype-new.gretiva.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Dec 2025 08:40:47 GMT -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telerehab - Solusi Pemulihan Tangan</title>

    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/erimicel/select2-tailwindcss-theme/dist/select2-tailwindcss-theme-plain.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastify.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body>
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
    @include('layouts.partials.sidebar')

    <!-- Bottom Navbar (Mobile) -->
    @include('layouts.partials.mobilenav')
    <!-- Main Content Area -->
    <main class="bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-100 lg:ml-72 min-h-screen py-6 mb-20 md:mb-0">

        <!-- Header -->
        @include('layouts.partials.navbar')

        <div id="mainContainer" class="w-full px-8 mt-6">
            {{ $slot }}
        </div>
    </main>

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
                Swal.fire({
                    title: message,
                    icon: "success",
                });
            });

            Livewire.on('alert-delete', (message) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirmed');
                    }
                });
            });

        });
    </script>
    @stack('scripts')
    @livewireScripts
</body>

</html>