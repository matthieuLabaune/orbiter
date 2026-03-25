<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.baselines.index', $project) }}" class="text-gray-400 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-blue-500 dark:text-blue-400 font-semibold">{{ $baseline->ref }}</span>
                    @if($baseline->is_immutable)
                        <x-ui.badge color="emerald">Immutable</x-ui.badge>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $baseline->title }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Meta --}}
        <div class="text-xs text-gray-400 dark:text-slate-500 flex items-center gap-3">
            <span>Creee le {{ $baseline->created_at->format('d/m/Y H:i') }}</span>
            @if($baseline->signed_by)
                <span class="text-gray-300 dark:text-slate-700">·</span>
                <span>Signe par {{ $baseline->signed_by }}</span>
            @endif
        </div>

        {{-- Description --}}
        @if($baseline->description)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                <div class="text-gray-600 dark:text-slate-300 whitespace-pre-wrap">{{ $baseline->description }}</div>
            </div>
        @endif

        {{-- Snapshot summary --}}
        @if($baseline->snapshot)
            @php $snap = $baseline->snapshot; @endphp
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-4">Etat du projet</h3>

                {{-- Counters --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-3 bg-gray-50 dark:bg-slate-800/50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $snap['requirements_count'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">Exigences</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 dark:bg-slate-800/50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $snap['tests_count'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">Tests</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 dark:bg-slate-800/50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $snap['modules_count'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">Modules</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 dark:bg-slate-800/50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $snap['adrs_count'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">ADRs</div>
                    </div>
                </div>

                {{-- Requirements by status --}}
                @if(isset($snap['requirements_by_status']) && count($snap['requirements_by_status']) > 0)
                    <div class="mb-6">
                        <h4 class="text-xs text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-2">Exigences par statut V&V</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $vvColors = ['draft' => 'slate', 'to_verify' => 'amber', 'verified' => 'blue', 'validated' => 'emerald', 'failed' => 'red'];
                            @endphp
                            @foreach($snap['requirements_by_status'] as $status => $count)
                                <x-ui.badge :color="$vvColors[$status] ?? 'slate'">{{ $status }}: {{ $count }}</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tasks summary --}}
                @if(isset($snap['tasks_summary']) && count($snap['tasks_summary']) > 0)
                    <div class="mb-6">
                        <h4 class="text-xs text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-2">Taches</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $taskColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                            @endphp
                            @foreach($snap['tasks_summary'] as $status => $count)
                                <x-ui.badge :color="$taskColors[$status] ?? 'slate'">{{ $status }}: {{ $count }}</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Coverage bars --}}
                @if(isset($snap['coverage']))
                    @php
                        $cov = $snap['coverage'];
                        $total = $cov['total'] ?? 0;
                    @endphp
                    <div class="space-y-3 mb-6">
                        <h4 class="text-xs text-gray-500 dark:text-slate-500 uppercase tracking-wider">Couverture</h4>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-slate-400">Tests lies</span>
                                <span class="font-mono text-gray-500 dark:text-slate-400">{{ $cov['covered'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['covered'] ?? 0" :max="max($total, 1)" color="blue" :showLabel="false" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-slate-400">Verifiees</span>
                                <span class="font-mono text-gray-500 dark:text-slate-400">{{ $cov['verified'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['verified'] ?? 0" :max="max($total, 1)" color="emerald" :showLabel="false" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-slate-400">Validees</span>
                                <span class="font-mono text-gray-500 dark:text-slate-400">{{ $cov['validated'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['validated'] ?? 0" :max="max($total, 1)" color="emerald" :showLabel="false" />
                        </div>
                    </div>
                @endif

                {{-- Anomalies open --}}
                @if(isset($snap['anomalies_open']))
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 dark:text-slate-500">Anomalies ouvertes :</span>
                        <span class="text-sm font-mono {{ ($snap['anomalies_open'] ?? 0) > 0 ? 'text-red-500 dark:text-red-400' : 'text-emerald-500 dark:text-emerald-400' }}">
                            {{ $snap['anomalies_open'] }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Raw snapshot JSON --}}
            <details class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl overflow-hidden">
                <summary class="px-5 py-3 text-sm font-medium text-gray-500 dark:text-slate-400 cursor-pointer hover:text-gray-700 dark:hover:text-slate-300 transition-colors">
                    Snapshot JSON brut
                </summary>
                <div class="px-5 pb-5">
                    <pre class="text-xs text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-slate-800/50 rounded-lg p-4 overflow-x-auto font-mono">{{ json_encode($snap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </details>
        @endif
    </div>
</x-app-layout>
