<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.adrs.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Nouvel ADR</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.adrs.store', $project) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Titre de la décision</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Choix de X plutôt que Y">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                <select name="status" id="status" required
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500">
                    @foreach(['proposed' => 'Proposé', 'accepted' => 'Accepté', 'deprecated' => 'Déprécié', 'superseded' => 'Remplacé'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', 'proposed') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="context" class="block text-sm font-medium text-slate-300 mb-1">Contexte</label>
                <textarea name="context" id="context" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Quel problème ou situation motive cette décision ?">{{ old('context') }}</textarea>
            </div>

            <div>
                <label for="decision" class="block text-sm font-medium text-slate-300 mb-1">Décision</label>
                <textarea name="decision" id="decision" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Quelle décision a été prise ?">{{ old('decision') }}</textarea>
            </div>

            <div>
                <label for="consequences" class="block text-sm font-medium text-slate-300 mb-1">Conséquences</label>
                <textarea name="consequences" id="consequences" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Impacts positifs et négatifs...">{{ old('consequences') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Modules impactés</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($modules as $module)
                        <label class="flex items-center gap-1.5 text-sm cursor-pointer px-2 py-1 bg-slate-800 rounded-lg border border-slate-700 hover:border-slate-600">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                                   {{ in_array($module->id, old('modules', [])) ? 'checked' : '' }}
                                   class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span class="text-slate-300">{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.adrs.index', $project) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Créer l'ADR
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
