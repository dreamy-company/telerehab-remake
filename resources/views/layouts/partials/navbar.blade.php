<header class="flex justify-between items-center mb-12">
    <div>
        <h2 id="pageTitle" class="text-4xl font-extrabold text-gray-900 tracking-tight" data-i18n="welcome_title">Halo, {{ Auth::user()->name }}</h2>
        <p id="pageSubtitle" class="text-gray-500 mt-1" data-i18n="welcome_subtitle">Siap untuk progres pemulihan hari ini?</p>
    </div>
    <div class="flex items-center gap-5 mr-40 lg:mr-24">
        <div class="w-14 h-14 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-teal-200">
            <img id="userAvatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Stefani" alt="Avatar">
        </div>
        <div class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100 hover:cursor-pointer">
            <a
                href="{{ route('auth.logout') }}"
                class="flex items-center justify-center w-full h-full rounded-2xl hover:bg-gray-50 cursor-pointer">
                <i class="fas fa-sign-out-alt text-xl"></i>
            </a>
        </div>
    </div>
</header>