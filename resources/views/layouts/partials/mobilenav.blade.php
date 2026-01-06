<div class="">
    <nav id="mobileNav"
        class="bg-white fixed bottom-0 left-0 w-full bottom-nav flex lg:hidden justify-around items-center p-4 z-50 shadow-2xl">
        @php
            $role = Auth::user()->role;
            $menuItems = json_decode(file_get_contents(resource_path("views/layouts/partials/{$role}Menu.json")), true);
        @endphp
        @foreach ($menuItems as $item)
            @if(isset($item['subMenu']) && count($item['subMenu']) > 0)
                <div class="relative group flex flex-col items-center">
                    <div
                        class="absolute bottom-full mb-4 hidden group-hover:block group-focus-within:block bg-white shadow-xl rounded-lg py-2 min-w-[140px] left-1/2 -translate-x-1/2 border border-gray-100">
                        @foreach($item['subMenu'] as $sub)
                            <a href="{{ $sub['route'] }}"
                                class="block px-4 py-2 text-sm text-gray-600 hover:bg-teal-50 hover:text-teal-600">
                                {{ $sub['name'] }}
                            </a>
                        @endforeach
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-white rotate-45"></div>
                    </div>
                    <button
                        class="flex flex-col items-center text-xs {{ request()->url() === url($item['route']) ? 'text-teal-600 nav-active' : 'text-gray-400' }}">
                        <div class="w-8 h-8 flex items-center justify-center text-lg">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <span class="hidden sm:block">{{ $item['name'] }}</span>
                    </button>
                </div>
            @else
                <a href="{{ $item['route'] }}"
                    class="flex flex-col items-center text-xs {{ request()->url() === url($item['route']) ? 'text-teal-600 nav-active' : 'text-gray-400' }}">
                    <div class="w-8 h-8 flex items-center justify-center text-lg">
                        <i class="fas {{ $item['icon'] }}"></i>
                    </div>
                    <span class="hidden sm:block">{{ $item['name'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</div>