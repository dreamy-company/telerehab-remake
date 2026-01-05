  <aside class="fixed left-0 top-0 h-full w-72 bg-white border-r border-gray-100 p-8 hidden lg:flex flex-col z-50">
      <div class="flex items-center gap-3 mb-12">
          <!-- <div class="w-12 h-12 bg-teal-500 rounded-2xl flex items-center justify-center text-white text-xl shadow-lg shadow-teal-100">
              <i class="fas fa-hand-sparkles"></i>
          </div> -->
          <img src="{{ asset('assets/images/logo_telerehab.jpeg') }}" alt="TeleRehab Logo">
          <!-- <div>
              <h1 class="font-extrabold text-2xl tracking-tighter text-gray-800">TeleRehab</h1>
          </div> -->
      </div>

      <nav id="sidebarNav" class="space-y-3 flex-1">
          @php
          if(Auth::user()->role === 'admin')
          $menuItems = json_decode(file_get_contents(resource_path('views/layouts/partials/adminMenu.json')), true);
          elseif(Auth::user()->role === 'doctor')
          $menuItems = json_decode(file_get_contents(resource_path('views/layouts/partials/doctorMenu.json')), true);
          elseif(Auth::user()->role === 'patient')
          $menuItems = json_decode(file_get_contents(resource_path('views/layouts/partials/patientMenu.json')), true);
          elseif(Auth::user()->role === 'therapist')
          $menuItems = json_decode(file_get_contents(resource_path('views/layouts/partials/therapistMenu.json')), true);
          @endphp

          @foreach ($menuItems as $item)

          {{-- MENU TANPA SUBMENU --}}
          @if (empty($item['subMenu']))
          @php
          $isActive = request()->is(ltrim($item['route'], '/').'*');
          @endphp

          <a href="{{ $item['route'] }}"
              class="flex items-center gap-3 p-3 rounded-xl hover:bg-teal-50 transition-colors
               {{ $isActive ? 'bg-teal-100 text-teal-600 font-semibold nav-active' : 'text-gray-600' }}"
              wire:navigate>
              <i class="fas {{ $item['icon'] }} w-5"></i>
              <span>{{ $item['name'] }}</span>
          </a>

          {{-- MENU DENGAN SUBMENU --}}
          @else
          @php
          $isActive = collect($item['subMenu'])->contains(function ($sub) {
          return request()->is(ltrim($sub['route'], '/').'*');
          });
          @endphp

          <div class="group">
              <button type="button"
                  class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-teal-50 transition-colors
                        {{ $isActive ? 'bg-teal-100 text-teal-600 font-semibold' : 'text-gray-600' }}">
                  <i class="fas {{ $item['icon'] }} w-5"></i>
                  <span>{{ $item['name'] }}</span>
                  <i class="fas fa-chevron-down w-4 ml-auto transition-transform {{ $isActive ? 'rotate-180' : '' }}"></i>
              </button>

              <div class="pl-6 space-y-2 {{ $isActive ? '' : 'hidden' }}">
                  @foreach ($item['subMenu'] as $subItem)
                  @php
                  $subActive = request()->is(ltrim($subItem['route'], '/').'*');
                  @endphp

                  <a href="{{ $subItem['route'] }}"
                      class="flex items-center gap-3 p-2 rounded-lg hover:bg-teal-50 transition-colors
                           {{ $subActive ? 'bg-teal-100 text-teal-600 font-semibold' : 'text-gray-600' }}"
                      wire:navigate>
                      <i class="fas {{ $subItem['icon'] }} w-4"></i>
                      <span class="text-sm">{{ $subItem['name'] }}</span>
                  </a>
                  @endforeach
              </div>
          </div>
          @endif
          @endforeach
      </nav>


      <!-- <div class="bg-teal-50 p-6 rounded-3xl border border-teal-100">
          <p class="text-[10px] font-black text-teal-600 uppercase mb-2" data-i18n="voice_help_title">Bantuan Suara</p>
          <p class="text-xs text-teal-800 italic leading-relaxed" data-i18n="voice_help_desc">"Buka Latihan", "Rekam Video", "Scroll Bawah"</p>
      </div> -->
  </aside>