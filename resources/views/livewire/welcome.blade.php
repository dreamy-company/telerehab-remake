<div>
    <!-- Theme Mode -->
    <script>
        const defaultThemeMode = 'light'; // light|dark|system
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

    @include('layouts.partials.landing.navbar')
    <main class="container mx-auto px-4">
        <section class="grid grid-cols-1 md:grid-cols-2 items-center justify-center min-h-screen gap-8">
            <div class="flex justify-center mt-8 md:mt-0 p-4 md:order-2" data-aos="fade-left">
                <img src="{{ asset('assets/images/hero-img.png') }}" alt="Landing Page Image"
                    class="w-full max-w-xs sm:max-w-md md:max-w-2xl rounded-lg shadow-lg">
            </div>
            <div class="flex flex-col gap-4 justify-center items-start px-10 md:pe-20 md:order-1" data-aos="fade-right">
                <h1 class="font-bold mb-4 text-3xl md:text-5xl leading-tight">Realizing the Dream of a Better Life with
                    Prosthetics</h1>
                    <i class="fas fa-slash-line text-black"></i>
                <p class="text-base md:text-lg mb-8">There is nothing stronger than a person's determination to improve
                    their life. We will accompany you at every step.</p>
                <a href="{{ route('auth.login') }}"
                    class="rounded-3xl px-6 py-2 border-black border-2 drop-shadow-none flex gap-2 items-center justify-center w-full md:w-auto bg-secondary-default font-bold">
                    Start Your New Journey
                    <i class="fas fa-arrow-right mr-2"></i>
                </a>
            </div>
        </section>
        <section class="flex flex-col gap-4">
            <h2 class="text-3xl md:text-5xl font-bold text-center mb-4" data-aos="fade-up">4 Easy Steps to Get Your
                Prosthetic Device
            </h2>
            <div
                class="border-primary-default border-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-16 py-12 rounded-lg">
                <div class="flex flex-col gap-2" data-aos="zoom-in" data-aos-delay="100">
                    <img src="{{ asset('assets/images/form.png') }}" alt="form" class="h-24 w-24">
                    <h3 class="text-xl font-bold">Register and Fill Out the Form</h3>
                    <p class="text-md">To get started, complete the registration form with your personal details,
                        email, and password. The form also asks about your prosthetic needs so we can understand your
                        condition from the start and prepare the rehabilitation process appropriately.</p>
                </div>
                <div class="flex flex-col gap-2" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('assets/images/login.png') }}" alt="login" class="h-24 w-24">
                    <h3 class="text-xl font-bold">Sign In to Your Account</h3>
                    <p class="text-md">
                        After registering, sign in to your account to access the dashboard. There you can check your
                        registration status, schedule consultations, and start interacting with our medical team.
                    </p>
                </div>
                <div class="flex flex-col gap-2" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('assets/images/schedule.png') }}" alt="schedule" class="h-24 w-24">
                    <h3 class="text-xl font-bold">Schedule an Appointment</h3>
                    <p class="text-md">Use the button on the dashboard to request a consultation with a specialist.
                        Our medical team will help you understand prosthetic options that suit your needs and
                        condition.</p>
                </div>
                <div class="flex flex-col gap-2" data-aos="zoom-in" data-aos-delay="400">
                    <img src="{{ asset('assets/images/consultation.png') }}" alt="consultation" class="h-24 w-24">
                    <h3 class="text-xl font-bold">Consultation</h3>
                    <p class="text-md">After the consultation, schedule follow-up appointments for the next steps.
                        Our team will arrange a convenient time to talk and plan how to obtain the right prosthetic
                        device for you.</p>
                </div>
            </div>
        </section>
    </main>
    <footer class="bg-white p-8">
        <div class="container mx-auto grid grid-cols-1 gap-8 py-12 md:grid-cols-2 lg:grid-cols-4">
            <!-- Logo Section -->
            <div class="flex flex-col gap-4 border-primary-default md:border-e-2 md:pe-8">
                <img src="{{ asset('assets/images/logo_telerehab.jpeg') }}" alt="telerehab-logo"
                    class="h-20 w-auto mx-auto md:mx-0">
            </div>

            <!-- Navigation Section -->
            <div class="flex flex-col gap-2 justify-between">
                <h3 class="text-md text-gray-800 text-center md:text-left">"We support every step of your journey
                    toward a more independent and comfortable life with your prosthetic."</h3>
                <p class="text-primary-default text-center md:text-left mt-4 md:mt-auto">&copy; 2025 Prosthetic
                    Rehabilitation. All rights reserved.</p>
            </div>

            <!-- Contact Section -->
            <div class="flex flex-col justify-between gap-2">
                <div class="flex flex-col gap-2">
                    <h3 class="font-bold text-lg text-primary-default text-center md:text-left">Contact Us</h3>
                    <p class="text-sm text-gray-700 text-center md:text-left">Email: <a
                            href="mailto:support@rehabilita.id"
                            class="hover:text-blue-500 transition">support@rehabilita.id</a></p>
                    <p class="text-sm text-gray-700 text-center md:text-left">Phone: <a href="tel:+621234567"
                            class="hover:text-blue-500 transition">(021) 123-4567</a></p>
                    <p class="text-sm text-gray-700 text-center md:text-left">WhatsApp: <a href="tel:+6281234567890"
                            class="hover:text-blue-500 transition">+62 812-3456-7890</a></p>
                </div>
                <p class="text-primary-default text-center md:text-left mt-4 md:mt-auto">Privacy Policy</p>
            </div>

            <!-- Social Media Section -->
            <div class="flex flex-col justify-between gap-2">
                <div class="flex flex-col gap-2">
                    <h3 class="font-bold text-lg text-gray-800 text-center md:text-left">Follow Us</h3>
                    <div class="flex justify-center md:justify-start gap-4">
                        <a href="#"
                            class="text-white hover:text-white bg-primary-default px-2 py-1 rounded-full"><i
                                class="fab fa-instagram-square"></i></a>
                        <a href="#"
                            class="text-white hover:text-white bg-primary-default px-2 py-1 rounded-full"><i
                                class="fab fa-facebook-square"></i></a>
                        <a href="#"
                            class="text-white hover:text-white bg-primary-default px-2 py-1 rounded-full"><i
                                class="fab fa-youtube"></i></a>
                        <a href="#"
                            class="text-white hover:text-white bg-primary-default px-2 py-1 rounded-full"><i
                                class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <p class="text-primary-default text-center md:text-left mt-4 md:mt-auto">Terms & Conditions</p>
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <script src={{ asset('assets/js/core.bundle.js') }}></script>
    <script src={{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}></script>
    <script src={{ asset('assets/js/widgets/general.js') }}></script>
    <script src={{ asset('assets/js/layouts/demo1.js') }}></script>
    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out',
        });
    </script>
</div>