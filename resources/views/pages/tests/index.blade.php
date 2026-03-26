<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Tests & V&V</h2>
            <a href="{{ route('projects.tests.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 btn-primary transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouveau test
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4" x-data="{ tab: 'list' }">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="flex items-center gap-1 border-b" style="border-color: var(--o-border);">
            <button @click="tab = 'list'" :class="tab === 'list' ? 'text-gray-900 dark:text-white border-b-2 border-blue-500' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer">
                Liste
            </button>
            <button @click="tab = 'matrix'" :class="tab === 'matrix' ? 'text-gray-900 dark:text-white border-b-2 border-blue-500' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'"
                    class="px-4 py-2 text-sm font-medium transition-colors cursor-pointer">
                Matrice de traçabilité
            </button>
        </div>

        {{-- List tab --}}
        <div x-show="tab === 'list'">
            <div class="surface overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase" style="background: var(--o-surface-2); color: var(--o-text-4);">
                        <tr>
                            <th class="px-4 py-3">Ref</th>
                            <th class="px-4 py-3">Titre</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Exigences couvertes</th>
                            <th class="px-4 py-3">Dernier résultat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--o-border);">
                        @forelse($tests as $test)
                            @php
                                $lastExec = $test->executions->first();
                                $typeColors = ['manual' => 'slate', 'automated' => 'blue', 'review' => 'purple'];
                                $resultColors = ['pass' => 'emerald', 'fail' => 'red', 'skip' => 'amber'];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.tests.show', [$project, $test]) }}"
                                       class="font-mono" style="color: var(--o-accent);">{{ $test->ref }}</a>
                                </td>
                                <td class="px-4 py-3" style="color: var(--o-text-2);">
                                    <a href="{{ route('projects.tests.show', [$project, $test]) }}" class="hover:text-gray-900 dark:hover:text-white">{{ $test->title }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :color="$typeColors[$test->type] ?? 'slate'">{{ $test->type }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($test->requirements as $req)
                                            <span class="text-xs font-mono" style="color: var(--o-accent);">{{ $req->ref }}</span>
                                        @endforeach
                                        @if($test->requirements->isEmpty())
                                            <span class="text-xs text-gray-400 dark:text-slate-600">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($lastExec)
                                        <x-ui.badge :color="$resultColors[$lastExec->result] ?? 'slate'">{{ $lastExec->result }}</x-ui.badge>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-slate-600">Non exécuté</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center" style="color: var(--o-text-4);">Aucun test.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Matrix tab --}}
        <div x-show="tab === 'matrix'" x-cloak>
            <livewire:tests.traceability-matrix :project="$project" />
        </div>
    </div>
</x-app-layout>
