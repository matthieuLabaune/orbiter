<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.modules.index', $project) }}" class="hover:opacity-80 transition-colors" style="color: var(--orbiter-text-muted);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">{{ $module->name }}</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <x-ui.badge :color="match($module->status) { 'active' => 'emerald', 'deprecated' => 'amber', default => 'slate' }">
                            {{ $module->status }}
                        </x-ui.badge>
                        <span class="text-xs" style="color: var(--orbiter-text-muted);">par {{ $module->owner->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.modules.edit', [$project, $module]) }}"
                   class="btn-secondary inline-flex items-center gap-2 px-3 py-1.5 text-sm transition-colors">
                    <x-lucide-settings class="w-4 h-4" />
                    Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Description --}}
        @if($module->description)
            <div class="surface p-5">
                <p style="color: var(--orbiter-text-secondary);">{{ $module->description }}</p>
            </div>
        @endif

        {{-- Exigences --}}
        <div>
            <h3 class="text-lg font-semibold mb-4" style="color: var(--orbiter-text);">Exigences</h3>
            @if($module->requirements->isEmpty())
                <div class="surface p-8 text-center">
                    <x-lucide-list-checks class="w-8 h-8 mx-auto mb-3" style="color: var(--orbiter-text-muted);" />
                    <p style="color: var(--orbiter-text-muted);">Aucune exigence pour ce module.</p>
                </div>
            @else
                <div class="surface overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted);">
                            <tr>
                                <th class="px-4 py-3 text-left">Ref</th>
                                <th class="px-4 py-3 text-left">Titre</th>
                                <th class="px-4 py-3 text-left">Statut V&V</th>
                                <th class="px-4 py-3 text-right">Tests</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="--tw-divide-opacity: 1; border-color: var(--orbiter-border);">
                            @foreach($module->requirements as $requirement)
                                <tr class="hover:opacity-90 transition-colors">
                                    <td class="px-4 py-3">
                                        <a href="#" class="font-mono hover:opacity-80" style="color: var(--orbiter-accent);">
                                            {{ $requirement->ref }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3" style="color: var(--orbiter-text-secondary);">{{ $requirement->title }}</td>
                                    <td class="px-4 py-3">
                                        <x-project.vv-status :status="$requirement->vv_status ?? 'untested'" />
                                    </td>
                                    <td class="px-4 py-3 text-right" style="color: var(--orbiter-text-secondary);">{{ $requirement->tests->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Dépendances --}}
        <div>
            <h3 class="text-lg font-semibold mb-4" style="color: var(--orbiter-text);">Dépendances</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="surface p-5">
                    <h4 class="text-sm font-medium mb-3" style="color: var(--orbiter-text-muted);">Dépend de</h4>
                    @if($module->dependencies->isEmpty())
                        <p class="text-sm" style="color: var(--orbiter-text-muted);">Aucune dépendance.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($module->dependencies as $dep)
                                <li>
                                    <a href="{{ route('projects.modules.show', [$project, $dep]) }}"
                                       class="text-sm hover:opacity-80 transition-colors" style="color: var(--orbiter-accent);">
                                        {{ $dep->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="surface p-5">
                    <h4 class="text-sm font-medium mb-3" style="color: var(--orbiter-text-muted);">Dépendants</h4>
                    @if($module->dependents->isEmpty())
                        <p class="text-sm" style="color: var(--orbiter-text-muted);">Aucun module dépendant.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($module->dependents as $dep)
                                <li>
                                    <a href="{{ route('projects.modules.show', [$project, $dep]) }}"
                                       class="text-sm hover:opacity-80 transition-colors" style="color: var(--orbiter-accent);">
                                        {{ $dep->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tâches --}}
        <div>
            <h3 class="text-lg font-semibold mb-4" style="color: var(--orbiter-text);">Tâches</h3>
            @if($module->tasks->isEmpty())
                <div class="surface p-8 text-center">
                    <x-lucide-circle-check class="w-8 h-8 mx-auto mb-3" style="color: var(--orbiter-text-muted);" />
                    <p style="color: var(--orbiter-text-muted);">Aucune tâche pour ce module.</p>
                </div>
            @else
                <div class="surface overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted);">
                            <tr>
                                <th class="px-4 py-3 text-left">Titre</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-left">Priorité</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="--tw-divide-opacity: 1; border-color: var(--orbiter-border);">
                            @foreach($module->tasks as $task)
                                <tr class="hover:opacity-90 transition-colors">
                                    <td class="px-4 py-3" style="color: var(--orbiter-text-secondary);">{{ $task->title }}</td>
                                    <td class="px-4 py-3">
                                        <x-ui.badge :color="match($task->status ?? 'todo') { 'done' => 'emerald', 'in_progress' => 'blue', default => 'slate' }">
                                            {{ $task->status ?? 'todo' }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-ui.badge :color="match($task->priority ?? 'medium') { 'high' => 'red', 'medium' => 'amber', default => 'slate' }">
                                            {{ $task->priority ?? 'medium' }}
                                        </x-ui.badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
