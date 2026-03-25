<div class="surface p-5">
    <h3 class="text-sm font-medium uppercase tracking-wider mb-4" style="color: var(--orbiter-text-muted);">
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
                   class="flex items-start gap-3 p-3 rounded-lg border-l-2 {{ $borderColors[$alert['type']] ?? 'border-l-slate-500' }} hover:opacity-90 transition-colors" style="background: var(--orbiter-surface-2);">
                    <x-dynamic-component :component="'lucide-' . $alert['icon']" class="w-4 h-4 mt-0.5 shrink-0 {{ $iconColors[$alert['type']] ?? 'text-slate-400' }}" />
                    <div class="min-w-0">
                        <div class="text-sm" style="color: var(--orbiter-text);">{{ $alert['message'] }}</div>
                        @if($alert['detail'])
                            <div class="text-xs truncate" style="color: var(--orbiter-text-muted);">{{ $alert['detail'] }}</div>
                        @endif
                        @if($alert['module'])
                            <span class="inline-block mt-1 text-[10px] px-1.5 py-0.5 rounded" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-secondary);">{{ $alert['module'] }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
