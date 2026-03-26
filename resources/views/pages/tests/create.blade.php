<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.tests.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Nouveau test</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.tests.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                           class="w-full input-field"
                           placeholder="Vérifier que...">
                    @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label for="type" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Type</label>
                    <select name="type" id="type" required
                            class="w-full input-field">
                        <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>Manuel</option>
                        <option value="automated" {{ old('type') == 'automated' ? 'selected' : '' }}>Automatisé</option>
                        <option value="review" {{ old('type') == 'review' ? 'selected' : '' }}>Review</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="procedure" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Procédure</label>
                <textarea name="procedure" id="procedure" rows="4"
                          class="w-full input-field font-mono text-sm"
                          placeholder="1. Étape 1&#10;2. Étape 2&#10;3. Vérifier que...">{{ old('procedure') }}</textarea>
            </div>

            <div>
                <label for="expected_result" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Résultat attendu</label>
                <textarea name="expected_result" id="expected_result" rows="2"
                          class="w-full input-field"
                          placeholder="Le système doit...">{{ old('expected_result') }}</textarea>
            </div>

            {{-- Requirements linkage --}}
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--o-text-4);">Exigences couvertes</label>
                <div class="max-h-48 overflow-y-auto space-y-2 rounded-lg p-3" style="border: 1px solid var(--o-border);">
                    @foreach($requirements as $req)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="requirements[]" value="{{ $req->id }}"
                                   {{ in_array($req->id, old('requirements', [])) ? 'checked' : '' }}
                                   class="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span class="font-mono" style="color: var(--o-accent);">{{ $req->ref }}</span>
                            <span class="truncate" style="color: var(--o-text-2);">{{ $req->title }}</span>
                        </label>
                    @endforeach
                    @if($requirements->isEmpty())
                        <p class="text-xs" style="color: var(--o-text-4);">Aucune exigence dans ce projet.</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.tests.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--o-text-4);">Annuler</a>
                <button type="submit" class="px-4 py-2 btn-primary transition-colors">
                    Créer le test
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
