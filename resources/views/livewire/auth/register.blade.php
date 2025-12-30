<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl my-5">
        <div class="p-8 md:p-12 overflow-y-auto  w-full"> <!-- Increased width here -->
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-gray-900">Telerehab</h1>
                <p class="text-sm text-gray-500 mt-2">Create your account</p>
            </div>

            <!-- Form -->
            <form class="grid grid-cols-1 md:grid-cols-2 gap-4" wire:submit.prevent="store">
                <!-- Full Name -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter full name">
                </div>

                <!-- Email -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter email">
                </div>

                <!-- Phone Number -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" wire:model="telephone" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter phone number">
                </div>

                <!-- Address -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                    <textarea wire:model="address" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter address" rows="3"></textarea>
                </div>

                <!-- Password -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span> (6 characters minimum)</label>
                    <input type="password" wire:model="password" required minlength="6" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter password">
                </div>

                <!-- Medical Record Number -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Medical Record Number</label>
                    <input type="text" wire:model="medical_record_number" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter medical record number">
                </div>

                <!-- Prosthetic Used -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prosthetic Used</label>
                    <input type="text" wire:model="prosthetic" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter prosthetic description">
                </div>

                <!-- Prosthetic Since -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prosthetic Since (Date)</label>
                    <input type="date" wire:model="prosthetic_since" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
                </div>

                <!-- Upload Patient Condition -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Patient Condition</label>
                    <input type="file" wire:model="patient_condition" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">*Maximum filesize 2MB</p>
                </div>

                <!-- BPJS Number -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">BPJS Number</label>
                    <input type="text" wire:model="bpjs_number" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter BPJS number">
                </div>

                <!-- Upload BPJS Card -->
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload BPJS Card</label>
                    <input type="file" wire:model="bpjs_card" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
                    <p class="text-xs text-gray-500 mt-1">*Maximum filesize 2MB</p>
                </div>

                <!-- Register Button -->
                <div class="col-span-2">
                    <button type="submit" class="w-full bg-[#17B8A6] text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all mt-6">
                        Register
                    </button>
                </div>
            </form>

            <!-- Sign In Link -->
            <p class="text-center text-gray-600 text-sm mt-6">
                Already have an account? <a href="{{ route('auth.login') }}" class="text-[#17B8A6] font-bold hover:text-[#3A6FD8]">Sign in here</a>
            </p>
        </div>

    </div>
</div>