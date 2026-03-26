<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.tasks.index', $project) }}" class="transition-colors" style="color: var(--o-text-4);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $task->title }}</h2>
                    @php
                        $statusColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                        $statusLabels = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'];
                    @endphp
                    <div class="flex items-center gap-2 mt-1">
                        <x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $statusLabels[$task->status] ?? $task->status }}</x-ui.badge>
                        @if($task->module) <span class="text-xs" style="color: var(--o-text-4);">{{ $task->module->name }}</span> @endif
                    </div>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                   class="btn-secondary">
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
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                        <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $task->description }}</div>
                    </div>
                @endif

                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Progression</h3>
                    <x-ui.progress-bar :value="$task->progress" :color="$task->status === 'done' ? 'emerald' : 'blue'" />
                </div>
            </div>

            <div class="space-y-6">
                <div class="surface p-5 space-y-3">
                    <div>
                        <span class="text-xs" style="color: var(--o-text-4);">Assigné à</span>
                        <div class="text-sm" style="color: var(--o-text);">{{ $task->assignee?->name ?? 'Non assigné' }}</div>
                    </div>
                    <div>
                        <span class="text-xs" style="color: var(--o-text-4);">Exigence</span>
                        <div class="text-sm">
                            @if($task->requirement)
                                <a href="{{ route('projects.requirements.show', [$project, $task->requirement]) }}" class="font-mono" style="color: var(--o-accent);">{{ $task->requirement->ref }}</a>
                            @else
                                <span style="color: var(--o-text-4);">—</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-xs" style="color: var(--o-text-4);">Dates</span>
                        <div class="text-sm" style="color: var(--o-text);">
                            @if($task->start_date && $task->end_date)
                                {{ $task->start_date->format('d/m/Y') }} → {{ $task->end_date->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>

                @if($task->blockedBy->isNotEmpty())
                    <div class="rounded-xl p-5" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2);">
                        <h3 class="text-sm font-medium text-red-400 uppercase tracking-wider mb-3">Bloqué par</h3>
                        @foreach($task->blockedBy as $blocker)
                            <div class="text-sm py-1" style="color: var(--o-text-2);">
                                <a href="{{ route('projects.tasks.show', [$project, $blocker]) }}" class="hover:opacity-80">{{ $blocker->title }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($task->blocks->isNotEmpty())
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Bloque</h3>
                        @foreach($task->blocks as $blocked)
                            <div class="text-sm py-1" style="color: var(--o-text-2);">
                                <a href="{{ route('projects.tasks.show', [$project, $blocked]) }}" class="hover:opacity-80">{{ $blocked->title }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
