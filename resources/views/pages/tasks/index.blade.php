<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Planning</h2>
            <a href="{{ route('projects.tasks.create', $project) }}"
               class="btn-primary">
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
        <div class="flex items-center gap-1 border-b" style="border-color: var(--orbiter-border);">
            <button @click="tab = 'kanban'" :class="tab === 'kanban' ? 'border-b-2' : 'opacity-60 hover:opacity-100'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2" :style="tab === 'kanban' ? 'color: var(--orbiter-text); border-color: var(--orbiter-accent);' : 'color: var(--orbiter-text-muted);'">
                <x-lucide-columns-3 class="w-4 h-4" />
                Kanban
            </button>
            <button @click="tab = 'gantt'" :class="tab === 'gantt' ? 'border-b-2' : 'opacity-60 hover:opacity-100'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2" :style="tab === 'gantt' ? 'color: var(--orbiter-text); border-color: var(--orbiter-accent);' : 'color: var(--orbiter-text-muted);'">
                <x-lucide-gantt-chart class="w-4 h-4" />
                Gantt
            </button>
            <button @click="tab = 'list'" :class="tab === 'list' ? 'border-b-2' : 'opacity-60 hover:opacity-100'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer flex items-center gap-2" :style="tab === 'list' ? 'color: var(--orbiter-text); border-color: var(--orbiter-accent);' : 'color: var(--orbiter-text-muted);'">
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
            <div class="surface overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-muted);">
                        <tr>
                            <th class="px-4 py-3">Tâche</th>
                            <th class="px-4 py-3">Module</th>
                            <th class="px-4 py-3">Assigné</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Progression</th>
                            <th class="px-4 py-3">Dates</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--orbiter-border);">
                        @foreach($tasks as $task)
                            @php
                                $statusColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                                $statusLabels = ['todo' => 'A faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'];
                            @endphp
                            <tr class="transition-colors" style="--tw-divide-color: var(--orbiter-border);">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.tasks.show', [$project, $task]) }}" style="color: var(--orbiter-text);">{{ $task->title }}</a>
                                </td>
                                <td class="px-4 py-3 text-xs" style="color: var(--orbiter-text-muted);">{{ $task->module?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs" style="color: var(--orbiter-text-muted);">{{ $task->assignee?->name ?? '—' }}</td>
                                <td class="px-4 py-3"><x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $statusLabels[$task->status] ?? $task->status }}</x-ui.badge></td>
                                <td class="px-4 py-3 w-32"><x-ui.progress-bar :value="$task->progress" :color="$task->status === 'done' ? 'emerald' : 'blue'" /></td>
                                <td class="px-4 py-3 text-xs whitespace-nowrap" style="color: var(--orbiter-text-muted);">
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
