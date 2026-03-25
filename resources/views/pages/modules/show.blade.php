<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.modules.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $module->name }}</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        <x-ui.badge :color="match($module->status) { 'active' => 'emerald', 'deprecated' => 'amber', default => 'slate' }">
                            {{ $module->status }}
                        </x-ui.badge>
                        <span class="text-xs text-slate-500">par {{ $module->owner->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.modules.edit', [$project, $module]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
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
            <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                <p class="text-slate-300">{{ $module->description }}</p>
            </div>
        @endif

        {{-- Exigences --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-4">Exigences</h3>
            @if($module->requirements->isEmpty())
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-8 text-center">
                    <x-lucide-list-checks class="w-8 h-8 text-slate-600 mx-auto mb-3" />
                    <p class="text-slate-500">Aucune exigence pour ce module.</p>
                </div>
            @else
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left">Ref</th>
                                <th class="px-4 py-3 text-left">Titre</th>
                                <th class="px-4 py-3 text-left">Statut V&V</th>
                                <th class="px-4 py-3 text-right">Tests</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach($module->requirements as $requirement)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <a href="#" class="font-mono text-blue-400 hover:text-blue-300">
                                            {{ $requirement->ref }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">{{ $requirement->title }}</td>
                                    <td class="px-4 py-3">
                                        <x-project.vv-status :status="$requirement->vv_status ?? 'untested'" />
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-400">{{ $requirement->tests->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Dépendances --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-4">Dépendances</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h4 class="text-sm font-medium text-slate-400 mb-3">Dépend de</h4>
                    @if($module->dependencies->isEmpty())
                        <p class="text-sm text-slate-500">Aucune dépendance.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($module->dependencies as $dep)
                                <li>
                                    <a href="{{ route('projects.modules.show', [$project, $dep]) }}"
                                       class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                                        {{ $dep->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h4 class="text-sm font-medium text-slate-400 mb-3">Dépendants</h4>
                    @if($module->dependents->isEmpty())
                        <p class="text-sm text-slate-500">Aucun module dépendant.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($module->dependents as $dep)
                                <li>
                                    <a href="{{ route('projects.modules.show', [$project, $dep]) }}"
                                       class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
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
            <h3 class="text-lg font-semibold text-white mb-4">Tâches</h3>
            @if($module->tasks->isEmpty())
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-8 text-center">
                    <x-lucide-circle-check class="w-8 h-8 text-slate-600 mx-auto mb-3" />
                    <p class="text-slate-500">Aucune tâche pour ce module.</p>
                </div>
            @else
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left">Titre</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-left">Priorité</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            @foreach($module->tasks as $task)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-slate-300">{{ $task->title }}</td>
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
