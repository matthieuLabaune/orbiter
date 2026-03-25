<div class="surface p-5">
    <h3 class="text-sm font-medium uppercase tracking-wider mb-4" style="color: var(--orbiter-text-muted);">Activité récente</h3>

    @if($activities->isEmpty())
        <p class="text-sm text-center py-6" style="color: var(--orbiter-text-muted);">Aucune activité récente.</p>
    @else
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach($activities as $activity)
                @php
                    $colors = [
                        'emerald' => 'text-emerald-400 bg-emerald-500/10',
                        'red' => 'text-red-400 bg-red-500/10',
                        'amber' => 'text-amber-400 bg-amber-500/10',
                        'blue' => 'text-blue-400 bg-blue-500/10',
                        'purple' => 'text-purple-400 bg-purple-500/10',
                    ];
                    $colorClass = $colors[$activity['color']] ?? 'text-slate-400 bg-slate-500/10';
                @endphp
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 {{ $colorClass }}">
                        <x-dynamic-component :component="'lucide-' . $activity['icon']" class="w-3.5 h-3.5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-mono" style="color: var(--orbiter-text);">{{ $activity['message'] }}</span>
                            <span class="text-[10px] shrink-0" style="color: var(--orbiter-text-muted);">{{ $activity['date']?->diffForHumans() }}</span>
                        </div>
                        @if($activity['detail'])
                            <div class="text-xs truncate" style="color: var(--orbiter-text-muted);">{{ $activity['detail'] }}</div>
                        @endif
                        @if($activity['user'])
                            <div class="text-[10px]" style="color: var(--orbiter-text-muted);">{{ $activity['user'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
