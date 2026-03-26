<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.requirements.show', [$project, $requirement]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">
                Modifier <span class="font-mono" style="color: var(--o-accent);">{{ $requirement->ref }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.requirements.update', [$project, $requirement]) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $requirement->title) }}" required
                           class="w-full input-field">
                    @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="module_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Module</label>
                    <select name="module_id" id="module_id" required
                            class="w-full input-field">
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ old('module_id', $requirement->module_id) == $module->id ? 'selected' : '' }}>
                                {{ $module->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Priorité</label>
                    <select name="priority" id="priority" required
                            class="w-full input-field">
                        @foreach(['P0' => 'P0 — Critique', 'P1' => 'P1 — Important', 'P2' => 'P2 — Normal', 'P3' => 'P3 — Nice to have'] as $val => $label)
                            <option value="{{ $val }}" {{ old('priority', $requirement->priority) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full input-field">{{ old('description', $requirement->description) }}</textarea>
            </div>

            <div>
                <label for="acceptance_criteria" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Critères d'acceptation</label>
                <textarea name="acceptance_criteria" id="acceptance_criteria" rows="4"
                          class="w-full input-field font-mono text-sm">{{ old('acceptance_criteria', $requirement->acceptance_criteria) }}</textarea>
            </div>

            {{-- Risk Score --}}
            <div class="rounded-lg p-4" style="border: 1px solid var(--o-border);">
                <h3 class="text-sm font-medium mb-3" style="color: var(--o-text-2);">Score de risque (FMEA)</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach(['risk_impact' => 'Impact', 'risk_probability' => 'Probabilité', 'risk_detectability' => 'Détectabilité'] as $field => $label)
                        <div>
                            <label class="block text-xs mb-1" style="color: var(--o-text-4);">{{ $label }} (1-5)</label>
                            <select name="{{ $field }}"
                                    class="w-full input-field text-sm">
                                <option value="">—</option>
                                @foreach(range(1, 5) as $v)
                                    <option value="{{ $v }}" {{ old($field, $requirement->$field) == $v ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Change reason --}}
            <div class="border-t pt-4" style="border-color: var(--o-border);">
                <label for="change_reason" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Raison de la modification</label>
                <input type="text" name="change_reason" id="change_reason"
                       class="w-full input-field"
                       placeholder="Clarification des critères, changement de périmètre...">
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.requirements.show', [$project, $requirement]) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--o-text-4);">Annuler</a>
                <button type="submit" class="px-4 py-2 btn-primary transition-colors">
                    Enregistrer (v{{ $requirement->version + 1 }})
                </button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <h3 class="text-sm font-medium text-red-400 mb-2">Zone dangereuse</h3>
            <p class="text-sm mb-4" style="color: var(--o-text-4);">Supprimer cette exigence et toutes ses versions.</p>
            <form action="{{ route('projects.requirements.destroy', [$project, $requirement]) }}" method="POST"
                  onsubmit="return confirm('Supprimer {{ $requirement->ref }} ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm font-medium rounded-lg border border-red-500/30 transition-colors">
                    Supprimer {{ $requirement->ref }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
