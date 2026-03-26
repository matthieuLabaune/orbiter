<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.user-stories.index', $project) }}" class="opacity-40 hover:opacity-100 transition-opacity" style="color: var(--o-text-3);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-medium" style="color: var(--o-accent);">{{ $userStory->ref }}</span>
                        @php
                            $statusColors = ['open' => 'blue', 'in_progress' => 'orange', 'done' => 'green', 'closed' => 'gray'];
                            $priorityColors = ['P0' => 'red', 'P1' => 'orange', 'P2' => 'blue', 'P3' => 'gray'];
                        @endphp
                        <x-ui.badge :color="$priorityColors[$userStory->priority] ?? 'gray'">{{ $userStory->priority }}</x-ui.badge>
                        <x-ui.badge :color="$statusColors[$userStory->status] ?? 'gray'">{{ $userStory->status }}</x-ui.badge>
                    </div>
                    <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $userStory->title }}</h2>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.user-stories.edit', [$project, $userStory]) }}" class="btn-secondary">
                    <x-lucide-pencil class="w-4 h-4" /> Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: var(--o-green-bg); color: var(--o-green);">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-6">
            @if($userStory->description)
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                    <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $userStory->description }}</div>
                </div>
            @endif

            @if($userStory->acceptance_criteria)
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Critères d'acceptation</h3>
                    <div class="whitespace-pre-wrap font-mono text-sm" style="color: var(--o-text-2);">{{ $userStory->acceptance_criteria }}</div>
                </div>
            @endif

            {{-- Requirements --}}
            <div class="surface p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium uppercase tracking-wider" style="color: var(--o-text-4);">
                        Requirements ({{ $userStory->requirements->count() }})
                    </h3>
                    @can('update', $project)
                        <a href="{{ route('projects.requirements.create', $project) }}?user_story_id={{ $userStory->id }}&module_id={{ $userStory->module_id }}"
                           class="text-xs font-medium" style="color: var(--o-accent);">+ Requirement</a>
                    @endcan
                </div>
                @forelse($userStory->requirements as $req)
                    <div class="flex items-center justify-between py-2" style="border-bottom: 1px solid var(--o-border);">
                        <div class="flex items-center gap-3 min-w-0">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}"
                               class="font-mono text-sm font-medium shrink-0" style="color: var(--o-accent);">{{ $req->ref }}</a>
                            <span class="text-sm truncate" style="color: var(--o-text-2);">{{ $req->title }}</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <x-project.vv-status :status="$req->vv_status" />
                            <x-project.risk-score :score="$req->risk_score" />
                        </div>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--o-text-4);">Aucun requirement lié.</p>
                @endforelse
            </div>

            {{-- Tasks --}}
            <div class="surface p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium uppercase tracking-wider" style="color: var(--o-text-4);">
                        Tâches ({{ $userStory->tasks->count() }})
                    </h3>
                    @can('update', $project)
                        <a href="{{ route('projects.tasks.create', $project) }}?user_story_id={{ $userStory->id }}&module_id={{ $userStory->module_id }}"
                           class="text-xs font-medium" style="color: var(--o-accent);">+ Tâche</a>
                    @endcan
                </div>
                @forelse($userStory->tasks as $task)
                    @php $taskStatusColors = ['done' => 'green', 'in_progress' => 'blue', 'todo' => 'gray', 'blocked' => 'red']; @endphp
                    <div class="flex items-center justify-between py-2" style="border-bottom: 1px solid var(--o-border);">
                        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-sm hover:opacity-70" style="color: var(--o-text-2);">{{ $task->title }}</a>
                        <x-ui.badge :color="$taskStatusColors[$task->status] ?? 'gray'">{{ $task->status }}</x-ui.badge>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--o-text-4);">Aucune tâche directe.</p>
                @endforelse
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            @php $adv = $userStory->advancement; @endphp
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Avancement</h3>
                <div class="text-center mb-3">
                    <div class="text-3xl font-bold font-mono" style="color: {{ $adv['percentage'] === 100 ? 'var(--o-green)' : 'var(--o-accent)' }};">{{ $adv['percentage'] }}%</div>
                    <div class="text-xs" style="color: var(--o-text-4);">{{ $adv['validated'] }}/{{ $adv['total'] }} REQ validées</div>
                </div>
                <x-ui.progress-bar :value="$adv['verified']" :max="$adv['total']" color="blue" class="mb-1" />
                <div class="flex justify-between text-[10px]" style="color: var(--o-text-4);">
                    <span>{{ $adv['verified'] }} vérifiées</span>
                    <span>{{ $adv['validated'] }} validées</span>
                </div>
            </div>

            <div class="surface p-5 space-y-3">
                <div>
                    <span class="text-xs" style="color: var(--o-text-4);">Module</span>
                    <div class="text-sm" style="color: var(--o-text);">{{ $userStory->module?->name ?? '—' }}</div>
                </div>
                <div>
                    <span class="text-xs" style="color: var(--o-text-4);">Assignée à</span>
                    <div class="text-sm" style="color: var(--o-text);">{{ $userStory->assignee?->name ?? 'Non assignée' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
