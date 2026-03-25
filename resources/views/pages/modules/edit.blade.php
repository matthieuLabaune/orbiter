<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.modules.show', [$project, $module]) }}" class="hover:opacity-80 transition-colors" style="color: var(--orbiter-text-muted);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">Modifier {{ $module->name }}</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.modules.update', [$project, $module]) }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Nom du module</label>
                <input type="text" name="name" id="name" value="{{ old('name', $module->name) }}" required
                       class="input-field w-full rounded-lg px-3 py-2 transition-colors">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="input-field w-full rounded-lg px-3 py-2 transition-colors">{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Statut</label>
                <select name="status" id="status"
                        class="input-field w-full rounded-lg px-3 py-2 transition-colors">
                    <option value="active" {{ old('status', $module->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ old('status', $module->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="deprecated" {{ old('status', $module->status) === 'deprecated' ? 'selected' : '' }}>Deprecated</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dépendances --}}
            @if($allModules->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--orbiter-text-secondary);">Dépendances</label>
                    <div class="space-y-2 rounded-lg p-3" style="background: var(--orbiter-surface-2); border: 1px solid var(--orbiter-border);">
                        @foreach($allModules as $otherModule)
                            <label class="flex items-center gap-2 text-sm cursor-pointer hover:opacity-80 transition-colors" style="color: var(--orbiter-text-secondary);">
                                <input type="checkbox" name="dependencies[]" value="{{ $otherModule->id }}"
                                       {{ in_array($otherModule->id, old('dependencies', $module->dependencies->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded bg-slate-700 border-slate-600 text-blue-500 focus:ring-blue-500 focus:ring-offset-0">
                                {{ $otherModule->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('dependencies')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.modules.show', [$project, $module]) }}" class="px-4 py-2 text-sm hover:opacity-80 transition-colors" style="color: var(--orbiter-text-secondary);">
                    Annuler
                </a>
                <button type="submit"
                        class="btn-primary px-4 py-2 text-sm font-medium transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <h3 class="text-sm font-medium text-red-400 mb-2">Zone dangereuse</h3>
            <p class="text-sm mb-4" style="color: var(--orbiter-text-muted);">Supprimer ce module et toutes ses données de manière irréversible.</p>
            <form action="{{ route('projects.modules.destroy', [$project, $module]) }}" method="POST"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce module ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm font-medium rounded-lg border border-red-500/30 transition-colors">
                    Supprimer le module
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
