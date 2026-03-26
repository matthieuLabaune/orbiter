<div>
    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3 surface p-4 mb-4">
        <div class="relative">
            <x-lucide-search class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500" />
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..."
                   class="input-field pl-9 pr-3 py-1.5 text-sm w-52">
        </div>

        <select wire:model.live="moduleFilter"
                class="input-field px-3 py-1.5 text-sm">
            <option value="">Tous les modules</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter"
                class="input-field px-3 py-1.5 text-sm">
            <option value="">Tous les statuts</option>
            @foreach(['untested' => 'Non testé', 'in_test' => 'En test', 'verified' => 'Vérifié', 'validated' => 'Validé', 'failed' => 'Échoué'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>

        <select wire:model.live="priorityFilter"
                class="input-field px-3 py-1.5 text-sm">
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

        <span class="text-xs ml-auto" style="color: var(--o-text-4);">
            {{ $requirements->count() }} résultat(s)
        </span>
    </div>

    {{-- Table --}}
    <div class="surface overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase" style="background: var(--o-surface-2); color: var(--o-text-4);">
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
            <tbody class="divide-y" style="border-color: var(--o-border);">
                @forelse($requirements as $req)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors" wire:key="req-{{ $req->id }}">
                        <td class="px-4 py-3">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}"
                               class="font-mono" style="color: var(--o-accent);">{{ $req->ref }}</a>
                        </td>
                        <td class="px-4 py-3" style="color: var(--o-text-2);">
                            <a href="{{ route('projects.requirements.show', [$project, $req]) }}" class="hover:text-white">
                                {{ $req->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color: var(--o-text-4);">{{ $req->module?->name }}</td>
                        <td class="px-4 py-3">
                            @php $pColors = ['P0' => 'red', 'P1' => 'amber', 'P2' => 'blue', 'P3' => 'slate']; @endphp
                            <x-ui.badge :color="$pColors[$req->priority] ?? 'slate'">{{ $req->priority }}</x-ui.badge>
                        </td>
                        <td class="px-4 py-3"><x-project.vv-status :status="$req->vv_status" /></td>
                        <td class="px-4 py-3"><x-project.risk-score :score="$req->risk_score" /></td>
                        <td class="px-4 py-3 text-xs" style="color: var(--o-text-4);">{{ $req->tests->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center" style="color: var(--o-text-4);">Aucune exigence trouvée.</td>
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
