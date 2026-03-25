<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.modules.show', [$project, $module]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Modifier {{ $module->name }}</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.modules.update', [$project, $module]) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Nom du module</label>
                <input type="text" name="name" id="name" value="{{ old('name', $module->name) }}" required
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">{{ old('description', $module->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                <select name="status" id="status"
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
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
                    <label class="block text-sm font-medium text-slate-300 mb-2">Dépendances</label>
                    <div class="space-y-2 bg-slate-800 border border-slate-600 rounded-lg p-3">
                        @foreach($allModules as $otherModule)
                            <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer hover:text-white transition-colors">
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
                <a href="{{ route('projects.modules.show', [$project, $module]) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <h3 class="text-sm font-medium text-red-400 mb-2">Zone dangereuse</h3>
            <p class="text-sm text-slate-500 mb-4">Supprimer ce module et toutes ses données de manière irréversible.</p>
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
