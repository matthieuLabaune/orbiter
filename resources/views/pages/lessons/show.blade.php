<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.lessons.index', $project) }}" class="text-gray-400 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-blue-500 dark:text-blue-400">{{ $lesson->ref }}</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $lesson->title }}</h2>
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
        <div class="text-xs text-gray-400 dark:text-slate-500 flex items-center gap-3">
            <span>{{ $lesson->created_at->format('d/m/Y') }}</span>
            <span>Par {{ $lesson->author?->name ?? '—' }}</span>
            @if($lesson->module)
                <span class="text-gray-300 dark:text-slate-700">·</span>
                <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded">{{ $lesson->module->name }}</span>
            @endif
        </div>

        {{-- Tags --}}
        @if($lesson->tags)
            <div class="flex flex-wrap gap-1.5">
                @foreach($lesson->tags as $tag)
                    <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded-full border border-gray-200 dark:border-slate-700/50">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        {{-- Description --}}
        @if($lesson->description)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                <div class="text-gray-600 dark:text-slate-300 whitespace-pre-wrap">{{ $lesson->description }}</div>
            </div>
        @endif

        {{-- Linked requirement --}}
        @if($lesson->requirement)
            <div class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5">
                <h3 class="text-sm font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-3">Exigence liée</h3>
                <a href="{{ route('projects.requirements.show', [$project, $lesson->requirement]) }}"
                   class="inline-flex items-center gap-2 text-blue-500 dark:text-blue-400 hover:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    <span class="font-mono text-sm">{{ $lesson->requirement->ref }}</span>
                    <span class="text-gray-600 dark:text-slate-300">{{ $lesson->requirement->title }}</span>
                    <x-lucide-external-link class="w-3.5 h-3.5" />
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
