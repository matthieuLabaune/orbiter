<div>
    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-4 mb-4">
        <div class="relative">
            <x-lucide-search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..."
                   class="bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg pl-9 pr-3 py-1.5 text-sm text-gray-900 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 w-52">
        </div>

        <select wire:model.live="moduleFilter"
                class="bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-1.5 text-sm text-gray-900 dark:text-white focus:border-blue-500">
            <option value="">Tous les modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter"
                class="bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-1.5 text-sm text-gray-900 dark:text-white focus:border-blue-500">
            <option value="">Tous les statuts</option>
            @foreach(['untested' => 'Non testé', 'in_test' => 'En test', 'verified' => 'Vérifié', 'validated' => 'Validé', 'failed' => 'Échoué'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>

        <select wire:model.live="priorityFilter"
                class="bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-1.5 text-sm text-gray-900 dark:text-white focus:border-blue-500">
            <option value="">Toutes priorités</option>
            @foreach(['P0', 'P1', 'P2', 'P3'] as $p)
                <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
        </select>

        @if($search || $moduleFilter || $statusFilter || $priorityFilter)
            <button wire:click="$set('search', ''); $set('moduleFilter', ''); $set('statusFilter', ''); $set('priorityFilter', '')"
                    class="text-xs text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 cursor-pointer">
                Effacer les filtres
            </button>
        @endif

        <span class="text-xs text-gray-300 dark:text-slate-600 ml-auto">
            {{ $requirements->count() }} résultat(s)
        </span>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 dark:text-slate-400 uppercase bg-gray-50 dark:bg-slate-800/50">
                <tr>
                    @foreach([
                        'ref' => 'Ref',
                        'title' => 'Titre',
                        'module' => 'Module',
                        'priority' => 'Priorité',
                        'vv_status' => 'V&V',
                        'risk' => 'Risque',
                        'tests' => 'Tests',
                    ] as $col => $label)
                        <th class="px-4 py-3">
                            @if(in_array($col, ['ref', 'title', 'priority', 'vv_status']))
                                <button wire:click="sort('{{ $col }}')" class="flex items-center gap-1 hover:text-white transition-colors cursor-pointer">
                                    {{ $label }}
                                    @if($sortBy === $col)
                                        <x-dynamic-component :component="$sortDir === 'asc' ? 'lucide-chevron-up' : 'lucide-chevron-down'" class="w-3 h-3" />
                                    @endif
                                </button>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                @forelse($requirements as $req)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors" wire:key="req-{{ $req->id }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}"
                               class="font-mono text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">{{ $req->ref }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-800 dark:text-slate-200">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}" class="hover:text-white">
                                {{ $req->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $req->module?->name }}</td>
                        <td class="px-4 py-3">
                            @php $pColors = ['P0' => 'red', 'P1' => 'amber', 'P2' => 'blue', 'P3' => 'slate']; @endphp
                            <x-ui.badge :color="$pColors[$req->priority] ?? 'slate'">{{ $req->priority }}</x-ui.badge>
                        </td>
                        <td class="px-4 py-3"><x-project.vv-status :status="$req->vv_status" /></td>
                        <td class="px-4 py-3"><x-project.risk-score :score="$req->risk_score" /></td>
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $req->tests->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 dark:text-slate-500">Aucune exigence trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($requirements->hasPages())
        <div class="mt-4">
            {{ $requirements->links() }}
        </div>
    @endif
</div>
