<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">Exigences</h2>
            <a href="{{ route('projects.requirements.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouvelle exigence
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                   class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 w-48">

            <select name="module" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>
                        {{ $module->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les statuts</option>
                @foreach(['untested' => 'Non testé', 'in_test' => 'En test', 'verified' => 'Vérifié', 'validated' => 'Validé', 'failed' => 'Échoué'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="priority" class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Toutes priorités</option>
                @foreach(['P0', 'P1', 'P2', 'P3'] as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-lg transition-colors">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'module', 'status', 'priority']))
                <a href="{{ route('projects.requirements.index', $project) }}" class="text-xs text-slate-500 hover:text-slate-300">
                    Effacer
                </a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3">Ref</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Module</th>
                        <th class="px-4 py-3">Priorité</th>
                        <th class="px-4 py-3">V&V</th>
                        <th class="px-4 py-3">Risque</th>
                        <th class="px-4 py-3">Tests</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($requirements as $req)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('projects.requirements.show', [$project, $req]) }}"
                                   class="font-mono text-blue-400 hover:text-blue-300">
                                    {{ $req->ref }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-slate-200">
                                <a href="{{ route('projects.requirements.show', [$project, $req]) }}" class="hover:text-white">
                                    {{ $req->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-slate-400">{{ $req->module?->name }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $pColors = ['P0' => 'red', 'P1' => 'amber', 'P2' => 'blue', 'P3' => 'slate'];
                                @endphp
                                <x-ui.badge :color="$pColors[$req->priority] ?? 'slate'">{{ $req->priority }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <x-project.vv-status :status="$req->vv_status" />
                            </td>
                            <td class="px-4 py-3">
                                <x-project.risk-score :score="$req->risk_score" />
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-slate-400">{{ $req->tests->count() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                Aucune exigence trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
