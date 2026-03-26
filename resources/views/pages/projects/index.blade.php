<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Projets</h2>
            <a href="{{ route('projects.create') }}"
               class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouveau projet
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($projects->isEmpty())
            <div class="text-center py-16">
                <x-lucide-rocket class="w-12 h-12 mx-auto mb-4" style="color: var(--o-text-4);" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucun projet</h3>
                <p class="mb-6" style="color: var(--o-text-4);">Créez votre premier projet pour commencer.</p>
                <a href="{{ route('projects.create') }}"
                   class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors">
                    <x-lucide-plus class="w-4 h-4" />
                    Créer un projet
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                       class="block surface p-5 hover:border-blue-500/50 transition-all group">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-semibold group-hover:text-blue-400 transition-colors" style="color: var(--o-text);">
                                {{ $project->name }}
                            </h3>
                            <span class="text-xs font-mono" style="color: var(--o-text-4);">{{ $project->slug }}</span>
                        </div>

                        @if($project->description)
                            <p class="text-sm mb-4 line-clamp-2" style="color: var(--o-text-2);">{{ $project->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-xs" style="color: var(--o-text-4);">
                            <span class="flex items-center gap-1">
                                <x-lucide-boxes class="w-3.5 h-3.5" />
                                {{ $project->modules_count }} modules
                            </span>
                            <span class="flex items-center gap-1">
                                <x-lucide-list-checks class="w-3.5 h-3.5" />
                                {{ $project->requirements_count }} REQ
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
