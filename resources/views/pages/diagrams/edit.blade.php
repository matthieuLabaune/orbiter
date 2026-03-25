<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.diagrams.show', [$project, $diagram]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Modifier {{ $diagram->title }}</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('projects.diagrams.update', [$project, $diagram]) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $diagram->title) }}" required
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label for="mermaid_source" class="block text-sm font-medium text-slate-300 mb-1">Source Mermaid</label>
                <textarea name="mermaid_source" id="mermaid_source" rows="20"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white font-mono text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('mermaid_source', $diagram->mermaid_source) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.diagrams.show', [$project, $diagram]) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Enregistrer (v{{ $diagram->version + 1 }})
                </button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
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
