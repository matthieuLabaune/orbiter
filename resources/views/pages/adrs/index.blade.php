<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Architecture Decision Records</h2>
            <a href="{{ route('projects.adrs.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouvel ADR
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @forelse($adrs as $adr)
            @php
                $statusColors = ['proposed' => 'amber', 'accepted' => 'emerald', 'deprecated' => 'slate', 'superseded' => 'red'];
                $statusLabels = ['proposed' => 'Proposé', 'accepted' => 'Accepté', 'deprecated' => 'Déprécié', 'superseded' => 'Remplacé'];
            @endphp
            <a href="{{ route('projects.adrs.show', [$project, $adr]) }}"
               class="block bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-blue-600 dark:text-blue-400 text-sm">{{ $adr->ref }}</span>
                            <x-ui.badge :color="$statusColors[$adr->status] ?? 'slate'">{{ $statusLabels[$adr->status] ?? $adr->status }}</x-ui.badge>
                        </div>
                        <h3 class="text-gray-900 dark:text-white font-medium group-hover:text-blue-400 transition-colors">{{ $adr->title }}</h3>
                        @if($adr->modules->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($adr->modules as $mod)
                                    <span class="text-xs px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded">{{ $mod->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 dark:text-slate-500">
                        {{ $adr->created_at->format('d/m/Y') }}
                        <br>{{ $adr->author?->name }}
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-file-text class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-600 dark:text-slate-300 mb-2">Aucun ADR</h3>
                <p class="text-gray-400 dark:text-slate-500 mb-6">Documentez votre première décision d'architecture.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
