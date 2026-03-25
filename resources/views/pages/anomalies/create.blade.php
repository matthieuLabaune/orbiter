<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.anomalies.index', $project) }}" class="transition-colors" style="color: var(--orbiter-text-muted);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Signaler une anomalie</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.anomalies.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="input-field"
                       placeholder="Description courte de l'anomalie...">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Type</label>
                    <select name="type" id="type" required
                            class="input-field">
                        @foreach(['anomaly' => 'Anomalie', 'non_conformity' => 'Non-conformite', 'defect' => 'Defaut'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', 'anomaly') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="severity" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Severite</label>
                    <select name="severity" id="severity" required
                            class="input-field">
                        @foreach(['low' => 'Faible', 'medium' => 'Moyen', 'high' => 'Eleve', 'critical' => 'Critique'] as $val => $label)
                            <option value="{{ $val }}" {{ old('severity', 'medium') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="input-field"
                          placeholder="Contexte, etapes de reproduction, impact observe...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="module_id" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Module</label>
                    <select name="module_id" id="module_id"
                            class="input-field">
                        <option value="">—</option>
                        @foreach($modules as $m)
                            <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="requirement_id" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Exigence liee <span class="text-xs" style="color: var(--orbiter-text-muted);">(requis si NC)</span></label>
                    <select name="requirement_id" id="requirement_id"
                            class="input-field">
                        <option value="">—</option>
                        @foreach($requirements as $r)
                            <option value="{{ $r->id }}" {{ old('requirement_id') == $r->id ? 'selected' : '' }}>{{ $r->ref }} — {{ Str::limit($r->title, 40) }}</option>
                        @endforeach
                    </select>
                    @error('requirement_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="assignee_id" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Assigne a</label>
                <select name="assignee_id" id="assignee_id"
                        class="input-field">
                    <option value="">—</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('assignee_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.anomalies.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--orbiter-text-muted);">Annuler</a>
                <button type="submit" class="btn-primary">
                    Signaler l'anomalie
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
