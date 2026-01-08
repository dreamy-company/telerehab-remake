<div class="min-h-[70vh] flex items-center justify-center p-6">
    <div class="w-full max-w-5xl bg-slate-50/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-300/50 overflow-hidden border border-slate-200 grid md:grid-cols-2 relative transform transition-all hover:shadow-slate-500/5"
        x-data="{ loading: false }"
        @email-sent.window="loading = false">

        <!-- Left Side: Illustration -->
        <div class="hidden md:flex relative bg-[#17B8A6] items-center justify-center p-12 overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-teal-900/10 rounded-full -ml-12 -mb-12 blur-xl transition-transform duration-700 group-hover:scale-110"></div>

            <div class="relative z-10 text-center text-white">
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 blur-2xl rounded-full"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-white relative z-10 drop-shadow-2xl" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold mb-3 tracking-tight">Email Verified</h2>
                <p class="text-teal-50 opacity-90 leading-relaxed font-medium">
                    Your email has been successfully verified. You can now login to your account.
                </p>
            </div>
        </div>

        <!-- Right Side: Content -->
        <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center relative bg-slate-50">
            <!-- Mobile Header -->
            <div class="md:hidden text-center mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#17B8A6] mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-3">Email Verified!</h1>
                <p class="text-slate-500 font-medium">
                    Your email <span class="font-bold text-slate-800">{{ $userData->email }}</span> has been successfully verified.
                </p>
            </div>

            <div class="space-y-4 mb-8">
                <a href="{{ route('auth.login') }}"
                    class="w-full bg-[#17B8A6] hover:bg-[#13978b] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#17B8A6]/20 hover:shadow-[#17B8A6]/40 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group">
                    <span>Go to Login</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="text-center">
                <p class="text-slate-500 text-sm font-medium">Account activated successfully</p>
            </div>
        </div>
    </div>
</div>
