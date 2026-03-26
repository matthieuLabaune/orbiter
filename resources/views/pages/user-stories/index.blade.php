<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">User Stories</h2>
            <a href="{{ route('projects.user-stories.create', $project) }}" class="btn-primary">
                <x-lucide-plus class="w-4 h-4" /> Nouvelle User Story
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: var(--o-green-bg); color: var(--o-green);">{{ session('success') }}</div>
    @endif

    @forelse($stories as $story)
        @php
            $adv = $story->advancement;
            $statusColors = ['open' => 'blue', 'in_progress' => 'orange', 'done' => 'green', 'closed' => 'gray'];
            $priorityColors = ['P0' => 'red', 'P1' => 'orange', 'P2' => 'blue', 'P3' => 'gray'];
        @endphp
        <a href="{{ route('projects.user-stories.show', [$project, $story]) }}"
           class="block surface p-5 mb-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-sm font-medium" style="color: var(--o-accent);">{{ $story->ref }}</span>
                        <x-ui.badge :color="$priorityColors[$story->priority] ?? 'gray'">{{ $story->priority }}</x-ui.badge>
                        <x-ui.badge :color="$statusColors[$story->status] ?? 'gray'">{{ $story->status }}</x-ui.badge>
                    </div>
                    <h3 class="font-semibold" style="color: var(--o-text);">{{ $story->title }}</h3>
                </div>
                <div class="text-right">
                    <div class="text-lg font-bold font-mono" style="color: {{ $adv['percentage'] === 100 ? 'var(--o-green)' : 'var(--o-accent)' }};">{{ $adv['percentage'] }}%</div>
                    <div class="text-[10px]" style="color: var(--o-text-4);">{{ $adv['validated'] }}/{{ $adv['total'] }} validées</div>
                </div>
            </div>
            @if($story->requirements->isNotEmpty())
                <div class="flex items-center gap-3 mt-3">
                    <span class="text-xs" style="color: var(--o-text-4);">{{ $story->requirements->count() }} REQ</span>
                    <x-ui.progress-bar :value="$adv['validated']" :max="$adv['total']" color="green" :showLabel="false" class="flex-1" />
                </div>
            @endif
        </a>
    @empty
        <div class="text-center py-16 surface">
            <x-lucide-book-open class="w-12 h-12 mx-auto mb-4" style="color: var(--o-text-4);" />
            <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucune User Story</h3>
            <p class="text-sm mb-6" style="color: var(--o-text-3);">Décrivez les besoins utilisateurs.</p>
        </div>
    @endforelse
</x-app-layout>
