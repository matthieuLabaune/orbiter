<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.show', $project) }}" class="hover:opacity-80 transition-colors" style="color: var(--orbiter-text-muted);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Modules</h2>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.modules.create', $project) }}"
                   class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors">
                    <x-lucide-plus class="w-4 h-4" />
                    Nouveau module
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($modules->isEmpty())
            <div class="text-center py-16">
                <x-lucide-boxes class="w-12 h-12 mx-auto mb-4" style="color: var(--orbiter-text-muted);" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--orbiter-text-secondary);">Aucun module</h3>
                <p class="mb-6" style="color: var(--orbiter-text-muted);">Créez votre premier module pour commencer.</p>
                @can('update', $project)
                    <a href="{{ route('projects.modules.create', $project) }}"
                       class="btn-primary inline-flex items-center gap-2 px-4 py-2 text-sm font-medium transition-colors">
                        <x-lucide-plus class="w-4 h-4" />
                        Créer un module
                    </a>
                @endcan
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($modules as $module)
                    <a href="{{ route('projects.modules.show', [$project, $module]) }}"
                       class="block surface p-5 hover:border-blue-500/50 transition-all group">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-semibold group-hover:text-blue-400 transition-colors" style="color: var(--orbiter-text);">
                                {{ $module->name }}
                            </h3>
                            <x-ui.badge :color="match($module->status) { 'active' => 'emerald', 'deprecated' => 'amber', default => 'slate' }">
                                {{ $module->status }}
                            </x-ui.badge>
                        </div>

                        <p class="text-xs mb-2" style="color: var(--orbiter-text-muted);">{{ $module->owner->name ?? 'N/A' }}</p>

                        @if($module->description)
                            <p class="text-sm mb-4 line-clamp-2" style="color: var(--orbiter-text-secondary);">{{ $module->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-xs" style="color: var(--orbiter-text-muted);">
                            <span class="flex items-center gap-1">
                                <x-lucide-list-checks class="w-3.5 h-3.5" />
                                {{ $module->requirements_count }} REQ
                            </span>
                            <span class="flex items-center gap-1">
                                <x-lucide-circle-check class="w-3.5 h-3.5" />
                                {{ $module->tasks_count }} tâches
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
