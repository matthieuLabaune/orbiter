<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.requirements.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Nouvelle exigence</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.requirements.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                           class="w-full input-field"
                           placeholder="L'utilisateur peut...">
                    @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="module_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Module</label>
                    <select name="module_id" id="module_id" required
                            class="w-full input-field">
                        <option value="">Sélectionner...</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                {{ $module->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('module_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                @if(request('user_story_id'))
                    <input type="hidden" name="user_story_id" value="{{ request('user_story_id') }}">
                @endif

                <div>
                    <label for="priority" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Priorité</label>
                    <select name="priority" id="priority" required
                            class="w-full input-field">
                        @foreach(['P0' => 'P0 — Critique', 'P1' => 'P1 — Important', 'P2' => 'P2 — Normal', 'P3' => 'P3 — Nice to have'] as $val => $label)
                            <option value="{{ $val }}" {{ old('priority', 'P2') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full input-field"
                          placeholder="Description détaillée de l'exigence...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="acceptance_criteria" class="block text-sm font-medium mb-1" style="color: var(--o-text-4);">Critères d'acceptation</label>
                <textarea name="acceptance_criteria" id="acceptance_criteria" rows="4"
                          class="w-full input-field font-mono text-sm"
                          placeholder="- [ ] Critère 1&#10;- [ ] Critère 2">{{ old('acceptance_criteria') }}</textarea>
            </div>

            {{-- Risk Score FMEA --}}
            <div class="rounded-lg p-4" style="border: 1px solid var(--o-border);">
                <h3 class="text-sm font-medium mb-3" style="color: var(--o-text-2);">Score de risque (FMEA)</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="risk_impact" class="block text-xs mb-1" style="color: var(--o-text-4);">Impact (1-5)</label>
                        <select name="risk_impact" id="risk_impact"
                                class="w-full input-field text-sm">
                            <option value="">—</option>
                            @foreach(range(1, 5) as $v)
                                <option value="{{ $v }}" {{ old('risk_impact') == $v ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="risk_probability" class="block text-xs mb-1" style="color: var(--o-text-4);">Probabilité (1-5)</label>
                        <select name="risk_probability" id="risk_probability"
                                class="w-full input-field text-sm">
                            <option value="">—</option>
                            @foreach(range(1, 5) as $v)
                                <option value="{{ $v }}" {{ old('risk_probability') == $v ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="risk_detectability" class="block text-xs mb-1" style="color: var(--o-text-4);">Détectabilité (1-5)</label>
                        <select name="risk_detectability" id="risk_detectability"
                                class="w-full input-field text-sm">
                            <option value="">—</option>
                            @foreach(range(1, 5) as $v)
                                <option value="{{ $v }}" {{ old('risk_detectability') == $v ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="mt-2 text-xs" style="color: var(--o-text-4);">Score = Impact × Probabilité × (6 - Détectabilité)</p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.requirements.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--o-text-4);">Annuler</a>
                <button type="submit" class="px-4 py-2 btn-primary transition-colors">
                    Créer l'exigence
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
