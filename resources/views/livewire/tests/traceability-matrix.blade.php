<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <select wire:model.live="moduleFilter"
                    class="bg-slate-800 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500">
                <option value="">Tous les modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-500">
                Couverture : <span class="text-white font-mono">{{ $coveredReqs }}/{{ $totalReqs }}</span>
                @if($totalReqs > 0)
                    <span class="text-slate-400">({{ round(($coveredReqs / $totalReqs) * 100) }}%)</span>
                @endif
            </span>
        </div>
    </div>

    {{-- Matrix --}}
    @if($tests->isEmpty() || $matrix->isEmpty())
        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-8 text-center text-slate-500">
            Pas assez de données pour la matrice de traçabilité.
        </div>
    @else
        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-x-auto">
            <table class="text-xs">
                <thead>
                    <tr class="bg-slate-800/50">
                        <th class="px-3 py-2 text-left text-slate-400 font-medium sticky left-0 bg-slate-800/50 min-w-[200px]">
                            REQ / TEST
                        </th>
                        @foreach($tests as $test)
                            <th class="px-2 py-2 text-center min-w-[60px]">
                                <a href="{{ route('projects.tests.show', [$project, $test]) }}"
                                   class="font-mono text-blue-400 hover:text-blue-300 whitespace-nowrap">
                                    {{ $test->ref }}
                                </a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @foreach($matrix as $row)
                        <tr class="hover:bg-slate-800/20">
                            <td class="px-3 py-2 sticky left-0 bg-slate-900/80">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('projects.requirements.show', [$project, $row['requirement']]) }}"
                                       class="font-mono text-blue-400 hover:text-blue-300">{{ $row['requirement']->ref }}</a>
                                    <span class="text-slate-500 truncate max-w-[120px]">{{ $row['requirement']->title }}</span>
                                    <x-project.vv-status :status="$row['requirement']->vv_status" />
                                </div>
                            </td>
                            @foreach($row['cells'] as $cell)
                                <td class="px-2 py-2 text-center">
                                    @if($cell['linked'])
                                        @php
                                            $bg = match($cell['result']) {
                                                'pass' => 'bg-emerald-500/30 text-emerald-400',
                                                'fail' => 'bg-red-500/30 text-red-400',
                                                'skip' => 'bg-amber-500/30 text-amber-400',
                                                default => 'bg-blue-500/20 text-blue-400',
                                            };
                                            $icon = match($cell['result']) {
                                                'pass' => '✓',
                                                'fail' => '✗',
                                                'skip' => '—',
                                                default => '○',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded {{ $bg }} text-[10px] font-bold">
                                            {{ $icon }}
                                        </span>
                                    @else
                                        <span class="text-slate-700">·</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-emerald-500/30 text-emerald-400 text-center text-[10px] font-bold leading-4">✓</span> Pass</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-red-500/30 text-red-400 text-center text-[10px] font-bold leading-4">✗</span> Fail</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-amber-500/30 text-amber-400 text-center text-[10px] font-bold leading-4">—</span> Skip</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-blue-500/20 text-blue-400 text-center text-[10px] font-bold leading-4">○</span> Lié, non exécuté</span>
            <span class="flex items-center gap-1"><span class="text-slate-700">·</span> Non lié</span>
        </div>
    @endif
</div>
