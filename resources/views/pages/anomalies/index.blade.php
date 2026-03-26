<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Anomalies</h2>
            <a href="{{ route('projects.anomalies.create', $project) }}"
               class="btn-primary">
                <x-lucide-plus class="w-4 h-4" />
                Signaler
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($anomalies->isEmpty())
            <div class="text-center py-16">
                <x-lucide-shield-check class="w-12 h-12 mx-auto mb-4" style="color: var(--o-text-4);" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucune anomalie</h3>
                <p style="color: var(--o-text-4);">Signalez les non-conformites, anomalies et defauts.</p>
            </div>
        @else
            <div class="surface overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase" style="background: var(--o-surface-2); color: var(--o-text-4);">
                        <tr>
                            <th class="px-4 py-3">Ref</th>
                            <th class="px-4 py-3">Titre</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Severite</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Module</th>
                            <th class="px-4 py-3">Assigne</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="--tw-divide-color: var(--o-border);">
                        @foreach($anomalies as $anomaly)
                            @php
                                $typeColors = ['anomaly' => 'amber', 'non_conformity' => 'red', 'defect' => 'red'];
                                $typeLabels = ['anomaly' => 'Anomalie', 'non_conformity' => 'Non-conformite', 'defect' => 'Defaut'];
                                $severityColors = ['low' => 'slate', 'medium' => 'amber', 'high' => 'red', 'critical' => 'red'];
                                $severityLabels = ['low' => 'Faible', 'medium' => 'Moyen', 'high' => 'Eleve', 'critical' => 'Critique'];
                                $statusColors = ['open' => 'amber', 'investigating' => 'blue', 'resolved' => 'emerald', 'closed' => 'slate'];
                                $statusLabels = ['open' => 'Ouvert', 'investigating' => 'Investigation', 'resolved' => 'Resolu', 'closed' => 'Ferme'];
                            @endphp
                            <tr class="transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.anomalies.show', [$project, $anomaly]) }}"
                                       class="font-mono" style="color: var(--o-accent);">{{ $anomaly->ref }}</a>
                                </td>
                                <td class="px-4 py-3" style="color: var(--o-text);">
                                    <a href="{{ route('projects.anomalies.show', [$project, $anomaly]) }}"
                                       class="transition-colors" style="color: var(--o-text);">{{ $anomaly->title }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :color="$typeColors[$anomaly->type] ?? 'slate'">{{ $typeLabels[$anomaly->type] ?? $anomaly->type }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :color="$severityColors[$anomaly->severity] ?? 'slate'">{{ $severityLabels[$anomaly->severity] ?? $anomaly->severity }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :color="$statusColors[$anomaly->status] ?? 'slate'">{{ $statusLabels[$anomaly->status] ?? $anomaly->status }}</x-ui.badge>
                                </td>
                                <td class="px-4 py-3 text-xs" style="color: var(--o-text-4);">{{ $anomaly->module?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs" style="color: var(--o-text-4);">{{ $anomaly->assignee?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
