<div class="">
    <!-- <nav id="mobileNav" class="bg-white fixed bottom-0 left-0 w-full bottom-nav flex lg:hidden justify-around items-center p-4 z-50 shadow-2xl">
    @php
          $menuItems = json_decode(file_get_contents(resource_path('views/layouts/partials/adminMenu.json')), true);

    @endphp
    @foreach ($menuItems as $item)
    <a href="{{ $item['route'] }}" class="flex flex-col items-center text-xs {{ request()->url() === url($item['route']) ? 'text-teal-600 nav-active ' : 'text-gray-400' }}">
        <div class="w-8 h-8 flex items-center justify-center text-lg">
            <i class="fas {{ $item['icon'] }}"></i>
        </div>
        <span>{{ $item['name'] }}</span>
    </a>
    @endforeach
</nav> -->
</div>