<div class="space-y-6">
    {{-- Global health --}}
    <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
        <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-4">Santé globale du projet</h3>
        <div class="grid grid-cols-4 gap-4 mb-4">
            @foreach([
                ['label' => 'Formalisées', 'value' => $projectHealth['formalized'], 'color' => 'slate'],
                ['label' => 'Couvertes', 'value' => $projectHealth['covered'], 'color' => 'blue'],
                ['label' => 'Vérifiées', 'value' => $projectHealth['verified'], 'color' => 'amber'],
                ['label' => 'Validées', 'value' => $projectHealth['validated'], 'color' => 'emerald'],
            ] as $axis)
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $axis['value'] }}</div>
                    <div class="text-xs text-gray-400 dark:text-slate-500">{{ $axis['label'] }}</div>
                    <x-ui.progress-bar :value="$axis['value']" :max="$projectHealth['total']" :color="$axis['color']" :showLabel="false" class="mt-2" />
                </div>
            @endforeach
        </div>
        <div class="text-center border-t border-gray-100 dark:border-slate-700/50 pt-3">
            <span class="text-xs text-gray-400 dark:text-slate-500">Avancement réel (validation)</span>
            <span class="text-lg font-bold text-emerald-400 font-mono ml-2">{{ $projectHealth['percentage'] ?? 0 }}%</span>
            <span class="text-xs text-gray-400 dark:text-slate-500">· {{ $projectHealth['total'] }} exigences</span>
        </div>
    </div>

    {{-- Per-module health --}}
    <div>
        <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Par module</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            @foreach($moduleHealth as $item)
                <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item['module']->name }}</h4>
                        @php
                            $h = $item['health'];
                            $pct = $h['total'] > 0 ? round(($h['validated'] / $h['total']) * 100) : 0;
                        @endphp
                        <span class="text-xs font-mono {{ $pct === 100 ? 'text-emerald-400' : ($pct > 0 ? 'text-blue-400' : 'text-gray-400 dark:text-slate-500') }}">
                            {{ $pct }}%
                        </span>
                    </div>
                    <x-project.module-health :health="$item['health']" />
                </div>
            @endforeach
        </div>
    </div>
</div>
