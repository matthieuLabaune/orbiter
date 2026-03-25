<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.adrs.show', [$project, $adr]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Modifier <span class="font-mono text-blue-400">{{ $adr->ref }}</span></h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.adrs.update', [$project, $adr]) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $adr->title) }}" required
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                    <select name="status" id="status" required
                            class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500">
                        @foreach(['proposed' => 'Proposé', 'accepted' => 'Accepté', 'deprecated' => 'Déprécié', 'superseded' => 'Remplacé'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $adr->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="superseded_by" class="block text-sm font-medium text-slate-300 mb-1">Remplacé par</label>
                    <input type="text" name="superseded_by" id="superseded_by" value="{{ old('superseded_by', $adr->superseded_by) }}"
                           class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500"
                           placeholder="ADR-XXX">
                </div>
            </div>

            @foreach([['context', 'Contexte', $adr->context], ['decision', 'Décision', $adr->decision], ['consequences', 'Conséquences', $adr->consequences]] as [$field, $label, $value])
                <div>
                    <label for="{{ $field }}" class="block text-sm font-medium text-slate-300 mb-1">{{ $label }}</label>
                    <textarea name="{{ $field }}" id="{{ $field }}" rows="4"
                              class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old($field, $value) }}</textarea>
                </div>
            @endforeach

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Modules impactés</label>
                @php $linkedModules = $adr->modules->pluck('id')->toArray(); @endphp
                <div class="flex flex-wrap gap-2">
                    @foreach($modules as $module)
                        <label class="flex items-center gap-1.5 text-sm cursor-pointer px-2 py-1 bg-slate-800 rounded-lg border border-slate-700 hover:border-slate-600">
                            <input type="checkbox" name="modules[]" value="{{ $module->id }}"
                                   {{ in_array($module->id, old('modules', $linkedModules)) ? 'checked' : '' }}
                                   class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-blue-500">
                            <span class="text-slate-300">{{ $module->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.adrs.show', [$project, $adr]) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">Enregistrer</button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <form action="{{ route('projects.adrs.destroy', [$project, $adr]) }}" method="POST"
                  onsubmit="return confirm('Supprimer {{ $adr->ref }} ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm rounded-lg border border-red-500/30 transition-colors">
                    Supprimer {{ $adr->ref }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
