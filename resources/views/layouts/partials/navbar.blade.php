<header>
    <div class="flex justify-between items-center mb-12 container px-6">
        <div class="md:ml-5">
            <h2 id="pageTitle" class="text-4xl font-extrabold text-gray-900 tracking-tight" data-i18n="welcome_title">
                Halo,
                {{ Auth::user()->name }}
            </h2>
            @if(Auth::user()->role == 'patient')
                <p id="pageSubtitle" class="text-gray-500 mt-1" data-i18n="welcome_subtitle">Ready for your recovery
                    process?
                </p>
            @endif
        </div>
        <div class="flex items-center gap-5  md:mr-24">
            <div
                class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100 hover:cursor-pointer">
                <a href="{{ route('auth.logout') }}"
                    class="flex items-center justify-center w-full h-full rounded-2xl hover:bg-gray-50 cursor-pointer">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </a>
            </div>
        </div>
    </div>
    <x-breadcrumb />
</header>