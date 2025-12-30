<div class="w-full max-w-5xl bg-slate-50/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-slate-300/50 overflow-hidden border border-slate-200 grid md:grid-cols-2 relative transform transition-all hover:shadow-brand-500/5">
    
    <!-- Left Side: Image & Brand -->
    <div class="hidden md:flex relative bg-[#17B8A6] items-center justify-center p-12 overflow-hidden group">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl transition-transform duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-teal-900/10 rounded-full -ml-12 -mb-12 blur-xl transition-transform duration-700 group-hover:scale-110"></div>
        
        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative z-10 text-center text-white">
            <div class="mb-8 relative inline-block">
                <!-- Glow effect -->
                <div class="absolute inset-0 bg-white/20 blur-2xl rounded-full scale-90"></div>
                <!-- Menggunakan Asset Helper -->
                <img src="{{ asset('assets/images/login-img.png') }}" alt="Telerehab Illustration" class="w-64 mx-auto relative z-10 drop-shadow-2xl transform transition-transform duration-500 group-hover:-translate-y-2">
            </div>
            <h2 class="text-3xl font-bold mb-3 tracking-tight">Welcome Back!</h2>
            <p class="text-teal-50 opacity-90 leading-relaxed font-medium">
                "Recovery is a process. It takes time. <br>It takes patience. It takes everything you've got."
            </p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="p-8 md:p-12 lg:p-16 flex flex-col justify-center relative bg-slate-50">
        <!-- Mobile Brand Title -->
        <div class="md:hidden text-center mb-8">
            <h1 class="text-3xl font-black text-[#17B8A6]">Telerehab</h1>
        </div>

        <!-- Header Text -->
        <div class="text-center md:text-left mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-2">Login</h1>
            <p class="text-slate-500 font-medium">Please enter your details to continue.</p>
        </div>

        <!-- Form dengan wire:submit -->
        <form class="space-y-6" wire:submit.prevent="login">
            
            <!-- Email Input -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" required 
                        class="w-full pl-12 pr-4 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800" 
                        placeholder="hello@telerehab.id" 
                        wire:model="email" 
                        autocomplete="email">
                </div>
                @error('email') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password Input -->
            <div x-data="{ show: false }">
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#17B8A6] transition-colors">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input :type="show ? 'text' : 'password'" required 
                        class="w-full pl-12 pr-12 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:bg-white focus:border-[#17B8A6] focus:outline-none focus:ring-4 focus:ring-[#17B8A6]/10 transition-all duration-300 font-semibold placeholder-slate-400 text-slate-800" 
                        placeholder="••••••••" 
                        wire:model="password" 
                        id="password">
                    <button type="button" class="absolute right-0 inset-y-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" @click="show = !show">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between text-sm mt-2">
                <label class="flex items-center gap-3 cursor-pointer group select-none">
                    <div class="relative flex items-center">
                        <input type="checkbox" wire:model="remember" class="peer sr-only">
                        <div class="w-5 h-5 border-2 border-slate-300 rounded-md peer-checked:bg-[#17B8A6] peer-checked:border-[#17B8A6] bg-slate-100 transition-all"></div>
                        <i class="fas fa-check text-[10px] text-white absolute left-1 top-1 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                    </div>
                    <span class="text-slate-500 font-bold group-hover:text-slate-700 transition-colors">Remember me</span>
                </label>
                <a href="#" class="text-[#17B8A6] font-bold hover:text-[#0d9488] transition-all">Forgot Password?</a>
            </div>

            <!-- Submit Button (Loading State) -->
            <button type="submit" class="w-full bg-[#17B8A6] hover:bg-[#13978b] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#17B8A6]/20 hover:shadow-[#17B8A6]/40 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 group mt-4 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                <span wire:loading.remove>Sign In</span>
                <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Processing...</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform" wire:loading.remove></i>
            </button>
        </form>

        <!-- Separator -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="px-4 bg-slate-50 text-slate-400 font-bold tracking-wider">Or continue with</span>
            </div>
        </div>

        <!-- Register CTA -->
        <div class="text-center">
            <p class="text-slate-500 text-sm font-medium mb-4">Don't have an account yet?</p>
            <a href="{{ route('auth.register') }}" class="inline-flex items-center justify-center w-full px-8 py-3 rounded-2xl border-2 border-slate-200 text-slate-600 font-bold hover:border-[#17B8A6] hover:text-[#17B8A6] hover:bg-[#17B8A6]/5 transition-all duration-300">
                Create New Account
            </a>
        </div>
    </div>
</div>