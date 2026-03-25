<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.diagrams.index', $project) }}" class="transition-colors" style="color: var(--orbiter-text-muted);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Nouveau diagramme</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('projects.diagrams.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="input-field"
                       placeholder="Architecture des modules">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mermaid_source" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Source Mermaid</label>
                <textarea name="mermaid_source" id="mermaid_source" rows="15"
                          class="input-field font-mono"
                          placeholder="graph TB&#10;    A[Module A] --> B[Module B]&#10;    B --> C[Module C]">{{ old('mermaid_source', "graph TB\n    A[Module A] --> B[Module B]\n    B --> C[Module C]") }}</textarea>
                @error('mermaid_source') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs" style="color: var(--orbiter-text-muted);">
                    <a href="https://mermaid.js.org/intro/" target="_blank" rel="noopener" style="color: var(--orbiter-accent);">Documentation Mermaid.js</a>
                </p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.diagrams.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--orbiter-text-muted);">Annuler</a>
                <button type="submit" class="btn-primary">
                    Créer le diagramme
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
