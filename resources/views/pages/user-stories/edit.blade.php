<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.user-stories.show', [$project, $userStory]) }}" class="opacity-40 hover:opacity-100 transition-opacity" style="color: var(--o-text-3);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Modifier <span class="font-mono" style="color: var(--o-accent);">{{ $userStory->ref }}</span></h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('projects.user-stories.update', [$project, $userStory]) }}" method="POST" class="surface p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $userStory->title) }}" required class="input-field">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="module_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Module</label>
                    <select name="module_id" id="module_id" class="input-field">
                        <option value="">—</option>
                        @foreach($modules as $m) <option value="{{ $m->id }}" {{ old('module_id', $userStory->module_id) == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Priorité</label>
                    <select name="priority" id="priority" required class="input-field">
                        @foreach(['P0','P1','P2','P3'] as $p) <option value="{{ $p }}" {{ old('priority', $userStory->priority) == $p ? 'selected' : '' }}>{{ $p }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Statut</label>
                    <select name="status" id="status" required class="input-field">
                        @foreach(['open' => 'Ouvert', 'in_progress' => 'En cours', 'done' => 'Terminé', 'closed' => 'Fermé'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $userStory->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Description</label>
                <textarea name="description" id="description" rows="4" class="input-field">{{ old('description', $userStory->description) }}</textarea>
            </div>

            <div>
                <label for="acceptance_criteria" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Critères d'acceptation</label>
                <textarea name="acceptance_criteria" id="acceptance_criteria" rows="4" class="input-field font-mono text-sm">{{ old('acceptance_criteria', $userStory->acceptance_criteria) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('projects.user-stories.show', [$project, $userStory]) }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>

        <div class="mt-8 p-6 rounded-2xl" style="background: var(--o-red-bg);">
            <h3 class="text-sm font-medium mb-2" style="color: var(--o-red);">Zone dangereuse</h3>
            <form action="{{ route('projects.user-stories.destroy', [$project, $userStory]) }}" method="POST"
                  onsubmit="return confirm('Supprimer {{ $userStory->ref }} ?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm font-medium px-4 py-2 rounded-xl" style="background: var(--o-red-bg); color: var(--o-red); border: 1px solid var(--o-red);">
                    Supprimer {{ $userStory->ref }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
