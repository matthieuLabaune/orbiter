@props(['items' => []])

<nav class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-slate-500 mb-1">
    @foreach($items as $item)
        @if(!$loop->last)
            <a href="{{ $item['url'] }}" class="hover:text-gray-600 dark:hover:text-slate-300 transition-colors">
                {{ $item['label'] }}
            </a>
            <x-lucide-chevron-right class="w-3 h-3" />
        @else
            <span class="text-gray-600 dark:text-slate-300">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
