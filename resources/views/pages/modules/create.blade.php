<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.modules.index', $project) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Nouveau module</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.modules.store', $project) }}" method="POST"
              class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Nom du module</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                       placeholder="Mon module">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                          placeholder="Description du module...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                <select name="status" id="status"
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="deprecated" {{ old('status') === 'deprecated' ? 'selected' : '' }}>Deprecated</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.modules.index', $project) }}" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Créer le module
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
