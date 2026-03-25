<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.tasks.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $task->title }}</h2>
                    @php
                        $statusColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                        $statusLabels = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'];
                    @endphp
                    <div class="flex items-center gap-2 mt-1">
                        <x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $statusLabels[$task->status] ?? $task->status }}</x-ui.badge>
                        @if($task->module) <span class="text-xs text-slate-500">{{ $task->module->name }}</span> @endif
                    </div>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    <x-lucide-pencil class="w-4 h-4" />
                    Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                @if($task->description)
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                        <div class="text-slate-300 whitespace-pre-wrap">{{ $task->description }}</div>
                    </div>
                @endif

                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Progression</h3>
                    <x-ui.progress-bar :value="$task->progress" :color="$task->status === 'done' ? 'emerald' : 'blue'" />
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5 space-y-3">
                    <div>
                        <span class="text-xs text-slate-500">Assigné à</span>
                        <div class="text-sm text-slate-200">{{ $task->assignee?->name ?? 'Non assigné' }}</div>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500">Exigence</span>
                        <div class="text-sm">
                            @if($task->requirement)
                                <a href="{{ route('projects.requirements.show', [$project, $task->requirement]) }}" class="font-mono text-blue-400 hover:text-blue-300">{{ $task->requirement->ref }}</a>
                            @else
                                <span class="text-slate-500">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500">Dates</span>
                        <div class="text-sm text-slate-200">
                            @if($task->start_date && $task->end_date)
                                {{ $task->start_date->format('d/m/Y') }} → {{ $task->end_date->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>

                @if($task->blockedBy->isNotEmpty())
                    <div class="bg-slate-900/80 border border-red-500/20 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-red-400 uppercase tracking-wider mb-3">Bloqué par</h3>
                        @foreach($task->blockedBy as $blocker)
                            <div class="text-sm text-slate-300 py-1">
                                <a href="{{ route('projects.tasks.show', [$project, $blocker]) }}" class="hover:text-white">{{ $blocker->title }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($task->blocks->isNotEmpty())
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Bloque</h3>
                        @foreach($task->blocks as $blocked)
                            <div class="text-sm text-slate-300 py-1">
                                <a href="{{ route('projects.tasks.show', [$project, $blocked]) }}" class="hover:text-white">{{ $blocked->title }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
