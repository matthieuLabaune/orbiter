<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Anomalies</h2>
            <a href="{{ route('projects.anomalies.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
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
                <x-lucide-shield-check class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-600 dark:text-slate-300 mb-2">Aucune anomalie</h3>
                <p class="text-gray-400 dark:text-slate-500">Signalez les non-conformites, anomalies et defauts.</p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 dark:text-slate-400 uppercase bg-gray-50 dark:bg-slate-800/50">
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
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @foreach($anomalies as $anomaly)
                            @php
                                $typeColors = ['anomaly' => 'amber', 'non_conformity' => 'red', 'defect' => 'red'];
                                $typeLabels = ['anomaly' => 'Anomalie', 'non_conformity' => 'Non-conformite', 'defect' => 'Defaut'];
                                $severityColors = ['low' => 'slate', 'medium' => 'amber', 'high' => 'red', 'critical' => 'red'];
                                $severityLabels = ['low' => 'Faible', 'medium' => 'Moyen', 'high' => 'Eleve', 'critical' => 'Critique'];
                                $statusColors = ['open' => 'amber', 'investigating' => 'blue', 'resolved' => 'emerald', 'closed' => 'slate'];
                                $statusLabels = ['open' => 'Ouvert', 'investigating' => 'Investigation', 'resolved' => 'Resolu', 'closed' => 'Ferme'];
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3">
                                    <a href="{{ route('projects.anomalies.show', [$project, $anomaly]) }}"
                                       class="font-mono text-blue-500 dark:text-blue-400 hover:text-blue-400 dark:hover:text-blue-300">{{ $anomaly->ref }}</a>
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-slate-200">
                                    <a href="{{ route('projects.anomalies.show', [$project, $anomaly]) }}"
                                       class="hover:text-blue-500 dark:hover:text-blue-400 transition-colors">{{ $anomaly->title }}</a>
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
                                <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $anomaly->module?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $anomaly->assignee?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
