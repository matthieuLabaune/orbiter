<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.user-stories.index', $project) }}" class="opacity-40 hover:opacity-100 transition-opacity" style="color: var(--o-text-3);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Nouvelle User Story</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('projects.user-stories.store', $project) }}" method="POST" class="surface p-6 space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="input-field" placeholder="En tant que PM, je veux...">
                @error('title') <p class="mt-1 text-sm" style="color: var(--o-red);">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="module_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Module</label>
                    <select name="module_id" id="module_id" class="input-field">
                        <option value="">—</option>
                        @foreach($modules as $m) <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Priorité</label>
                    <select name="priority" id="priority" required class="input-field">
                        @foreach(['P0' => 'P0 — Critique', 'P1' => 'P1 — Important', 'P2' => 'P2 — Normal', 'P3' => 'P3 — Nice to have'] as $val => $label)
                            <option value="{{ $val }}" {{ old('priority', 'P2') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="assignee_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Assignée à</label>
                <select name="assignee_id" id="assignee_id" class="input-field">
                    <option value="">—</option>
                    @foreach($members as $m) <option value="{{ $m->id }}" {{ old('assignee_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Description</label>
                <textarea name="description" id="description" rows="4" class="input-field"
                          placeholder="En tant que [rôle], je veux [action] pour [bénéfice]">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="acceptance_criteria" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Critères d'acceptation</label>
                <textarea name="acceptance_criteria" id="acceptance_criteria" rows="4" class="input-field font-mono text-sm"
                          placeholder="- [ ] Critère 1&#10;- [ ] Critère 2">{{ old('acceptance_criteria') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('projects.user-stories.index', $project) }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Créer la User Story</button>
            </div>
        </form>
    </div>
</x-app-layout>
