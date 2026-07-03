<div>

    <!-- Main Background diganti ke bg-slate-100 agar ada kontras dengan kartu -->
    <main class="overflow-x-hidden relative bg-slate-100 min-h-screen font-sans">

        <!-- Background Decor (Animated Blobs) -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <!-- Blob 1 (Teal) -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#17B8A6] rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob"></div>
            <!-- Blob 2 (Cyan) -->
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob animation-delay-2000"></div>
            <!-- Blob 3 (Emerald) -->
            <div class="absolute bottom-0 left-20 w-[500px] h-[500px] bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob animation-delay-4000"></div>
        </div>

        <!-- =============================================== -->
        <!--                  HERO SECTION                   -->
        <!-- =============================================== -->
        <section class="min-h-screen flex items-center pt-32 pb-20 relative">
            <div class="container mx-auto px-6 md:px-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <!-- Text Content -->
            <div class="hero-content flex flex-col gap-8 order-2 lg:order-1 z-10 text-center lg:text-left">
                <!-- Badge -->
                <div class="flex justify-center lg:justify-start hero-badge">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 border border-[#17B8A6]/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#17B8A6] animate-pulse"></span>
                    <span class="text-xs font-bold text-[#17B8A6] tracking-widest uppercase">AI-Powered Telerehabilitation</span>
                </div>
                </div>

                <!-- Headline -->
                <h1 class="font-extrabold text-4xl md:text-5xl lg:text-6xl leading-tight text-slate-800 hero-title text-center">
                Realizing the Dream of a
                <span class="text-[#17B8A6] bg-clip-text bg-gradient-to-r from-[#17B8A6] to-teal-500">Better Life</span> with Prosthetics
                </h1>

                <!-- Description -->
                <p class="text-lg text-slate-600 leading-relaxed max-w-lg mx-auto lg:mx-0 hero-desc text-center">
                Determination fuels recovery. We combine advanced medical technology with heartfelt care to accompany you at every step of your new journey.
                </p>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start hero-cta">
                <a href="{{ route('auth.login') }}"
                   class="group relative overflow-hidden rounded-full px-8 py-4 bg-[#17B8A6] hover:bg-[#13978b] text-white font-bold shadow-lg shadow-[#17B8A6]/20 transition-all duration-300 hover:scale-105">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                    Start Your Journey
                    <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </span>
                </a>
                <a href="#steps" class="px-8 py-4 rounded-full bg-slate-50 border border-slate-300 font-semibold text-slate-600 shadow-sm hover:border-[#17B8A6] hover:text-[#17B8A6] transition-all flex items-center justify-center">
                    How it Works
                </a>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="relative order-1 lg:order-2 flex justify-center hero-image-container opacity-0 translate-x-8">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#17B8A6]/10 to-slate-50 rounded-full scale-90 blur-3xl opacity-50 -z-10"></div>
                <img src="{{ asset('assets/images/hero-img.png') }}"
                 alt="Prosthetic Landing"
                 class="w-full max-w-xs sm:max-w-md lg:max-w-lg object-contain drop-shadow-xl floating-image rounded-3xl relative z-10">
            </div>
            </div>
        </section>

        <!-- =============================================== -->
        <!--              KEY FEATURES SECTION               -->
        <!-- =============================================== -->
        <section class="py-24 relative">
            <div class="container mx-auto px-6 md:px-12">
                <!-- Header -->
                <div class="text-center max-w-3xl mx-auto mb-16 feature-header opacity-0">
                    <span class="text-[#17B8A6] font-bold tracking-wider text-sm uppercase mb-2 block">Platform Capabilities</span>
                    <h2 class="text-3xl md:text-5xl font-bold text-slate-800 mb-6">Built for Every Step</h2>
                    <p class="text-slate-500 text-lg">
                        Everything you need — from your first consultation to mastering your prosthetic — in one platform.
                    </p>
                </div>

                <!-- Feature Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Feature 1: Pose Tracking -->
                    <div class="feature-card group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-[#17B8A6]/30">
                        <div class="w-14 h-14 bg-[#17B8A6]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#17B8A6] transition-colors duration-300">
                            <i class="fas fa-camera text-xl text-[#17B8A6] group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Real-Time Pose Tracking</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            AI joint-angle detection via Google MediaPipe — counts reps and checks form directly in your browser, no extra app needed.
                        </p>
                    </div>

                    <!-- Feature 2: Progress Analytics -->
                    <div class="feature-card group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-cyan-400/30">
                        <div class="w-14 h-14 bg-cyan-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-cyan-500 transition-colors duration-300">
                            <i class="fas fa-chart-line text-xl text-cyan-500 group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Progress Analytics</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Every session logs joint angles, rep counts, and completion rates so your care team sees your real data, not just self-reports.
                        </p>
                    </div>

                    <!-- Feature 3: Video Reviews -->
                    <div class="feature-card group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-sky-400/30">
                        <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-sky-500 transition-colors duration-300">
                            <i class="fas fa-video text-xl text-sky-500 group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Exercise Video Reviews</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Record your sessions, upload them, and receive personalised text and video feedback from your doctor or therapist.
                        </p>
                    </div>

                    <!-- Feature 4: Online Consultation -->
                    <div class="feature-card group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-emerald-400/30">
                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 transition-colors duration-300">
                            <i class="fas fa-calendar-check text-xl text-emerald-500 group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Online Consultation</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Request a consultation in one tap. A doctor accepts, sets the schedule, delivers a diagnosis, and assigns your rehab plan — all in the platform.
                        </p>
                    </div>

                    <!-- Feature 5: Mobile App -->
                    <div class="feature-card group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-violet-400/30">
                        <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-violet-500 transition-colors duration-300">
                            <i class="fas fa-mobile-alt text-xl text-violet-500 group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Mobile App Ready</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            A full REST API powers a companion mobile app so patients can view routines, request consultations, and track progress from anywhere.
                        </p>
                    </div>

                    <!-- Feature 6: Clinical Flags -->
                    <div class="feature-card group bg-gradient-to-br from-[#17B8A6]/5 to-slate-50 p-8 rounded-3xl border border-[#17B8A6]/30 shadow-lg shadow-[#17B8A6]/10 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 ring-1 ring-[#17B8A6]/20">
                        <div class="w-14 h-14 bg-[#17B8A6]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#17B8A6] transition-colors duration-300">
                            <i class="fas fa-flag text-xl text-[#17B8A6] group-hover:text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Clinical Flags</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Doctors and therapists can flag sessions for wrong form, incomplete range, or missed reps — keeping every deviation on record for safer care.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- =============================================== -->
        <!--                5 STEPS SECTION                  -->
        <!-- =============================================== -->
        <section id="steps" class="py-24 relative bg-slate-100/50 backdrop-blur-sm">
            <div class="container mx-auto px-6 md:px-12">
                <!-- Header -->
                <div class="text-center max-w-3xl mx-auto mb-16 step-header opacity-0">
                    <span class="text-[#17B8A6] font-bold tracking-wider text-sm uppercase mb-2 block">Our Process</span>
                    <h2 class="text-3xl md:text-5xl font-bold text-slate-800 mb-6">Your Path to Independence</h2>
                    <p class="text-slate-500 text-lg">
                        We have simplified the rehabilitation process into 5 clear steps.
                    </p>
                </div>

                <!-- Steps Layout -->
                <div class="flex flex-wrap justify-center gap-6 relative z-10">

                    <!-- Step 1 -->
                    <!-- bg-white diganti bg-slate-50 -->
                    <div class="step-card w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.333%-1.5rem)] group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-[#17B8A6]/30">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-[#17B8A6]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#17B8A6] transition-colors duration-300">
                                <i class="fas fa-file-signature text-xl text-[#17B8A6] group-hover:text-white"></i>
                            </div>
                            <span class="text-4xl font-black text-slate-200 select-none group-hover:text-[#17B8A6]/10 transition-colors">01</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Register & Profile</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Create your account. Tell us about your condition so we can prepare a tailored rehabilitation plan.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-card w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.333%-1.5rem)] group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-cyan-400/30">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-cyan-50 rounded-2xl flex items-center justify-center group-hover:bg-cyan-500 transition-colors duration-300">
                                <i class="fas fa-user-check text-xl text-cyan-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-4xl font-black text-slate-200 select-none group-hover:text-cyan-500/10 transition-colors">02</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Access Dashboard</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Sign in to your dashboard. Check your registration status and prepare for upcoming steps.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-card w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.333%-1.5rem)] group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-sky-400/30">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center group-hover:bg-sky-500 transition-colors duration-300">
                                <i class="fas fa-calendar-check text-xl text-sky-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-4xl font-black text-slate-200 select-none group-hover:text-sky-500/10 transition-colors">03</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Book Appointment</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Select a schedule. Our specialist team is ready to analyze your prosthetic needs deeply.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-card w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.333%-1.5rem)] group bg-slate-50 p-8 rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 hover:border-emerald-400/30">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 transition-colors duration-300">
                                <i class="fas fa-stethoscope text-xl text-emerald-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-4xl font-black text-slate-200 select-none group-hover:text-emerald-500/10 transition-colors">04</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Expert Consultation</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Meet our experts. We will measure, discuss, and plan the production of the perfect device.
                        </p>
                    </div>

                    <!-- Step 5 (Gradient Teal/Brand Highlight) -->
                    <!-- bg-white di gradient diganti bg-slate-50 -->
                    <div class="step-card w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.333%-1.5rem)] group bg-gradient-to-br from-[#17B8A6]/5 to-slate-50 p-8 rounded-3xl border border-[#17B8A6]/30 shadow-lg shadow-[#17B8A6]/10 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 ring-1 ring-[#17B8A6]/20">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-[#17B8A6]/10 rounded-2xl flex items-center justify-center group-hover:bg-[#17B8A6] transition-colors duration-300 shadow-sm">
                                <i class="fas fa-walking text-xl text-[#17B8A6] group-hover:text-white"></i>
                            </div>
                            <span class="text-4xl font-black text-[#17B8A6]/20 select-none">05</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Routine Rehabilitation</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            The journey continues. Engage in guided therapy sessions, track your joint angles live with AI pose detection, and upload exercise videos for expert review.
                        </p>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- =============================================== -->
    <!--                  FOOTER SECTION                 -->
    <!-- =============================================== -->
    <footer class="bg-slate-900 text-white pt-16 pb-8 rounded-t-[3rem] mt-10 relative overflow-hidden border-t-4 border-[#17B8A6]">
        <!-- Pattern Overlay -->
        <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
             <i class="fas fa-notes-medical text-9xl transform rotate-12"></i>
        </div>

        <div class="container mx-auto px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
            <!-- Brand -->
            <div class="flex flex-col gap-6">
                <!-- bg-white diganti bg-slate-50 -->
                <div class="bg-slate-50 p-3 w-fit rounded-lg">
                    <!-- Pastikan path logo benar -->
                    <img src="{{ asset('assets/images/logo_telerehab.jpeg') }}" alt="Logo" class="h-10 w-auto">
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Empowering lives through technology. We support every step of your journey toward a more independent life.
                </p>
                <!-- Socials -->
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center hover:bg-[#17B8A6] hover:text-white transition-all text-slate-300"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center hover:bg-[#17B8A6] hover:text-white transition-all text-slate-300"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center hover:bg-[#17B8A6] hover:text-white transition-all text-slate-300"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Links -->
            <div class="flex flex-col gap-4">
                <h3 class="font-bold text-lg text-white mb-2 pl-3 border-l-4 border-[#17B8A6]">Quick Links</h3>
                <a href="#" class="text-slate-400 hover:text-white hover:translate-x-1 transition-all">About Us</a>
                <a href="#" class="text-slate-400 hover:text-white hover:translate-x-1 transition-all">Services</a>
                <a href="#" class="text-slate-400 hover:text-white hover:translate-x-1 transition-all">Doctors</a>
            </div>

            <!-- Contact -->
            <div class="flex flex-col gap-4">
                <h3 class="font-bold text-lg text-white mb-2 pl-3 border-l-4 border-[#17B8A6]">Contact</h3>
                <a href="mailto:support@rehabilita.id" class="text-slate-400 hover:text-white transition flex items-center gap-3">
                    <i class="fas fa-envelope text-[#17B8A6]"></i> support@rehabilita.id
                </a>
                <a href="tel:+621234567" class="text-slate-400 hover:text-white transition flex items-center gap-3">
                    <i class="fas fa-phone text-[#17B8A6]"></i> (021) 123-4567
                </a>
            </div>

            <!-- Legal -->
            <div class="flex flex-col gap-4">
                <h3 class="font-bold text-lg text-white mb-2 pl-3 border-l-4 border-[#17B8A6]">Legal</h3>
                <a href="#" class="text-slate-400 hover:text-white transition">Privacy Policy</a>
                <a href="#" class="text-slate-400 hover:text-white transition">Terms & Conditions</a>
            </div>
        </div>

        <div class="border-t border-slate-800 mt-12 pt-8 text-center">
            <p class="text-slate-500 text-sm">&copy; 2025 Prosthetic Rehabilitation.</p>
        </div>
    </footer>

    <!-- =============================================== -->
    <!--           SCRIPTS (ANIMATION FIX)               -->
    <!-- =============================================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        // Simple Mobile Menu Handler
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.querySelector('[data-kt-menu-trigger="click"]') || document.querySelector('.menu-toggle');
            const menuContent = document.querySelector('#kt_header_menu') || document.querySelector('.menu-content');

            if (menuToggle && menuContent) {
                menuToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    menuContent.classList.toggle('hidden');
                    menuContent.classList.toggle('block');
                });
            }
        });

        // GSAP Animations (FIXED)
        document.addEventListener("DOMContentLoaded", (event) => {
            // Register Plugin Wajib
            gsap.registerPlugin(ScrollTrigger);

            // 1. Hero Animations - Menggunakan FROM agar element tidak hilang (default visible)
            // Hapus class opacity-0 dari HTML jika ingin fallback yang aman,
            // tapi di sini kita handle opacity via JS.

            const tlHero = gsap.timeline({ defaults: { ease: "power3.out" } });

            // Set initial state untuk hero elements
            gsap.set(".hero-badge, .hero-title, .hero-desc, .hero-cta, .hero-image-container", { autoAlpha: 0, y: 30 });

            tlHero.to(".hero-badge", { autoAlpha: 1, y: 0, duration: 0.8 })
                  .to(".hero-title", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.6")
                  .to(".hero-desc", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.6")
                  .to(".hero-cta", { autoAlpha: 1, y: 0, duration: 0.8 }, "-=0.6")
                  .to(".hero-image-container", { autoAlpha: 1, x: 0, duration: 1 }, "-=0.8");

            // 2. Floating Image (Continuous)
            gsap.to(".floating-image", {
                y: -20,
                duration: 3,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            // 3. Feature Section Header (ScrollTrigger)
            gsap.fromTo(".feature-header",
                { autoAlpha: 0, y: 50 },
                {
                    scrollTrigger: {
                        trigger: ".feature-header",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    autoAlpha: 1,
                    y: 0,
                    duration: 1,
                    ease: "power2.out"
                }
            );

            // 4. Staggered Feature Cards (ScrollTrigger)
            gsap.fromTo(".feature-card",
                { autoAlpha: 0, y: 60 },
                {
                    scrollTrigger: {
                        trigger: ".feature-card",
                        start: "top 80%",
                    },
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    ease: "back.out(1.5)"
                }
            );

            // 6. Step Section Header (ScrollTrigger)
            gsap.fromTo(".step-header",
                { autoAlpha: 0, y: 50 },
                {
                    scrollTrigger: {
                        trigger: ".step-header",
                        start: "top 85%", // Trigger sedikit lebih awal
                        toggleActions: "play none none reverse"
                    },
                    autoAlpha: 1,
                    y: 0,
                    duration: 1,
                    ease: "power2.out"
                }
            );

            // 7. Staggered Steps (ScrollTrigger)
            gsap.fromTo(".step-card",
                { autoAlpha: 0, y: 60 },
                {
                    scrollTrigger: {
                        trigger: "#steps",
                        start: "top 75%",
                    },
                    autoAlpha: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    ease: "back.out(1.5)"
                }
            );
        });
    </script>

    <style>
        /* Custom Animations untuk Background Blobs */
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
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>
