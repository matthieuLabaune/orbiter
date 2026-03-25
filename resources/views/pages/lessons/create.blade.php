<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.lessons.index', $project) }}" class="transition-colors" style="color: var(--orbiter-text-muted);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Nouvelle lesson learned</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.lessons.store', $project) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="input-field"
                       placeholder="Apprentissage clé...">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="input-field"
                          placeholder="Contexte, impact, recommandation...">{{ old('description') }}</textarea>
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
                    <label for="requirement_id" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Exigence liée</label>
                    <select name="requirement_id" id="requirement_id"
                            class="input-field">
                        <option value="">—</option>
                        @foreach($requirements as $r)
                            <option value="{{ $r->id }}" {{ old('requirement_id') == $r->id ? 'selected' : '' }}>{{ $r->ref }} — {{ Str::limit($r->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="tags" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Tags</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                       class="input-field"
                       placeholder="process, testing, design (comma-separated)">
                @error('tags') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.lessons.index', $project) }}" class="px-4 py-2 text-sm transition-colors" style="color: var(--orbiter-text-muted);">Annuler</a>
                <button type="submit" class="btn-primary">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
