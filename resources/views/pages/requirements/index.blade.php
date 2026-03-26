<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Exigences</h2>
            <a href="{{ route('projects.requirements.create', $project) }}"
               class="inline-flex items-center gap-2 px-4 py-2 btn-primary transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Nouvelle exigence
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <livewire:requirements.requirement-list :project="$project" />
    </div>
</x-app-layout>
