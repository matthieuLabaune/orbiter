<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.diagrams.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $diagram->title }}</h2>
                    <span class="text-xs text-slate-500 font-mono">v{{ $diagram->version }}</span>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.diagrams.edit', [$project, $diagram]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
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
        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6">
            <x-charts.mermaid-diagram :source="$diagram->mermaid_source" />
        </div>

        {{-- Source code --}}
        <details class="bg-slate-900/80 border border-slate-700/50 rounded-xl">
            <summary class="px-5 py-3 text-sm text-slate-400 cursor-pointer hover:text-white transition-colors">
                Voir le source Mermaid
            </summary>
            <div class="px-5 pb-4">
                <pre class="bg-slate-800 rounded-lg p-4 text-sm text-slate-300 font-mono overflow-x-auto">{{ $diagram->mermaid_source }}</pre>
            </div>
        </details>

        {{-- Version history --}}
        @if($diagram->versions->isNotEmpty())
            <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Historique des versions</h3>
                <div class="space-y-3">
                    @foreach($diagram->versions->sortByDesc('version') as $version)
                        <details class="border-l-2 border-slate-700 pl-4">
                            <summary class="cursor-pointer text-xs text-slate-500 hover:text-slate-300 transition-colors">
                                v{{ $version->version }} — {{ $version->created_at->format('d/m/Y H:i') }}
                                @if($version->changedBy) · {{ $version->changedBy->name }} @endif
                            </summary>
                            <pre class="mt-2 bg-slate-800 rounded-lg p-3 text-xs text-slate-400 font-mono overflow-x-auto">{{ $version->mermaid_source }}</pre>
                        </details>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
