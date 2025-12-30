<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - Telerehab' }}</title>
    
    <!-- Tailwind CSS (CDN for prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#17B8A6', // Primary Teal
                            600: '#0d9488', // Darker Teal
                        },
                        'off-white': '#F8FAFC', 
                    }
                }
            }
        }
    </script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireStyles
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white flex items-center justify-center min-h-screen p-4 relative overflow-hidden">

    <!-- Background Decoration (Soft Blobs) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-3xl mix-blend-multiply opacity-60 animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-blue-400/10 rounded-full blur-3xl mix-blend-multiply opacity-60 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Main Content -->
    {{ $slot }}

    <!-- Animations -->
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

    @livewireScripts

    <!-- Alert Scripts Handling -->
    <script>
        document.addEventListener('livewire:init', function() {
            // Error Alert Listener
            Livewire.on('alert-error', (message) => {
                Swal.fire({
                    title: "Gagal!",
                    text: message,
                    icon: "error",
                    confirmButtonColor: '#17B8A6',
                    confirmButtonText: "OK",
                });
            });

            // Success Alert Listener
            Livewire.on('alert-success', (message) => {
                Swal.fire({
                    title: "Berhasil!",
                    text: message,
                    icon: "success",
                    confirmButtonColor: '#17B8A6',
                    confirmButtonText: "OK",
                });
            });
        });
    </script>
</body>
</html>