<div class="shadow-md p-12 text-center bg-white">
    <div>
        <form class="space-y-5" wire:submit.prevent="save">
            <!-- Name and Email Row -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Name Input -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" placeholder="Enter name" wire:model="name" autocomplete="off">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email Input -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" placeholder="Enter email" wire:model="email" autocomplete="off">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Telephone Input -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Telephone</label>
                <input type="tel" class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" placeholder="Enter telephone" wire:model="telephone" autocomplete="off">
                @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Role -->
            <div class="flex flex-col items-start" wire:ignore>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                <select class="select2 w-full" wire:model="role" onchange="@this.set('role',this.value)">
                    <option disabled selected>Select Role</option>
                    <option value="admin" @if ($role == 'admin') selected @endif>Admin</option>
                    <option value="doctor" @if ($role == 'doctor') selected @endif>Doctor</option>
                    <option value="therapist" @if ($role == 'therapist') selected @endif>Therapist</option>
                </select>
            </div>

            <!-- Password Input -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50 w-full" placeholder="Enter password" wire:model="password" autocomplete="off">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2 pt-4 justify-end w-full">
                <a href="{{ route('admin.user') }}" class="btn bg-gray-500 text-white font-bold py-3 rounded-xl hover:bg-gray-600 transition-all">
                    Cancel
                </a>
                <button type="submit" class="btn bg-primary-500 text-white font-bold py-3 rounded-xl hover:bg-primary-600 transition-all">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>