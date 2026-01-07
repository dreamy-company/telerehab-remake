<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <div
        class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center bg-slate-50/50 gap-4">

        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 shadow-sm">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 leading-tight">User Management</h2>
                <p class="text-xs text-slate-500">Total {{ count($data) }} registered users</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="search" wire:model.live.debounce="search"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                    placeholder="Search name or email...">
            </div>

            <a @if(Auth::user()->role === 'admin') href="{{ route('admin.user.create') }}"
            @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.user.create') }}" @endif
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none shadow-md shadow-primary-500/20 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Add User
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User
                        Profile</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email
                        Address</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                    <th scope="col"
                        class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($data as $index => $user)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-medium w-16">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @php
                                    $bgAvatar = match ($user->role) {
                                        'admin' => 'bg-purple-100 text-purple-600 border-purple-200',
                                        'doctor' => 'bg-blue-100 text-blue-600 border-blue-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <div
                                    class="h-10 w-10 rounded-full {{ $bgAvatar }} flex items-center justify-center font-bold text-sm mr-3 border shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900">{{ $user->name }}</span>
                                    <span class="text-xs text-slate-400">Created:
                                        {{ $user->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-slate-600">
                                <i class="far fa-envelope mr-2 text-slate-400"></i>
                                {{ $user->email }}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    <i class="fas fa-shield-alt mr-1.5"></i> Admin
                                </span>
                            @elseif($user->role === 'doctor')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    <i class="fas fa-user-md mr-1.5"></i> Doctor
                                </span>
                            @elseif($user->role === 'therapist')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-teal-50 text-teal-700 border border-teal-100">
                                    <i class="fas fa-hands-helping mr-1.5"></i> Therapist
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fas fa-user mr-1.5"></i> Patient
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a @if(Auth::user()->role === 'admin') href="{{ route('admin.user.edit', $user->id) }}"
                                    @elseif(Auth::user()->role === 'doctor') href="{{ route('doctor.user.edit', $user->id) }}" @endif
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-amber-500 hover:bg-amber-50 hover:border-amber-200 transition-all shadow-sm tooltip"
                                    data-tip="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button wire:click="delete({{ $user->id }})"
                                    class="p-2 bg-white border border-slate-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition-all shadow-sm tooltip"
                                    data-tip="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-search text-2xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">No Users Found</h3>
                                <p class="text-sm mt-1">Try adjusting your search terms or add a new user.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div
        class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
        <span>Showing {{ count($data) }} results</span>
    </div>

</div>