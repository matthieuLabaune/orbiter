<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.baselines.index', $project) }}" class="transition-colors" style="color: var(--o-text-4);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono font-semibold" style="color: var(--o-accent);">{{ $baseline->ref }}</span>
                    @if($baseline->is_immutable)
                        <x-ui.badge color="emerald">Immutable</x-ui.badge>
                    @endif
                </div>
                <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $baseline->title }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Meta --}}
        <div class="text-xs flex items-center gap-3" style="color: var(--o-text-4);">
            <span>Creee le {{ $baseline->created_at->format('d/m/Y H:i') }}</span>
            @if($baseline->signed_by)
                <span style="color: var(--o-border);">·</span>
                <span>Signe par {{ $baseline->signed_by }}</span>
            @endif
        </div>

        {{-- Description --}}
        @if($baseline->description)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $baseline->description }}</div>
            </div>
        @endif

        {{-- Snapshot summary --}}
        @if($baseline->snapshot)
            @php $snap = $baseline->snapshot; @endphp
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-4" style="color: var(--o-text-4);">Etat du projet</h3>

                {{-- Counters --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-3 rounded-lg" style="background: var(--o-surface-2);">
                        <div class="text-2xl font-bold font-mono" style="color: var(--o-text);">{{ $snap['requirements_count'] ?? 0 }}</div>
                        <div class="text-xs mt-1" style="color: var(--o-text-4);">Exigences</div>
                    </div>
                    <div class="text-center p-3 rounded-lg" style="background: var(--o-surface-2);">
                        <div class="text-2xl font-bold font-mono" style="color: var(--o-text);">{{ $snap['tests_count'] ?? 0 }}</div>
                        <div class="text-xs mt-1" style="color: var(--o-text-4);">Tests</div>
                    </div>
                    <div class="text-center p-3 rounded-lg" style="background: var(--o-surface-2);">
                        <div class="text-2xl font-bold font-mono" style="color: var(--o-text);">{{ $snap['modules_count'] ?? 0 }}</div>
                        <div class="text-xs mt-1" style="color: var(--o-text-4);">Modules</div>
                    </div>
                    <div class="text-center p-3 rounded-lg" style="background: var(--o-surface-2);">
                        <div class="text-2xl font-bold font-mono" style="color: var(--o-text);">{{ $snap['adrs_count'] ?? 0 }}</div>
                        <div class="text-xs mt-1" style="color: var(--o-text-4);">ADRs</div>
                    </div>
                </div>

                {{-- Requirements by status --}}
                @if(isset($snap['requirements_by_status']) && count($snap['requirements_by_status']) > 0)
                    <div class="mb-6">
                        <h4 class="text-xs uppercase tracking-wider mb-2" style="color: var(--o-text-4);">Exigences par statut V&V</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $vvColors = ['draft' => 'slate', 'to_verify' => 'amber', 'verified' => 'blue', 'validated' => 'emerald', 'failed' => 'red'];
                            @endphp
                            @foreach($snap['requirements_by_status'] as $status => $count)
                                <x-ui.badge :color="$vvColors[$status] ?? 'slate'">{{ $status }}: {{ $count }}</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tasks summary --}}
                @if(isset($snap['tasks_summary']) && count($snap['tasks_summary']) > 0)
                    <div class="mb-6">
                        <h4 class="text-xs uppercase tracking-wider mb-2" style="color: var(--o-text-4);">Taches</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $taskColors = ['todo' => 'slate', 'in_progress' => 'blue', 'done' => 'emerald', 'blocked' => 'red'];
                            @endphp
                            @foreach($snap['tasks_summary'] as $status => $count)
                                <x-ui.badge :color="$taskColors[$status] ?? 'slate'">{{ $status }}: {{ $count }}</x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Coverage bars --}}
                @if(isset($snap['coverage']))
                    @php
                        $cov = $snap['coverage'];
                        $total = $cov['total'] ?? 0;
                    @endphp
                    <div class="space-y-3 mb-6">
                        <h4 class="text-xs uppercase tracking-wider" style="color: var(--o-text-4);">Couverture</h4>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span style="color: var(--o-text-4);">Tests lies</span>
                                <span class="font-mono" style="color: var(--o-text-4);">{{ $cov['covered'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['covered'] ?? 0" :max="max($total, 1)" color="blue" :showLabel="false" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span style="color: var(--o-text-4);">Verifiees</span>
                                <span class="font-mono" style="color: var(--o-text-4);">{{ $cov['verified'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['verified'] ?? 0" :max="max($total, 1)" color="emerald" :showLabel="false" />
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span style="color: var(--o-text-4);">Validees</span>
                                <span class="font-mono" style="color: var(--o-text-4);">{{ $cov['validated'] ?? 0 }}/{{ $total }}</span>
                            </div>
                            <x-ui.progress-bar :value="$cov['validated'] ?? 0" :max="max($total, 1)" color="emerald" :showLabel="false" />
                        </div>
                    </div>
                @endif

                {{-- Anomalies open --}}
                @if(isset($snap['anomalies_open']))
                    <div class="flex items-center gap-2">
                        <span class="text-xs" style="color: var(--o-text-4);">Anomalies ouvertes :</span>
                        <span class="text-sm font-mono {{ ($snap['anomalies_open'] ?? 0) > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                            {{ $snap['anomalies_open'] }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Raw snapshot JSON --}}
            <details class="surface overflow-hidden">
                <summary class="px-5 py-3 text-sm font-medium cursor-pointer transition-colors" style="color: var(--o-text-4);">
                    Snapshot JSON brut
                </summary>
                <div class="px-5 pb-5">
                    <pre class="text-xs rounded-lg p-4 overflow-x-auto font-mono" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ json_encode($snap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </details>
        @endif
    </div>
</x-app-layout>
