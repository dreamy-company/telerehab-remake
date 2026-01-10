<div class="flex flex-wrap gap-4">
    @foreach ($data as $item)
    <div class="relative bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border {{ $loop->first ? 'border-primary-500 ring-1 ring-primary-500' : 'border-gray-100' }}">
        @if($loop->first && $item->status === 'process' && now()->lessThanOrEqualTo($item->target))
            <span class="absolute -top-3 left-4 bg-primary-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            Current Rehabilitation
            </span>
        @elseif($loop->first && $item->status === 'process' && now()->greaterThan($item->target))
            <span class="absolute -top-3 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            Overdue Rehabilitation
            </span>
        @elseif($item->status === 'complete')
            <span class="absolute -top-3 left-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            Completed Rehabilitation
            </span>
        @endif
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $item->rehabilitation?->name }}</h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->rehabilitation?->description }}</p>
        <a href="{{ route('patient.rehabilitation.exercise', ['id' => $item->id]) }}" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2.5 px-5 rounded-lg transition-all duration-200 hover:scale-105">
            View Exercises
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
    </div>
    @endforeach
</div>