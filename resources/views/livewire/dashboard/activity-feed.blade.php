<div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
    <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-4">Activité récente</h3>

    @if($activities->isEmpty())
        <p class="text-sm text-gray-400 dark:text-slate-500 text-center py-6">Aucune activité récente.</p>
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
                            <span class="text-sm font-mono text-gray-800 dark:text-slate-200">{{ $activity['message'] }}</span>
                            <span class="text-[10px] text-gray-300 dark:text-slate-600 shrink-0">{{ $activity['date']?->diffForHumans() }}</span>
                        </div>
                        @if($activity['detail'])
                            <div class="text-xs text-gray-400 dark:text-slate-500 truncate">{{ $activity['detail'] }}</div>
                        @endif
                        @if($activity['user'])
                            <div class="text-[10px] text-gray-300 dark:text-slate-600">{{ $activity['user'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
