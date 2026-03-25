<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.baselines.index', $project) }}" class="transition-colors" style="color: var(--orbiter-text-muted);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Nouvelle baseline</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.baselines.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="ref" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Reference (version)</label>
                <input type="text" name="ref" id="ref" value="{{ old('ref') }}" required autofocus
                       class="input-field"
                       placeholder="v0.1.0">
                @error('ref') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="input-field"
                       placeholder="Baseline MVP, Release candidate...">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="input-field"
                          placeholder="Notes sur cette baseline...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="signed_by" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Signe par</label>
                <input type="text" name="signed_by" id="signed_by" value="{{ old('signed_by') }}"
                       class="input-field"
                       placeholder="Nom du responsable">
            </div>

            <div class="px-4 py-3 bg-blue-500/5 border border-blue-500/20 rounded-lg">
                <div class="flex items-center gap-2 text-sm" style="color: var(--orbiter-accent);">
                    <x-lucide-info class="w-4 h-4 flex-shrink-0" />
                    <span>Un snapshot complet de l'etat du projet sera automatiquement capture au moment de la creation.</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.baselines.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--orbiter-text-muted);">Annuler</a>
                <button type="submit" class="btn-primary">
                    Creer la baseline
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
