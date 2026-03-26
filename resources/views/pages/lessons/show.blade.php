<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.lessons.index', $project) }}" class="transition-colors" style="color: var(--o-text-4);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono" style="color: var(--o-accent);">{{ $lesson->ref }}</span>
                </div>
                <h2 class="text-xl font-semibold" style="color: var(--o-text);">{{ $lesson->title }}</h2>
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
            <span>{{ $lesson->created_at->format('d/m/Y') }}</span>
            <span>Par {{ $lesson->author?->name ?? '—' }}</span>
            @if($lesson->module)
                <span style="color: var(--o-border);">·</span>
                <span class="px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $lesson->module->name }}</span>
            @endif
        </div>

        {{-- Tags --}}
        @if($lesson->tags)
            <div class="flex flex-wrap gap-1.5">
                @foreach($lesson->tags as $tag)
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background: var(--o-surface-2); color: var(--o-text-4); border: 1px solid var(--o-border);">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        {{-- Description --}}
        @if($lesson->description)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Description</h3>
                <div class="whitespace-pre-wrap" style="color: var(--o-text-2);">{{ $lesson->description }}</div>
            </div>
        @endif

        {{-- Linked requirement --}}
        @if($lesson->requirement)
            <div class="surface p-5">
                <h3 class="text-sm font-medium uppercase tracking-wider mb-3" style="color: var(--o-text-4);">Exigence liée</h3>
                <a href="{{ route('projects.requirements.show', [$project, $lesson->requirement]) }}"
                   class="inline-flex items-center gap-2 transition-colors" style="color: var(--o-accent);">
                    <span class="font-mono text-sm">{{ $lesson->requirement->ref }}</span>
                    <span style="color: var(--o-text-2);">{{ $lesson->requirement->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
