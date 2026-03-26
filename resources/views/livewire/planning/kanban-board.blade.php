<div>
    {{-- Module filter --}}
    <div class="mb-4">
        <select wire:model.live="moduleFilter"
                class="input-field py-1.5">
            <option value="">Tous les modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Kanban columns --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($columns as $status => $config)
            @php
                $columnTasks = $tasks->get($status, collect());
                $dotColors = ['slate' => 'bg-slate-500', 'blue' => 'bg-blue-500', 'red' => 'bg-red-500', 'emerald' => 'bg-emerald-500'];
            @endphp
            <div class="surface p-3 min-h-[200px]">
                {{-- Column header --}}
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $dotColors[$config['color']] ?? 'bg-slate-500' }}"></span>
                        <span class="text-sm font-medium" style="color: var(--o-text-2);">{{ $config['label'] }}</span>
                    </div>
                    <span class="text-xs font-mono" style="color: var(--o-text-4);">{{ $columnTasks->count() }}</span>
                </div>

                {{-- Cards --}}
                <div class="space-y-2">
                    @foreach($columnTasks as $task)
                        <div class="surface-elevated p-3 transition-colors">
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}"
                               class="text-sm font-medium leading-snug block mb-2" style="color: var(--o-text);">
                                {{ $task->title }}
                            </a>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    @if($task->module)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $task->module->name }}</span>
                                    @endif
                                </div>
                                @if($task->assignee)
                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-medium" style="background: var(--o-surface-2); color: var(--o-text-2);"
                                         title="{{ $task->assignee->name }}">
                                        {{ substr($task->assignee->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            @if($task->progress > 0 && $task->progress < 100)
                                <div class="mt-2">
                                    <x-ui.progress-bar :value="$task->progress" color="blue" :showLabel="false" />
                                </div>
                            @endif

                            @if($task->blockedBy->isNotEmpty())
                                <div class="mt-2 flex items-center gap-1 text-[10px] text-red-400">
                                    <x-lucide-lock class="w-3 h-3" />
                                    Bloqué par {{ $task->blockedBy->count() }} tâche(s)
                                </div>
                            @endif

                            {{-- Quick status change --}}
                            @if($status !== 'done')
                                <div class="mt-2 flex gap-1">
                                    @if($status !== 'in_progress')
                                        <button wire:click="updateTaskStatus({{ $task->id }}, 'in_progress')"
                                                class="text-[10px] px-1.5 py-0.5 bg-blue-500/10 text-blue-400 rounded hover:bg-blue-500/20 transition-colors cursor-pointer">
                                            Démarrer
                                        </button>
                                    @endif
                                    <button wire:click="updateTaskStatus({{ $task->id }}, 'done')"
                                            class="text-[10px] px-1.5 py-0.5 bg-emerald-500/10 text-emerald-400 rounded hover:bg-emerald-500/20 transition-colors cursor-pointer">
                                        Terminer
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
