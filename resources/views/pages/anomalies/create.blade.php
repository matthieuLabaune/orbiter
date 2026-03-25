<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.anomalies.index', $project) }}" class="text-gray-400 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Signaler une anomalie</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.anomalies.store', $project) }}" method="POST"
              class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                       placeholder="Description courte de l'anomalie...">
                @error('title') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Type</label>
                    <select name="type" id="type" required
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        @foreach(['anomaly' => 'Anomalie', 'non_conformity' => 'Non-conformite', 'defect' => 'Defaut'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', 'anomaly') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="severity" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Severite</label>
                    <select name="severity" id="severity" required
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        @foreach(['low' => 'Faible', 'medium' => 'Moyen', 'high' => 'Eleve', 'critical' => 'Critique'] as $val => $label)
                            <option value="{{ $val }}" {{ old('severity', 'medium') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                          placeholder="Contexte, etapes de reproduction, impact observe...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="module_id" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Module</label>
                    <select name="module_id" id="module_id"
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="">—</option>
                        @foreach($modules as $m)
                            <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="requirement_id" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Exigence liee <span class="text-xs text-gray-400 dark:text-slate-500">(requis si NC)</span></label>
                    <select name="requirement_id" id="requirement_id"
                            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="">—</option>
                        @foreach($requirements as $r)
                            <option value="{{ $r->id }}" {{ old('requirement_id') == $r->id ? 'selected' : '' }}>{{ $r->ref }} — {{ Str::limit($r->title, 40) }}</option>
                        @endforeach
                    </select>
                    @error('requirement_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="assignee_id" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Assigne a</label>
                <select name="assignee_id" id="assignee_id"
                        class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                    <option value="">—</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ old('assignee_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.anomalies.index', $project) }}" class="px-4 py-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Signaler l'anomalie
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
