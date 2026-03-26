<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="hover:opacity-80 transition-colors" style="color: var(--o-text-4);">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold" style="color: var(--o-text);">Nouveau projet</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.store') }}" method="POST"
              class="surface p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Nom du projet</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="input-field w-full rounded-lg px-3 py-2 transition-colors"
                       placeholder="Mon projet">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium mb-1" style="color: var(--o-text-2);">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="input-field w-full rounded-lg px-3 py-2 transition-colors"
                          placeholder="Description du projet...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm hover:opacity-80 transition-colors" style="color: var(--o-text-2);">
                    Annuler
                </a>
                <button type="submit"
                        class="btn-primary px-4 py-2 text-sm font-medium transition-colors">
                    Créer le projet
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
