<div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
    <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-4">
        Alertes
        @if($alerts->isNotEmpty())
            <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                {{ $alerts->count() }}
            </span>
        @endif
    </h3>

    @if($alerts->isEmpty())
        <div class="text-center py-6">
            <x-lucide-check-circle class="w-8 h-8 text-emerald-500 mx-auto mb-2" />
            <p class="text-sm text-emerald-400">Aucune alerte</p>
        </div>
    @else
        <div class="space-y-2 max-h-80 overflow-y-auto">
            @foreach($alerts as $alert)
                @php
                    $borderColors = ['critical' => 'border-l-red-500', 'error' => 'border-l-red-400', 'warning' => 'border-l-amber-500'];
                    $iconColors = ['critical' => 'text-red-500', 'error' => 'text-red-400', 'warning' => 'text-amber-500'];
                @endphp
                <a href="{{ $alert['url'] }}"
                   class="flex items-start gap-3 p-3 rounded-lg border-l-2 {{ $borderColors[$alert['type']] ?? 'border-l-slate-500' }} bg-gray-50 dark:bg-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                    <x-dynamic-component :component="'lucide-' . $alert['icon']" class="w-4 h-4 mt-0.5 shrink-0 {{ $iconColors[$alert['type']] ?? 'text-slate-400' }}" />
                    <div class="min-w-0">
                        <div class="text-sm text-gray-800 dark:text-slate-200">{{ $alert['message'] }}</div>
                        @if($alert['detail'])
                            <div class="text-xs text-gray-400 dark:text-slate-500 truncate">{{ $alert['detail'] }}</div>
                        @endif
                        @if($alert['module'])
                            <span class="inline-block mt-1 text-[10px] px-1.5 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 rounded">{{ $alert['module'] }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
