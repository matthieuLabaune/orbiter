<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.requirements.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-lg" style="color: var(--o-accent);">{{ $requirement->ref }}</span>
                        <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $requirement->title }}</h2>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs" style="color: var(--o-text-4);">{{ $requirement->module?->name }}</span>
                        <span class="text-slate-700">·</span>
                        @php $pColors = ['P0' => 'red', 'P1' => 'amber', 'P2' => 'blue', 'P3' => 'slate']; @endphp
                        <x-ui.badge :color="$pColors[$requirement->priority] ?? 'slate'">{{ $requirement->priority }}</x-ui.badge>
                        <x-project.vv-status :status="$requirement->vv_status" />
                        <span class="text-xs font-mono" style="color: var(--o-text-4);">v{{ $requirement->version }}</span>
                    </div>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.requirements.edit', [$project, $requirement]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    <x-lucide-pencil class="w-4 h-4" />
                    Modifier
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description --}}
                @if($requirement->description)
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                        <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $requirement->description }}</div>
                    </div>
                @endif

                {{-- Acceptance criteria --}}
                @if($requirement->acceptance_criteria)
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Critères d'acceptation</h3>
                        <div class="whitespace-pre-wrap font-mono text-sm" style="color: var(--o-text-2);">{{ $requirement->acceptance_criteria }}</div>
                    </div>
                @endif

                {{-- Tests --}}
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Tests liés</h3>
                    @if($requirement->tests->isEmpty())
                        <p class="text-sm" style="color: var(--o-text-4);">Aucun test lié à cette exigence.</p>
                    @else
                        <table class="w-full text-sm">
                            <thead class="text-xs uppercase" style="background: var(--o-surface-2); color: var(--o-text-4);">
                                <tr>
                                    <th class="text-left py-2">Ref</th>
                                    <th class="text-left py-2">Titre</th>
                                    <th class="text-left py-2">Type</th>
                                    <th class="text-left py-2">Dernier résultat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--o-border);">
                                @foreach($requirement->tests as $test)
                                    @php
                                        $lastExec = $test->executions->sortByDesc('executed_at')->first();
                                        $resultColors = ['pass' => 'emerald', 'fail' => 'red', 'skip' => 'amber'];
                                    @endphp
                                    <tr>
                                        <td class="py-2 font-mono" style="color: var(--o-accent);">{{ $test->ref }}</td>
                                        <td class="py-2" style="color: var(--o-text-2);">{{ $test->title }}</td>
                                        <td class="py-2">
                                            <x-ui.badge :color="$test->type === 'automated' ? 'blue' : ($test->type === 'review' ? 'purple' : 'slate')">
                                                {{ $test->type }}
                                            </x-ui.badge>
                                        </td>
                                        <td class="py-2">
                                            @if($lastExec)
                                                <x-ui.badge :color="$resultColors[$lastExec->result] ?? 'slate'">
                                                    {{ $lastExec->result }}
                                                </x-ui.badge>
                                            @else
                                                <span class="text-xs" style="color: var(--o-text-4);">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Version history --}}
                @if($requirement->versions->isNotEmpty())
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Historique des versions</h3>
                        <div class="space-y-3">
                            @foreach($requirement->versions->sortByDesc('version') as $version)
                                <div class="border-l-2 pl-4" style="border-color: var(--o-border);">
                                    <div class="flex items-center gap-2 text-xs" style="color: var(--o-text-4);">
                                        <span class="font-mono">v{{ $version->version }}</span>
                                        <span>·</span>
                                        <span>{{ $version->created_at->format('d/m/Y H:i') }}</span>
                                        @if($version->changedBy)
                                            <span>·</span>
                                            <span>{{ $version->changedBy->name }}</span>
                                        @endif
                                    </div>
                                    @if($version->change_reason)
                                        <p class="text-sm mt-1" style="color: var(--o-text-4);">{{ $version->change_reason }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Risk Score --}}
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Score de risque</h3>
                    <div class="text-center mb-4">
                        <x-project.risk-score :score="$requirement->risk_score" class="text-lg" />
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div>
                            <div style="color: var(--o-text-4);">Impact</div>
                            <div class="font-mono" style="color: var(--o-text);">{{ $requirement->risk_impact ?? '—' }}</div>
                        </div>
                        <div>
                            <div style="color: var(--o-text-4);">Probabilité</div>
                            <div class="font-mono" style="color: var(--o-text);">{{ $requirement->risk_probability ?? '—' }}</div>
                        </div>
                        <div>
                            <div style="color: var(--o-text-4);">Détectabilité</div>
                            <div class="font-mono" style="color: var(--o-text);">{{ $requirement->risk_detectability ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tasks --}}
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Tâches ({{ $requirement->tasks->count() }})</h3>
                    @forelse($requirement->tasks as $task)
                        <div class="flex items-center justify-between py-1.5 text-sm">
                            <span class="truncate" style="color: var(--o-text-2);">{{ $task->title }}</span>
                            @php
                                $statusColors = ['done' => 'emerald', 'in_progress' => 'blue', 'todo' => 'slate', 'blocked' => 'red'];
                            @endphp
                            <x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $task->status }}</x-ui.badge>
                        </div>
                    @empty
                        <p class="text-xs" style="color: var(--o-text-4);">Aucune tâche liée.</p>
                    @endforelse
                </div>

                {{-- Lessons Learned --}}
                @if($requirement->lessons->isNotEmpty())
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Lessons Learned</h3>
                        @foreach($requirement->lessons as $lesson)
                            <div class="py-2 border-b last:border-0" style="border-color: var(--o-border);">
                                <div class="text-sm" style="color: var(--o-text-2);">{{ $lesson->title }}</div>
                                <div class="text-xs font-mono" style="color: var(--o-text-4);">{{ $lesson->ref }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Anomalies --}}
                @if($requirement->anomalies->isNotEmpty())
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Anomalies</h3>
                        @foreach($requirement->anomalies as $anomaly)
                            <div class="py-2 border-b last:border-0" style="border-color: var(--o-border);">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-mono text-red-400">{{ $anomaly->ref }}</span>
                                    <span class="text-sm" style="color: var(--o-text-2);">{{ $anomaly->title }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
