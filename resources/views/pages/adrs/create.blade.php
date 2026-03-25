<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.adrs.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Nouvel ADR</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.adrs.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-muted);">Titre de la décision</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="w-full input-field"
                       placeholder="Choix de X plutôt que Y">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-muted);">Statut</label>
                <select name="status" id="status" required
                        class="w-full input-field">
                    @foreach(['proposed' => 'Proposé', 'accepted' => 'Accepté', 'deprecated' => 'Déprécié', 'superseded' => 'Remplacé'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', 'proposed') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="context" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-muted);">Contexte</label>
                <textarea name="context" id="context" rows="4"
                          class="w-full input-field"
                          placeholder="Quel problème ou situation motive cette décision ?">{{ old('context') }}</textarea>
            </div>

            <div>
                <label for="decision" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-muted);">Décision</label>
                <textarea name="decision" id="decision" rows="4"
                          class="w-full input-field"
                          placeholder="Quelle décision a été prise ?">{{ old('decision') }}</textarea>
            </div>

            <div>
                <label for="consequences" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-muted);">Conséquences</label>
                <textarea name="consequences" id="consequences" rows="4"
                          class="w-full input-field"
                          placeholder="Impacts positifs et négatifs...">{{ old('consequences') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--orbiter-text-muted);">Modules impactés</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($modules as $module)
                        <label class="flex items-center gap-1.5 text-sm cursor-pointer px-2 py-1 rounded-lg" style="background: var(--orbiter-surface); border: 1px solid var(--orbiter-border);">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                                   {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }}
                                   class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span style="color: var(--orbiter-text-secondary);">{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.adrs.index', $project) }}" class="px-4 py-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 btn-primary transition-colors">
                    Créer l'ADR
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
