<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.diagrams.show', [$project, $diagram]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Modifier {{ $diagram->title }}</h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <livewire:architecture.diagram-editor :project="$project" :diagram="$diagram" />

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6 max-w-md">
            <form action="{{ route('projects.diagrams.destroy', [$project, $diagram]) }}" method="POST"
                  onsubmit="return confirm('Supprimer ce diagramme ?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm rounded-lg border border-red-500/30 transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
