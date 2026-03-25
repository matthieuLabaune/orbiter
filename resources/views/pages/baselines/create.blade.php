<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.baselines.index', $project) }}" class="text-gray-400 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Nouvelle baseline</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.baselines.store', $project) }}" method="POST"
              class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <label for="ref" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Reference (version)</label>
                <input type="text" name="ref" id="ref" value="{{ old('ref') }}" required autofocus
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="v0.1.0">
                @error('ref') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Baseline MVP, Release candidate...">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Notes sur cette baseline...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="signed_by" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Signe par</label>
                <input type="text" name="signed_by" id="signed_by" value="{{ old('signed_by') }}"
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Nom du responsable">
            </div>

            <div class="px-4 py-3 bg-blue-500/5 border border-blue-500/20 rounded-lg">
                <div class="flex items-center gap-2 text-sm text-blue-500 dark:text-blue-400">
                    <x-lucide-info class="w-4 h-4 flex-shrink-0" />
                    <span>Un snapshot complet de l'etat du projet sera automatiquement capture au moment de la creation.</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.baselines.index', $project) }}" class="px-4 py-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Creer la baseline
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
