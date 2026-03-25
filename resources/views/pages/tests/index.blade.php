<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Tests & V&V</h2>
            <a href="{{ route('projects.tests.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouveau test
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3">Ref</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Exigences couvertes</th>
                        <th class="px-4 py-3">Dernier résultat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($tests as $test)
                        @php
                            $lastExec = $test->executions->first();
                            $typeColors = ['manual' => 'slate', 'automated' => 'blue', 'review' => 'purple'];
                            $resultColors = ['pass' => 'emerald', 'fail' => 'red', 'skip' => 'amber'];
                        @endphp
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('projects.tests.show', [$project, $test]) }}"
                                   class="font-mono text-blue-400 hover:text-blue-300">{{ $test->ref }}</a>
                            </td>
                            <td class="px-4 py-3 text-slate-200">
                                <a href="{{ route('projects.tests.show', [$project, $test]) }}" class="hover:text-white">{{ $test->title }}</a>
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge :color="$typeColors[$test->type] ?? 'slate'">{{ $test->type }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($test->requirements as $req)
                                        <span class="text-xs font-mono text-slate-400">{{ $req->ref }}</span>
                                    @endforeach
                                    @if($test->requirements->isEmpty())
                                        <span class="text-xs text-slate-600">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($lastExec)
                                    <x-ui.badge :color="$resultColors[$lastExec->result] ?? 'slate'">{{ $lastExec->result }}</x-ui.badge>
                                @else
                                    <span class="text-xs text-slate-600">Non exécuté</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucun test.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
