<header>
    <div class="flex justify-between items-center mb-12 container px-6">
        <div class="md:ml-5">
            <h2 id="pageTitle" class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight" data-i18n="welcome_title">
                {{ __('Hello,') }}
                {{ Auth::user()->name }}
            </h2>
            @if(Auth::user()->role == 'patient')
                <p id="pageSubtitle" class="text-gray-500 mt-1" data-i18n="welcome_subtitle">{{ __('Ready for your recovery process?') }}
                </p>
            @endif
        </div>
        <div class="flex items-center gap-5  md:mr-24">

            {{-- Language switcher --}}
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button"
                    class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-500 border border-gray-100 hover:bg-gray-50 cursor-pointer gap-1"
                    aria-label="{{ __('Change language') }}">
                    <i class="fas fa-globe text-xl"></i>
                    <span class="text-xs font-bold uppercase">{{ app()->getLocale() }}</span>
                </div>
                <ul tabindex="0"
                    class="dropdown-content menu bg-white rounded-2xl z-[60] w-40 p-2 shadow-lg border border-gray-100 mt-2">
                    <li>
                        <a href="{{ route('locale.switch', 'id') }}"
                            class="flex items-center gap-2 rounded-xl {{ app()->getLocale() === 'id' ? 'font-bold text-primary-600' : '' }}">
                            🇮🇩 {{ __('Indonesian') }}
                            @if(app()->getLocale() === 'id') <i class="fas fa-check ml-auto text-xs"></i> @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('locale.switch', 'en') }}"
                            class="flex items-center gap-2 rounded-xl {{ app()->getLocale() === 'en' ? 'font-bold text-primary-600' : '' }}">
                            🇬🇧 {{ __('English') }}
                            @if(app()->getLocale() === 'en') <i class="fas fa-check ml-auto text-xs"></i> @endif
                        </a>
                    </li>
                </ul>
            </div>

            <div
                class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100 hover:cursor-pointer">
                <a href="{{ route('auth.profile', ['role' => Auth::user()->role, 'id' => encrypt(Auth::user()->id)]) }}"
                    class="flex items-center justify-center w-full h-full rounded-2xl hover:bg-gray-50 cursor-pointer">
                    <i class="fas fa-user text-xl"></i>
                </a>
            </div>
           
            <div
                class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100 hover:cursor-pointer">
                <a href="{{ route('auth.logout') }}"
                    class="flex items-center justify-center w-full h-full rounded-2xl hover:bg-gray-50 cursor-pointer">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- <x-breadcrumb /> -->
</header>