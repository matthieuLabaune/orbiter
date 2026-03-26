<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.anomalies.index', $project) }}" class="transition-colors" style="color: var(--o-text-4);">
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
                    <span class="font-mono" style="color: var(--o-accent);">{{ $anomaly->ref }}</span>
                    <x-ui.badge :color="$typeColors[$anomaly->type] ?? 'slate'">{{ $typeLabels[$anomaly->type] ?? $anomaly->type }}</x-ui.badge>
                    <x-ui.badge :color="$severityColors[$anomaly->severity] ?? 'slate'">{{ $severityLabels[$anomaly->severity] ?? $anomaly->severity }}</x-ui.badge>
                    <x-ui.badge :color="$statusColors[$anomaly->status] ?? 'slate'">{{ $statusLabels[$anomaly->status] ?? $anomaly->status }}</x-ui.badge>
                </div>
                <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $anomaly->title }}</h2>
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
                <div class="flex items-center gap-2 text-sm text-red-400">
                    <x-lucide-alert-triangle class="w-4 h-4 flex-shrink-0" />
                    <span>Cette non-conformite a impacte le statut V&V de l'exigence <span class="font-mono">{{ $anomaly->requirement->ref }}</span> (marque comme echoue).</span>
                </div>
            </div>
        @endif

        {{-- Meta --}}
        <div class="text-xs flex items-center gap-3" style="color: var(--o-text-4);">
            <span>Cree le {{ $anomaly->created_at->format('d/m/Y') }}</span>
            @if($anomaly->resolved_at)
                <span style="color: var(--o-border);">·</span>
                <span>Resolu le {{ $anomaly->resolved_at->format('d/m/Y') }}</span>
            @endif
            @if($anomaly->module)
                <span style="color: var(--o-border);">·</span>
                <span class="px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $anomaly->module->name }}</span>
            @endif
            @if($anomaly->assignee)
                <span style="color: var(--o-border);">·</span>
                <span>Assigne a {{ $anomaly->assignee->name }}</span>
            @endif
        </div>

        {{-- Description --}}
        @if($anomaly->description)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $anomaly->description }}</div>
            </div>
        @endif

        {{-- Linked requirement --}}
        @if($anomaly->requirement)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Exigence liee</h3>
                <a href="{{ route('projects.requirements.show', [$project, $anomaly->requirement]) }}"
                   class="inline-flex items-center gap-2 transition-colors" style="color: var(--o-accent);">
                    <span class="font-mono text-sm">{{ $anomaly->requirement->ref }}</span>
                    <span style="color: var(--o-text-2);">{{ $anomaly->requirement->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif

        {{-- Linked lesson --}}
        @if($anomaly->lesson)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Lesson learned</h3>
                <a href="{{ route('projects.lessons.show', [$project, $anomaly->lesson]) }}"
                   class="inline-flex items-center gap-2 transition-colors" style="color: var(--o-accent);">
                    <span class="font-mono text-sm">{{ $anomaly->lesson->ref }}</span>
                    <span style="color: var(--o-text-2);">{{ $anomaly->lesson->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
