<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Projets</h2>
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
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
                <x-lucide-rocket class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-600 dark:text-slate-300 mb-2">Aucun projet</h3>
                <p class="text-gray-400 dark:text-slate-500 mb-6">Créez votre premier projet pour commencer.</p>
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    <x-lucide-plus class="w-4 h-4" />
                    Créer un projet
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                       class="block bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5 hover:border-blue-500/50 hover:bg-gray-50 dark:hover:bg-slate-900 transition-all group">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-gray-900 dark:text-white font-semibold group-hover:text-blue-400 transition-colors">
                                {{ $project->name }}
                            </h3>
                            <span class="text-xs text-gray-400 dark:text-slate-500 font-mono">{{ $project->slug }}</span>
                        </div>

                        @if($project->description)
                            <p class="text-sm text-gray-500 dark:text-slate-400 mb-4 line-clamp-2">{{ $project->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-400 dark:text-slate-500">
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
