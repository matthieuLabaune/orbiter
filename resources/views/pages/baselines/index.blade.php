<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Baselines</h2>
            <a href="{{ route('projects.baselines.create', $project) }}"
               class="btn-primary">
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
               class="block surface p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-sm font-semibold" style="color: var(--o-accent);">{{ $baseline->ref }}</span>
                        </div>
                        <h3 class="font-medium transition-colors" style="color: var(--o-text);">{{ $baseline->title }}</h3>
                        @if($baseline->description)
                            <p class="text-sm mt-1 line-clamp-2" style="color: var(--o-text-4);">{{ $baseline->description }}</p>
                        @endif
                        @if($baseline->signed_by)
                            <div class="text-xs mt-2" style="color: var(--o-text-4);">Signe par {{ $baseline->signed_by }}</div>
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
                                <span class="text-xs" style="color: var(--o-text-4);">Couverture</span>
                                <div class="flex-1 max-w-48 rounded-full h-1.5 overflow-hidden" style="background: var(--o-surface-2);">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all" style="width: {{ $covPct }}%"></div>
                                </div>
                                <span class="text-xs font-mono" style="color: var(--o-text-4);">{{ $covPct }}%</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-right ml-4" style="color: var(--o-text-4);">
                        {{ $baseline->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-git-branch class="w-12 h-12 mx-auto mb-4" style="color: var(--o-text-4);" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucune baseline</h3>
                <p style="color: var(--o-text-4);">Gelez l'etat du projet a un instant donne.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
