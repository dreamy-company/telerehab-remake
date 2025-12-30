<div class="shadow-md p-12 text-center bg-white">
    <div>
        <form class="space-y-5" wire:submit.prevent="save">
            <!-- Name Input -->
            <div class="flex flex-col items-start"> 
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" required class="px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:outline-none transition-colors bg-gray-50" placeholder="Enter rehab type name" wire:model="name" autocomplete="off">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2 pt-4 justify-end w-full">
                <button type="button" @click="showForm = false" class="btn bg-gray-500 text-white font-bold py-3 rounded-xl hover:bg-gray-600 transition-all">
                    Cancel
                </button>
                <button type="submit" class="btn bg-primary-500 text-white font-bold py-3 rounded-xl hover:bg-primary-600 transition-all">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>