<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Planning</h2>
            <a href="{{ route('projects.tasks.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouvelle tâche
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4" x-data="{ tab: 'kanban' }">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="flex items-center gap-1 border-b border-slate-700/50">
            <button @click="tab = 'kanban'" :class="tab === 'kanban' ? 'text-white border-b-2 border-blue-500' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2">
                <x-lucide-columns-3 class="w-4 h-4" />
                Kanban
            </button>
            <button @click="tab = 'gantt'" :class="tab === 'gantt' ? 'text-white border-b-2 border-blue-500' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2">
                <x-lucide-gantt-chart class="w-4 h-4" />
                Gantt
            </button>
            <button @click="tab = 'list'" :class="tab === 'list' ? 'text-white border-b-2 border-blue-500' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2">
                <x-lucide-list class="w-4 h-4" />
                Liste
            </button>
        </div>

        {{-- Kanban --}}
        <div x-show="tab === 'kanban'" x-cloak>
            <livewire:planning.kanban-board :project="$project" />
        </div>

        {{-- Gantt --}}
        <div x-show="tab === 'gantt'" x-cloak>
            <livewire:planning.gantt-chart :project="$project" />
        </div>

        {{-- List --}}
        <div x-show="tab === 'list'" x-cloak>
            @php
                $tasks = $project->tasks()->with(['module', 'assignee', 'blockedBy'])->orderByRaw("CASE status WHEN 'blocked' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'todo' THEN 3 WHEN 'done' THEN 4 END")->get();
            @endphp
            <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                        <tr>
                            <th class="px-4 py-3">Tâche</th>
                            <th class="px-4 py-3">Module</th>
                            <th class="px-4 py-3">Assigné</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Progression</th>
                            <th class="px-4 py-3">Dates</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @foreach($tasks as $task)
                            @php
                                $statusColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                                $statusLabels = ['todo' => 'A faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'];
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-slate-200 hover:text-white">{{ $task->title }}</a>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $task->module?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $task->assignee?->name ?? '—' }}</td>
                                <td class="px-4 py-3"><x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $statusLabels[$task->status] ?? $task->status }}</x-ui.badge></td>
                                <td class="px-4 py-3 w-32"><x-ui.progress-bar :value="$task->progress" :color="$task->status === 'done' ? 'emerald' : 'blue'" /></td>
                                <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                    @if($task->start_date && $task->end_date) {{ $task->start_date->format('d/m') }} → {{ $task->end_date->format('d/m') }} @else — @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
