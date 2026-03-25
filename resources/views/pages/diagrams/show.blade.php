<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.diagrams.index', $project) }}" class="transition-colors" style="color: var(--orbiter-text-muted);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">{{ $diagram->title }}</h2>
                    <span class="text-xs font-mono" style="color: var(--orbiter-text-muted);">v{{ $diagram->version }}</span>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.diagrams.edit', [$project, $diagram]) }}"
                   class="btn-secondary">
                    <x-lucide-pencil class="w-4 h-4" />
                    Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Rendered diagram --}}
        <div class="surface p-6">
            <x-charts.mermaid-diagram :source="$diagram->mermaid_source" />
        </div>

        {{-- Source code --}}
        <details class="surface">
            <summary class="px-5 py-3 text-sm cursor-pointer transition-colors" style="color: var(--orbiter-text-muted);">
                Voir le source Mermaid
            </summary>
            <div class="px-5 pb-4">
                <pre class="rounded-lg p-4 text-sm font-mono overflow-x-auto" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-secondary);">{{ $diagram->mermaid_source }}</pre>
            </div>
        </details>

        {{-- Version history --}}
        @if($diagram->versions->isNotEmpty())
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--orbiter-text-muted);">Historique des versions</h3>
                <div class="space-y-3">
                    @foreach($diagram->versions->sortByDesc('version') as $version)
                        <details class="border-l-2 pl-4" style="border-color: var(--orbiter-border);">
                            <summary class="cursor-pointer text-xs transition-colors" style="color: var(--orbiter-text-muted);">
                                v{{ $version->version }} — {{ $version->created_at->format('d/m/Y H:i') }}
                                @if($version->changedBy) · {{ $version->changedBy->name }} @endif
                            </summary>
                            <pre class="mt-2 rounded-lg p-3 text-xs font-mono overflow-x-auto" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted);">{{ $version->mermaid_source }}</pre>
                        </details>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
