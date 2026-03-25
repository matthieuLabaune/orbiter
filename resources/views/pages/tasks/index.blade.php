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

    <div class="max-w-7xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap items-center gap-3 bg-slate-900/80 border border-slate-700/50 rounded-xl p-4">
            <select name="module" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>{{ $module->name }}</option>
                @endforeach
            </select>
            <select name="status" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les statuts</option>
                @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="assignee" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les assignés</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ request('assignee') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-lg transition-colors">Filtrer</button>
        </form>

        {{-- Table --}}
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
                    @forelse($tasks as $task)
                        @php
                            $statusColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                            $statusLabels = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'];
                        @endphp
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-slate-200 hover:text-white">
                                    {{ $task->title }}
                                </a>
                                @if($task->blockedBy->isNotEmpty())
                                    <div class="text-xs text-red-400 mt-0.5">
                                        Bloqué par {{ $task->blockedBy->count() }} tâche(s)
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $task->module?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">{{ $task->assignee?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $statusLabels[$task->status] ?? $task->status }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3 w-32">
                                <x-ui.progress-bar :value="$task->progress" :color="$task->status === 'done' ? 'emerald' : 'blue'" />
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                @if($task->start_date && $task->end_date)
                                    {{ $task->start_date->format('d/m') }} → {{ $task->end_date->format('d/m') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucune tâche.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
