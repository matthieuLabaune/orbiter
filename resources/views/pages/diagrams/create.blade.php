<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.diagrams.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Nouveau diagramme</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('projects.diagrams.store', $project) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Architecture des modules">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mermaid_source" class="block text-sm font-medium text-slate-300 mb-1">Source Mermaid</label>
                <textarea name="mermaid_source" id="mermaid_source" rows="15"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white font-mono text-sm placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="graph TB&#10;    A[Module A] --> B[Module B]&#10;    B --> C[Module C]">{{ old('mermaid_source', "graph TB\n    A[Module A] --> B[Module B]\n    B --> C[Module C]") }}</textarea>
                @error('mermaid_source') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-slate-500">
                    <a href="https://mermaid.js.org/intro/" target="_blank" rel="noopener" class="text-blue-400 hover:text-blue-300">Documentation Mermaid.js</a>
                </p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.diagrams.index', $project) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Créer le diagramme
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
