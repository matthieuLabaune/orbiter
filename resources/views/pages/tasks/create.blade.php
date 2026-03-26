<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.tasks.index', $project) }}" class="transition-colors" style="color: var(--o-text-4);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Nouvelle tâche</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.tasks.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="input-field">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="input-field">{{ old('description') }}</textarea>
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
                    <label for="assignee_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Assigné à</label>
                    <select name="assignee_id" id="assignee_id" class="input-field">
                        <option value="">—</option>
                        @foreach($members as $m) <option value="{{ $m->id }}" {{ old('assignee_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="requirement_id" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Exigence liée</label>
                    <select name="requirement_id" id="requirement_id" class="input-field">
                        <option value="">—</option>
                        @foreach($requirements as $r) <option value="{{ $r->id }}" {{ old('requirement_id') == $r->id ? 'selected' : '' }}>{{ $r->ref }} — {{ Str::limit($r->title, 40) }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Statut</label>
                    <select name="status" id="status" required class="input-field">
                        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', 'todo') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="progress" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Progression (%)</label>
                    <input type="number" name="progress" id="progress" value="{{ old('progress', 0) }}" min="0" max="100"
                           class="input-field">
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Début</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                           class="input-field">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Fin</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="input-field">
                </div>
            </div>

            @if($allTasks->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--o-text-2);">Bloqué par</label>
                    <div class="max-h-32 overflow-y-auto space-y-1.5 rounded-lg p-3" style="border: 1px solid var(--o-border);">
                        @foreach($allTasks as $t)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="blocked_by[]" value="{{ $t->id }}"
                                       class="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500">
                                <span style="color: var(--o-text-2);">{{ $t->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.tasks.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--o-text-4);">Annuler</a>
                <button type="submit" class="btn-primary">Créer la tâche</button>
            </div>
        </form>
    </div>
</x-app-layout>
