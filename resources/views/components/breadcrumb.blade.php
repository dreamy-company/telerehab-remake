<nav class="flex px-6 py-3 text-slate-700 border border-slate-200 rounded-xl bg-white shadow-sm mb-6 overflow-x-auto"
    aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse whitespace-nowrap">

        <li class="inline-flex items-center">
            <a href="{{ url('/') }}"
                class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-home mr-2 text-xs"></i>
                Dashboard
            </a>
        </li>

        @php
            $segments = Request::segments();
            $currentUrl = '';
            $totalSegments = count($segments);
        @endphp

        @foreach($segments as $index => $segment)
            @php
                $currentUrl .= '/' . $segment;
                // Ubah 'admin-panel' menjadi 'Admin Panel'
                $label = ucwords(str_replace('-', ' ', $segment));

                // Cek apakah segmen ini adalah segmen terakhir (Halaman Aktif)
                $isActive = ($index + 1) === $totalSegments;

                // Opsional: Jika segmen adalah angka (ID), tambahkan '#' atau lewati
                $isNumeric = is_numeric($segment);
                if ($isNumeric) {
                    $label = '#' . $segment;
                }
            @endphp

            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-300 text-[10px] mx-1"></i>

                    @if($isActive)
                        <span
                            class="ml-1 text-sm font-bold text-slate-800 md:ml-2 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                            {{ $label }}
                        </span>
                    @else
                        <a href="{{ url($currentUrl) }}"
                            class="ml-1 text-sm font-medium text-slate-500 hover:text-primary-600 md:ml-2 transition-colors">
                            {{ $label }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach

    </ol>
</nav>