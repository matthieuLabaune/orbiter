<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Lessons Learned</h2>
            <a href="{{ route('projects.lessons.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
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
               class="block bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-5 hover:border-blue-500/50 transition-all group">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-blue-500 dark:text-blue-400 text-sm">{{ $lesson->ref }}</span>
                        </div>
                        <h3 class="text-gray-900 dark:text-white font-medium group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">{{ $lesson->title }}</h3>
                        @if($lesson->tags)
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach($lesson->tags as $tag)
                                    <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 dark:text-slate-500 text-right">
                        <div>{{ $lesson->created_at->format('d/m/Y') }}</div>
                        @if($lesson->module) <div>{{ $lesson->module->name }}</div> @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <x-lucide-lightbulb class="w-12 h-12 text-gray-300 dark:text-slate-600 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-600 dark:text-slate-300 mb-2">Aucune lesson learned</h3>
                <p class="text-gray-400 dark:text-slate-500">Capitalisez vos apprentissages.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
