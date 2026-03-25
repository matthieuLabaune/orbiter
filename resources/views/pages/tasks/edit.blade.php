<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="text-slate-400 hover:text-white transition-colors">
                <x-lucide-arrow-left class="w-5 h-5" />
            </a>
            <h2 class="text-xl font-semibold text-white">Modifier la tâche</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST"
              class="bg-white dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700/50 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required
                       class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="module_id" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Module</label>
                    <select name="module_id" id="module_id" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="">—</option>
                        @foreach($modules as $m) <option value="{{ $m->id }}" {{ old('module_id', $task->module_id) == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="assignee_id" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Assigné à</label>
                    <select name="assignee_id" id="assignee_id" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="">—</option>
                        @foreach($members as $m) <option value="{{ $m->id }}" {{ old('assignee_id', $task->assignee_id) == $m->id ? 'selected' : '' }}>{{ $m->name }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="requirement_id" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Exigence liée</label>
                    <select name="requirement_id" id="requirement_id" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        <option value="">—</option>
                        @foreach($requirements as $r) <option value="{{ $r->id }}" {{ old('requirement_id', $task->requirement_id) == $r->id ? 'selected' : '' }}>{{ $r->ref }} — {{ Str::limit($r->title, 40) }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Statut</label>
                    <select name="status" id="status" required class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé', 'blocked' => 'Bloqué'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $task->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="progress" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Progression (%)</label>
                    <input type="number" name="progress" id="progress" value="{{ old('progress', $task->progress) }}" min="0" max="100"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Début</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-1">Fin</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $task->end_date?->format('Y-m-d')) }}"
                           class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-blue-500">
                </div>
            </div>

            @if($allTasks->isNotEmpty())
                @php $blockedByIds = $task->blockedBy->pluck('id')->toArray(); @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-600 dark:text-slate-300 mb-2">Bloqué par</label>
                    <div class="max-h-32 overflow-y-auto space-y-1.5 border border-gray-200 dark:border-slate-700/50 rounded-lg p-3">
                        @foreach($allTasks as $t)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="blocked_by[]" value="{{ $t->id }}"
                                       {{ in_array($t->id, old('blocked_by', $blockedByIds)) ? 'checked' : '' }}
                                       class="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500">
                                <span class="text-gray-600 dark:text-slate-300">{{ $t->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="px-4 py-2 text-sm text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">Enregistrer</button>
            </div>
        </form>

        <div class="mt-8 bg-red-500/5 border border-red-500/20 rounded-xl p-6">
            <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" onsubmit="return confirm('Supprimer cette tâche ?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-sm rounded-lg border border-red-500/30 transition-colors">Supprimer</button>
            </form>
        </div>
    </div>
</x-app-layout>
