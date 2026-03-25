<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.tests.show', [$project, $test]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">
                Modifier <span class="font-mono text-blue-400">{{ $test->ref }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.tests.update', [$project, $test]) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $test->title) }}" required
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-slate-300 mb-1">Type</label>
                <select name="type" id="type" required
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500">
                    @foreach(['manual' => 'Manuel', 'automated' => 'Automatisé', 'review' => 'Review'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $test->type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="procedure" class="block text-sm font-medium text-slate-300 mb-1">Procédure</label>
                <textarea name="procedure" id="procedure" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 font-mono text-sm">{{ old('procedure', $test->procedure) }}</textarea>
            </div>

            <div>
                <label for="expected_result" class="block text-sm font-medium text-slate-300 mb-1">Résultat attendu</label>
                <textarea name="expected_result" id="expected_result" rows="2"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500">{{ old('expected_result', $test->expected_result) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Exigences couvertes</label>
                <div class="max-h-48 overflow-y-auto space-y-2 border border-slate-700/50 rounded-lg p-3">
                    @php $linkedReqs = $test->requirements->pluck('id')->toArray(); @endphp
                    @foreach($requirements as $req)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="requirements[]" value="{{ $req->id }}"
                                   {{ in_array($req->id, old('requirements', $linkedReqs)) ? 'checked' : '' }}
                                   class="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span class="font-mono text-blue-400">{{ $req->ref }}</span>
                            <span class="text-slate-300 truncate">{{ $req->title }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.tests.show', [$project, $test]) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">Enregistrer</button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <h3 class="text-sm font-medium text-red-400 mb-2">Zone dangereuse</h3>
            <form action="{{ route('projects.tests.destroy', [$project, $test]) }}" method="POST"
                  onsubmit="return confirm('Supprimer {{ $test->ref }} ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm rounded-lg border border-red-500/30 transition-colors">
                    Supprimer {{ $test->ref }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
