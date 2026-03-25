<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.requirements.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-blue-400 text-lg">{{ $requirement->ref }}</span>
                        <h2 class="text-xl font-semibold text-white">{{ $requirement->title }}</h2>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs text-slate-500">{{ $requirement->module?->name }}</span>
                        <span class="text-slate-700">·</span>
                        @php $pColors = ['P0' => 'red', 'P1' => 'amber', 'P2' => 'blue', 'P3' => 'slate']; @endphp
                        <x-ui.badge :color="$pColors[$requirement->priority] ?? 'slate'">{{ $requirement->priority }}</x-ui.badge>
                        <x-project.vv-status :status="$requirement->vv_status" />
                        <span class="text-xs text-slate-500 font-mono">v{{ $requirement->version }}</span>
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
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                        <div class="text-slate-300 whitespace-pre-wrap">{{ $requirement->description }}</div>
                    </div>
                @endif

                {{-- Acceptance criteria --}}
                @if($requirement->acceptance_criteria)
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Critères d'acceptation</h3>
                        <div class="text-slate-300 whitespace-pre-wrap font-mono text-sm">{{ $requirement->acceptance_criteria }}</div>
                    </div>
                @endif

                {{-- Tests --}}
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Tests liés</h3>
                    @if($requirement->tests->isEmpty())
                        <p class="text-slate-500 text-sm">Aucun test lié à cette exigence.</p>
                    @else
                        <table class="w-full text-sm">
                            <thead class="text-xs text-slate-500 uppercase">
                                <tr>
                                    <th class="text-left py-2">Ref</th>
                                    <th class="text-left py-2">Titre</th>
                                    <th class="text-left py-2">Type</th>
                                    <th class="text-left py-2">Dernier résultat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50">
                                @foreach($requirement->tests as $test)
                                    @php
                                        $lastExec = $test->executions->sortByDesc('executed_at')->first();
                                        $resultColors = ['pass' => 'emerald', 'fail' => 'red', 'skip' => 'amber'];
                                    @endphp
                                    <tr>
                                        <td class="py-2 font-mono text-blue-400">{{ $test->ref }}</td>
                                        <td class="py-2 text-slate-300">{{ $test->title }}</td>
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
                                                <span class="text-xs text-slate-500">—</span>
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
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Historique des versions</h3>
                        <div class="space-y-3">
                            @foreach($requirement->versions->sortByDesc('version') as $version)
                                <div class="border-l-2 border-slate-700 pl-4">
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <span class="font-mono">v{{ $version->version }}</span>
                                        <span>·</span>
                                        <span>{{ $version->created_at->format('d/m/Y H:i') }}</span>
                                        @if($version->changedBy)
                                            <span>·</span>
                                            <span>{{ $version->changedBy->name }}</span>
                                        @endif
                                    </div>
                                    @if($version->change_reason)
                                        <p class="text-sm text-slate-400 mt-1">{{ $version->change_reason }}</p>
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
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Score de risque</h3>
                    <div class="text-center mb-4">
                        <x-project.risk-score :score="$requirement->risk_score" class="text-lg" />
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div>
                            <div class="text-slate-500">Impact</div>
                            <div class="text-white font-mono">{{ $requirement->risk_impact ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Probabilité</div>
                            <div class="text-white font-mono">{{ $requirement->risk_probability ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Détectabilité</div>
                            <div class="text-white font-mono">{{ $requirement->risk_detectability ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tasks --}}
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Tâches ({{ $requirement->tasks->count() }})</h3>
                    @forelse($requirement->tasks as $task)
                        <div class="flex items-center justify-between py-1.5 text-sm">
                            <span class="text-slate-300 truncate">{{ $task->title }}</span>
                            @php
                                $statusColors = ['done' => 'emerald', 'in_progress' => 'blue', 'todo' => 'slate', 'blocked' => 'red'];
                            @endphp
                            <x-ui.badge :color="$statusColors[$task->status] ?? 'slate'">{{ $task->status }}</x-ui.badge>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">Aucune tâche liée.</p>
                    @endforelse
                </div>

                {{-- Lessons Learned --}}
                @if($requirement->lessons->isNotEmpty())
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Lessons Learned</h3>
                        @foreach($requirement->lessons as $lesson)
                            <div class="py-2 border-b border-slate-700/50 last:border-0">
                                <div class="text-sm text-slate-300">{{ $lesson->title }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $lesson->ref }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Anomalies --}}
                @if($requirement->anomalies->isNotEmpty())
                    <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Anomalies</h3>
                        @foreach($requirement->anomalies as $anomaly)
                            <div class="py-2 border-b border-slate-700/50 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-mono text-red-400">{{ $anomaly->ref }}</span>
                                    <span class="text-sm text-slate-300">{{ $anomaly->title }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
