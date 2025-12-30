<nav class="sticky top-0 left-0 right-0 bg-white shadow-md h-20 z-50 w-full">
    <div class="container mx-auto px-4 flex justify-between items-center h-full">
        <!-- Logo -->
        <a href="/">
            <img src="{{ asset('assets/images/logo_telerehab.jpeg') }}" alt="Telerehab logo" class="h-12">
        </a>
        <!-- Desktop Menu -->
        {{-- <ul class="hidden md:flex gap-8">
            <li><a href="#tentang" class="hover:text-secondary-default transition">About</a></li>
            <li><a href="#sorotan" class="hover:text-secondary-default transition">Highlights</a></li>
            <li><a href="#dokter" class="hover:text-secondary-default transition">Doctors</a></li>
            <li><a href="#terapis" class="hover:text-secondary-default transition">Therapists</a></li>
        </ul> --}}
        <!-- Auth Buttons (Desktop) -->
        <div class="hidden md:flex gap-4">
            <a href="{{ route('auth.login') }}"
                class="rounded-3xl px-6 py-2 border-black border-2 flex gap-2 items-center justify-center hover:bg-gray-100 transition">
                <i class="fas fa-arrow-right mr-2"></i>
                Sign In
            </a>
            <a href="{{ route('auth.register') }}"
                class="rounded-3xl px-6 py-2 bg-black text-white border-white border-2 flex gap-2 items-center justify-center hover:bg-gray-900 transition">
                <i class="fas fa-plus-circle"></i>
                Create Account
            </a>
        </div>
        <!-- Mobile Menu Button -->
        <button id="navbar-toggle" class="md:hidden flex items-center px-2 py-1 border rounded focus:outline-none"
            aria-label="Toggle navigation">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <!-- Mobile Modal Menu -->
    <div id="navbar-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-start justify-end z-50 hidden">
        <div id="navbar-modal-content" class="bg-white w-80 h-full shadow-lg animate-slideInRight relative">
            <button id="navbar-close"
                class="absolute top-4 right-4 text-2xl text-gray-600 hover:text-black focus:outline-none"
                aria-label="Close menu">
                &times;
            </button>
            <ul class="flex flex-col gap-4 px-8 py-16">
                <li><a href="#tentang" class="block py-2 hover:text-secondary-default transition">About</a></li>
                <li><a href="#sorotan" class="block py-2 hover:text-secondary-default transition">Highlights</a></li>
                <li><a href="#dokter" class="block py-2 hover:text-secondary-default transition">Doctors</a></li>
                <li><a href="#terapis" class="block py-2 hover:text-secondary-default transition">Therapists</a></li>
                <li>
                    <a href="{{ route('auth.login') }}"
                        class="rounded-3xl px-6 py-2 border-black border-2 flex gap-2 items-center justify-center my-2 hover:bg-gray-100 transition">

                        <i class="fas fa-arrow-right mr-2"></i>
                        Sign In
                    </a>
                </li>
                <li>
                    <a href="{{ route('auth.register') }}"
                        class="rounded-3xl px-6 py-2 bg-black text-white border-white border-2 flex gap-2 items-center justify-center hover:bg-gray-900 transition">
                    <i class="fas fa-plus-circle"></i>
                        Create Account
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(100%);
            }
        }

        .animate-slideInRight {
            animation: slideInRight 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-slideOutRight {
            animation: slideOutRight 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('navbar-toggle');
            const modal = document.getElementById('navbar-modal');
            const close = document.getElementById('navbar-close');
            const modalContent = document.getElementById('navbar-modal-content');

            // Open modal
            toggle.addEventListener('click', function() {
                modal.classList.remove('hidden');
                modalContent.classList.remove('animate-slideOutRight');
                modalContent.classList.add('animate-slideInRight');
            });

            // Function to close modal with animation
            function closeModalWithAnimation() {
                modalContent.classList.remove('animate-slideInRight');
                modalContent.classList.add('animate-slideOutRight');
                modalContent.addEventListener('animationend', function handler() {
                    modal.classList.add('hidden');
                    modalContent.classList.remove('animate-slideOutRight');
                    modalContent.removeEventListener('animationend', handler);
                });
            }

            // Close modal on close button
            close.addEventListener('click', function() {
                closeModalWithAnimation();
            });

            // Close modal on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalWithAnimation();
                }
            });
        });
    </script>
</nav>