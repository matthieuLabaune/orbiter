<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.adrs.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono" style="color: var(--o-accent);">{{ $adr->ref }}</span>
                        @php
                            $statusColors = ['proposed' => 'amber', 'accepted' => 'emerald', 'deprecated' => 'slate', 'superseded' => 'red'];
                            $statusLabels = ['proposed' => 'Proposé', 'accepted' => 'Accepté', 'deprecated' => 'Déprécié', 'superseded' => 'Remplacé'];
                        @endphp
                        <x-ui.badge :color="$statusColors[$adr->status] ?? 'slate'">{{ $statusLabels[$adr->status] ?? $adr->status }}</x-ui.badge>
                    </div>
                    <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $adr->title }}</h2>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.adrs.edit', [$project, $adr]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    <x-lucide-pencil class="w-4 h-4" />
                    Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-xs flex items-center gap-3" style="color: var(--o-text-4);">
            <span>{{ $adr->created_at->format('d/m/Y') }}</span>
            <span>Par {{ $adr->author?->name ?? '—' }}</span>
            @if($adr->modules->isNotEmpty())
                <span>·</span>
                @foreach($adr->modules as $mod)
                    <span class="px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $mod->name }}</span>
                @endforeach
            @endif
        </div>

        @foreach([['Contexte', $adr->context], ['Décision', $adr->decision], ['Conséquences', $adr->consequences]] as [$title, $content])
            @if($content)
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">{{ $title }}</h3>
                    <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $content }}</div>
                </div>
            @endif
        @endforeach

        @if($adr->superseded_by)
            <div class="bg-amber-500/5 border border-amber-500/20 rounded-xl p-4">
                <p class="text-sm text-amber-400">Remplacé par : <span class="font-mono">{{ $adr->superseded_by }}</span></p>
            </div>
        @endif
    </div>
</x-app-layout>
