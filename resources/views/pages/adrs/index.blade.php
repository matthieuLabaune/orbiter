<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Architecture Decision Records</h2>
            <a href="{{ route('projects.adrs.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 btn-primary transition-colors">
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
               class="block surface p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-sm" style="color: var(--o-accent);">{{ $adr->ref }}</span>
                            <x-ui.badge :color="$statusColors[$adr->status] ?? 'slate'">{{ $statusLabels[$adr->status] ?? $adr->status }}</x-ui.badge>
                        </div>
                        <h3 class="font-medium group-hover:text-blue-400 transition-colors" style="color: var(--o-text);">{{ $adr->title }}</h3>
                        @if($adr->modules->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($adr->modules as $mod)
                                    <span class="text-xs px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $mod->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-xs" style="color: var(--o-text-4);">
                        {{ $adr->created_at->format('d/m/Y') }}
                        <br>{{ $adr->author?->name }}
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-file-text class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucun ADR</h3>
                <p class="mb-6" style="color: var(--o-text-4);">Documentez votre première décision d'architecture.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
