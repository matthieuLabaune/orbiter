<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.tests.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-lg" style="color: var(--o-accent);">{{ $test->ref }}</span>
                        <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $test->title }}</h2>
                    </div>
                    @php $typeColors = ['manual' => 'slate', 'automated' => 'blue', 'review' => 'purple']; @endphp
                    <x-ui.badge :color="$typeColors[$test->type] ?? 'slate'" class="mt-1">{{ $test->type }}</x-ui.badge>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('record-execution').showModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                    <x-lucide-play class="w-4 h-4" />
                    Enregistrer une exécution
                </button>
                @can('update', $project)
                    <a href="{{ route('projects.tests.edit', [$project, $test]) }}"
                       class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                        <x-lucide-pencil class="w-4 h-4" />
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Procedure --}}
                @if($test->procedure)
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Procédure</h3>
                        <div class="whitespace-pre-wrap font-mono text-sm" style="color: var(--o-text-2);">{{ $test->procedure }}</div>
                    </div>
                @endif

                {{-- Expected result --}}
                @if($test->expected_result)
                    <div class="surface p-5">
                        <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Résultat attendu</h3>
                        <div style="color: var(--o-text-2);">{{ $test->expected_result }}</div>
                    </div>
                @endif

                {{-- Execution history --}}
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">
                        Historique des exécutions ({{ $test->executions->count() }})
                    </h3>
                    @if($test->executions->isEmpty())
                        <p class="text-sm" style="color: var(--o-text-4);">Aucune exécution enregistrée.</p>
                    @else
                        <table class="w-full text-sm">
                            <thead class="text-xs uppercase" style="background: var(--o-surface-2); color: var(--o-text-4);">
                                <tr>
                                    <th class="text-left py-2">Date</th>
                                    <th class="text-left py-2">Résultat</th>
                                    <th class="text-left py-2">Exécuté par</th>
                                    <th class="text-left py-2">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--o-border);">
                                @foreach($test->executions->sortByDesc('executed_at') as $exec)
                                    @php $resultColors = ['pass' => 'emerald', 'fail' => 'red', 'skip' => 'amber']; @endphp
                                    <tr>
                                        <td class="py-2 text-xs" style="color: var(--o-text-4);">{{ $exec->executed_at?->format('d/m/Y H:i') }}</td>
                                        <td class="py-2">
                                            <x-ui.badge :color="$resultColors[$exec->result] ?? 'slate'">{{ $exec->result }}</x-ui.badge>
                                        </td>
                                        <td class="py-2" style="color: var(--o-text-4);">{{ $exec->executor?->name ?? '—' }}</td>
                                        <td class="py-2 text-xs" style="color: var(--o-text-4);">{{ $exec->notes ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="surface p-5">
                    <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Exigences couvertes</h3>
                    @forelse($test->requirements as $req)
                        <div class="flex items-center gap-2 py-1.5">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}"
                               class="font-mono text-sm" style="color: var(--o-accent);">{{ $req->ref }}</a>
                            <span class="text-sm truncate" style="color: var(--o-text-2);">{{ $req->title }}</span>
                            <x-project.vv-status :status="$req->vv_status" />
                        </div>
                    @empty
                        <p class="text-xs" style="color: var(--o-text-4);">Aucune exigence liée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Record execution dialog --}}
        <x-ui.dialog id="record-execution" title="Enregistrer une exécution">
            <form action="{{ route('projects.tests.executions.store', [$project, $test]) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--o-text-4);">Résultat</label>
                    <div class="flex gap-3">
                        @foreach(['pass' => 'Pass', 'fail' => 'Fail', 'skip' => 'Skip'] as $val => $label)
                            @php
                                $colors = [
                                    'pass' => 'border-emerald-500 bg-emerald-500/10 text-emerald-400 peer-checked:bg-emerald-500/30',
                                    'fail' => 'border-red-500 bg-red-500/10 text-red-400 peer-checked:bg-red-500/30',
                                    'skip' => 'border-amber-500 bg-amber-500/10 text-amber-400 peer-checked:bg-amber-500/30',
                                ];
                            @endphp
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="result" value="{{ $val }}" required class="peer sr-only" {{ $val === 'pass' ? 'checked' : '' }}>
                                <div class="text-center py-2 rounded-lg border {{ $colors[$val] }} font-medium text-sm transition-colors">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full input-field text-sm"
                              placeholder="Détails de l'exécution..."></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 btn-primary transition-colors">
                    Enregistrer
                </button>
            </form>
        </x-ui.dialog>
    </div>
</x-app-layout>
