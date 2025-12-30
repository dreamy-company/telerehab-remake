<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 via-white to-teal-50">
    <div class="w-full max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Side - Image (Hidden on mobile) -->
                <div class="hidden md:block relative bg-gradient-to-br from-teal-500 to-teal-700">
                    <div class="absolute inset-0 flex items-center justify-center p-12">
                        <div class="text-center text-white">
                            <img src="{{asset('assets/images/login-img.png')}}" alt="Telerehab" class="w-full h-auto rounded-2xl  mb-6">
                            
                        </div>
                    </div>
                </div>

                <!-- Right Side - Form -->
                <div class="p-8 md:p-12">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-black text-primary-500">Telerehab</h1>
                        <p class="text-sm text-gray-500 mt-2">Log in to your account</p>
                    </div>

                    <!-- Form -->
                    <form class="space-y-5" wire:submit.prevent="login">
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-teal-500 focus:outline-none transition-colors bg-gray-50" placeholder="name@example.com" wire:model="email" autocomplete="off">
                        </div>

                        <!-- Password Input -->
                        <div x-data="{ show: false }">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="flex items-center relative">
                                <input :type="show ? 'text' : 'password'" required class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-teal-500 focus:outline-none transition-colors bg-gray-50" placeholder="••••••••" wire:model="password" id="password" autocomplete="off">
                                <button type="button" class="absolute right-3 text-gray-500 hover:text-gray-700" @click="show = !show">
                                    <i x-show="!show" class="fas fa-eye w-5 h-5"></i>
                                    <i x-show="show" class="fas fa-eye-slash w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot -->
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300">
                                <span class="text-gray-600">Remember me</span>
                            </label>
                            <a href="#" class="text-primary hover:text-teal-700 font-semibold">Forgot password?</a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="w-full bg-[#17B8A6] text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all mt-6">
                            Login
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 my-6">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-xs text-gray-500">or</span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>

                    <!-- Sign Up Link -->
                    <p class="text-center text-gray-600 text-sm">
                        Don't have an account? <a href="{{ route('auth.register') }}" class="text-primary font-bold hover:text-teal-700">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>