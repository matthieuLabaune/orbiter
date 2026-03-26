<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Lessons Learned</h2>
            <a href="{{ route('projects.lessons.create', $project) }}"
               class="btn-primary">
                <x-lucide-plus class="w-4 h-4" />
                Nouvelle lesson
            </a>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto space-y-4">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">{{ session('success') }}</div>
        @endif
        @forelse($lessons as $lesson)
            <a href="{{ route('projects.lessons.show', [$project, $lesson]) }}"
               class="block surface p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-sm" style="color: var(--o-accent);">{{ $lesson->ref }}</span>
                        </div>
                        <h3 class="font-medium transition-colors" style="color: var(--o-text);">{{ $lesson->title }}</h3>
                        @if($lesson->tags)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($lesson->tags as $tag)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded" style="background: var(--o-surface-2); color: var(--o-text-4);">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-right" style="color: var(--o-text-4);">
                        <div>{{ $lesson->created_at->format('d/m/Y') }}</div>
                        @if($lesson->module) <div>{{ $lesson->module->name }}</div> @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-lightbulb class="w-12 h-12 mx-auto mb-4" style="color: var(--o-text-4);" />
                <h3 class="text-lg font-medium mb-2" style="color: var(--o-text-2);">Aucune lesson learned</h3>
                <p style="color: var(--o-text-4);">Capitalisez vos apprentissages.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
