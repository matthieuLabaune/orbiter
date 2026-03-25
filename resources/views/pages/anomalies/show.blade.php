<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.anomalies.index', $project) }}" class="text-gray-400 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <div>
                @php
                    $typeColors = ['anomaly' => 'amber', 'non_conformity' => 'red', 'defect' => 'red'];
                    $typeLabels = ['anomaly' => 'Anomalie', 'non_conformity' => 'Non-conformite', 'defect' => 'Defaut'];
                    $severityColors = ['low' => 'slate', 'medium' => 'amber', 'high' => 'red', 'critical' => 'red'];
                    $severityLabels = ['low' => 'Faible', 'medium' => 'Moyen', 'high' => 'Eleve', 'critical' => 'Critique'];
                    $statusColors = ['open' => 'amber', 'investigating' => 'blue', 'resolved' => 'emerald', 'closed' => 'slate'];
                    $statusLabels = ['open' => 'Ouvert', 'investigating' => 'Investigation', 'resolved' => 'Resolu', 'closed' => 'Ferme'];
                @endphp
                <div class="flex items-center gap-2">
                    <span class="font-mono text-blue-500 dark:text-blue-400">{{ $anomaly->ref }}</span>
                    <x-ui.badge :color="$typeColors[$anomaly->type] ?? 'slate'">{{ $typeLabels[$anomaly->type] ?? $anomaly->type }}</x-ui.badge>
                    <x-ui.badge :color="$severityColors[$anomaly->severity] ?? 'slate'">{{ $severityLabels[$anomaly->severity] ?? $anomaly->severity }}</x-ui.badge>
                    <x-ui.badge :color="$statusColors[$anomaly->status] ?? 'slate'">{{ $statusLabels[$anomaly->status] ?? $anomaly->status }}</x-ui.badge>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $anomaly->title }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Non-conformity warning --}}
        @if($anomaly->type === 'non_conformity' && $anomaly->requirement)
            <div class="px-4 py-3 bg-red-500/5 border border-red-500/20 rounded-lg">
                <div class="flex items-center gap-2 text-sm text-red-500 dark:text-red-400">
                    <x-lucide-alert-triangle class="w-4 h-4 flex-shrink-0" />
                    <span>Cette non-conformite a impacte le statut V&V de l'exigence <span class="font-mono">{{ $anomaly->requirement->ref }}</span> (marque comme echoue).</span>
                </div>
            </div>
        @endif

        {{-- Meta --}}
        <div class="text-xs text-gray-400 dark:text-slate-500 flex items-center gap-3">
            <span>Cree le {{ $anomaly->created_at->format('d/m/Y') }}</span>
            @if($anomaly->resolved_at)
                <span class="text-gray-300 dark:text-slate-700">·</span>
                <span>Resolu le {{ $anomaly->resolved_at->format('d/m/Y') }}</span>
            @endif
            @if($anomaly->module)
                <span class="text-gray-300 dark:text-slate-700">·</span>
                <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded">{{ $anomaly->module->name }}</span>
            @endif
            @if($anomaly->assignee)
                <span class="text-gray-300 dark:text-slate-700">·</span>
                <span>Assigne a {{ $anomaly->assignee->name }}</span>
            @endif
        </div>

        {{-- Description --}}
        @if($anomaly->description)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                <div class="text-gray-600 dark:text-slate-300 whitespace-pre-wrap">{{ $anomaly->description }}</div>
            </div>
        @endif

        {{-- Linked requirement --}}
        @if($anomaly->requirement)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Exigence liee</h3>
                <a href="{{ route('projects.requirements.show', [$project, $anomaly->requirement]) }}"
                   class="inline-flex items-center gap-2 text-blue-500 dark:text-blue-400 hover:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <span class="font-mono text-sm">{{ $anomaly->requirement->ref }}</span>
                    <span class="text-gray-600 dark:text-slate-300">{{ $anomaly->requirement->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif

        {{-- Linked lesson --}}
        @if($anomaly->lesson)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Lesson learned</h3>
                <a href="{{ route('projects.lessons.show', [$project, $anomaly->lesson]) }}"
                   class="inline-flex items-center gap-2 text-blue-500 dark:text-blue-400 hover:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <span class="font-mono text-sm">{{ $anomaly->lesson->ref }}</span>
                    <span class="text-gray-600 dark:text-slate-300">{{ $anomaly->lesson->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
