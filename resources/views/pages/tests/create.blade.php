<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.tests.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Nouveau test</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.tests.store', $project) }}" method="POST"
              class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                           placeholder="Vérifier que...">
                    @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                    <label for="type" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Type</label>
                    <select name="type" id="type" required
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>Manuel</option>
                        <option value="automated" {{ old('type') == 'automated' ? 'selected' : '' }}>Automatisé</option>
                        <option value="review" {{ old('type') == 'review' ? 'selected' : '' }}>Review</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="procedure" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Procédure</label>
                <textarea name="procedure" id="procedure" rows="4"
                          class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 font-mono text-sm"
                          placeholder="1. Étape 1&#10;2. Étape 2&#10;3. Vérifier que...">{{ old('procedure') }}</textarea>
            </div>

            <div>
                <label for="expected_result" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Résultat attendu</label>
                <textarea name="expected_result" id="expected_result" rows="2"
                          class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Le système doit...">{{ old('expected_result') }}</textarea>
            </div>

            {{-- Requirements linkage --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-2">Exigences couvertes</label>
                <div class="max-h-48 overflow-y-auto space-y-2 border border-gray-200 dark:border-slate-700/50 rounded-lg p-3">
                    @foreach($requirements as $req)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="requirements[]" value="{{ $req->id }}"
                                   {{ in_array($req->id, old('requirements', [])) ? 'checked' : '' }}
                                   class="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span class="font-mono text-blue-600 dark:text-blue-400">{{ $req->ref }}</span>
                            <span class="text-gray-600 dark:text-slate-300 truncate">{{ $req->title }}</span>
                        </label>
                    @endforeach
                    @if($requirements->isEmpty())
                        <p class="text-xs text-gray-400 dark:text-slate-500">Aucune exigence dans ce projet.</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.tests.index', $project) }}" class="px-4 py-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Créer le test
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
