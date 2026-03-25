<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Baselines</h2>
            <a href="{{ route('projects.baselines.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouvelle baseline
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @forelse($baselines as $baseline)
            <a href="{{ route('projects.baselines.show', [$project, $baseline]) }}"
               class="block bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-blue-500 dark:text-blue-400 text-sm font-semibold">{{ $baseline->ref }}</span>
                        </div>
                        <h3 class="text-gray-900 dark:text-white font-medium group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">{{ $baseline->title }}</h3>
                        @if($baseline->description)
                            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $baseline->description }}</p>
                        @endif
                        @if($baseline->signed_by)
                            <div class="text-xs text-gray-400 dark:text-slate-500 mt-2">Signe par {{ $baseline->signed_by }}</div>
                        @endif

                        {{-- Mini coverage bar --}}
                        @if($baseline->snapshot && isset($baseline->snapshot['coverage']))
                            @php
                                $cov = $baseline->snapshot['coverage'];
                                $total = $cov['total'] ?? 0;
                                $covered = $cov['covered'] ?? 0;
                                $covPct = $total > 0 ? round(($covered / $total) * 100) : 0;
                            @endphp
                            <div class="mt-3 flex items-center gap-3">
                                <span class="text-xs text-gray-500 dark:text-slate-500">Couverture</span>
                                <div class="flex-1 max-w-48 bg-gray-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all" style="width: {{ $covPct }}%"></div>
                                </div>
                                <span class="text-xs font-mono text-gray-500 dark:text-slate-400">{{ $covPct }}%</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 dark:text-slate-500 text-right ml-4">
                        {{ $baseline->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-git-branch class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-600 dark:text-slate-300 mb-2">Aucune baseline</h3>
                <p class="text-gray-400 dark:text-slate-500">Gelez l'etat du projet a un instant donne.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
