<div class="shadow-md p-12 text-center bg-white">
    <div>
        <form class="space-y-5" wire:submit.prevent="save" enctype="multipart/form-data">
            <!-- Row 1: Name and Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Name Input -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter full name">
                </div>

                <!-- Email -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter email">
                </div>
            </div>

            <!-- Row 2: Phone Number and Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Phone Number -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" wire:model="telephone" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter phone number">
                </div>

                <!-- Address -->
                <div class="flex flex-col items-start">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Address <span class="text-red-500">*</span></label>
                    <textarea wire:model="address" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter address" rows="3"></textarea>
                </div>
            </div>

            <!-- Password -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Password 
                    @if(!$patientId) <span class="text-red-500">*</span> @endif (6 characters minimum)</label>
                <input type="password" wire:model="password" @if(!$patientId) required  minlength="6" @endif class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter password">
            </div>

            <!-- Medical Record Number -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Medical Record Number</label>
                <input type="text" wire:model="medical_record_number" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter medical record number">
            </div>

            <!-- Prosthetic Used -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Prosthetic Used</label>
                <input type="text" wire:model="prosthetic" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter prosthetic description">
            </div>

            <!-- Prosthetic Since -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Prosthetic Since (Date)</label>
                <input type="date" wire:model="prosthetic_since" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
            </div>

            <!-- Upload Patient Condition -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Upload Patient Condition</label>
                <input type="file" wire:model="patient_condition" multiple accept="image/*" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
                <p class="text-xs text-gray-500 mt-1">*Maximum filesize 2MB</p>

              
                @if($old_patient_condition && $patientId)
                <p class="text-xs text-gray-600">Current file:</p>
                <div class="mt-2 flex gap-2">
                    @foreach ($old_patient_condition as $photo)
                        <img src="{{ Storage::url($photo['url']) }}" alt="Patient Condition" class="w-32 h-32 object-cover rounded-lg">
                    @endforeach
                </div>
                @elseif($patient_condition && !is_string($patient_condition))
                <p class="text-xs text-green-500 mt-1">New file selected</p>
                @endif
            </div>

            <!-- BPJS Number -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">BPJS Number</label>
                <input type="text" wire:model="bpjs_number" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50" placeholder="Enter BPJS number">
            </div>

            <!-- Upload BPJS Card -->
            <div class="flex flex-col items-start">
                <label class="block text-sm font-semibold text-gray-700 mb-2 justify-start">Upload BPJS Card</label>
                <input type="file" wire:model="bpjs_card" accept="image/*" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#17B8A6] focus:outline-none transition-colors bg-gray-50">
                <p class="text-xs text-gray-500 mt-1">*Maximum filesize 2MB</p>
                @if($old_bpjs_card && $patientId)
                <div class="mt-2">
                    <p class="text-xs text-gray-600">Current file:</p>
                    <img src="{{ Storage::url($old_bpjs_card) }}" alt="BPJS Card" class="w-32 h-32 object-cover rounded-lg">
                </div>
                @endif
            </div>

            <div class="flex gap-2 pt-4 justify-end w-full">
                <a @if(Auth::user()->role === 'admin') href="{{ route('admin.patient') }}" @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.patient') }}" @endif class="btn bg-gray-500 text-white font-bold py-3 rounded-xl hover:bg-gray-600 transition-all">
                    Cancel
                </a>
                <button type="submit" class="btn bg-primary-500 text-white font-bold py-3 rounded-xl hover:bg-primary-600 transition-all">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>