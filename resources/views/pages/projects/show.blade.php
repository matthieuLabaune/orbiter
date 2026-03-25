<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="text-slate-400 hover:text-white transition-colors">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $project->name }}</h2>
                    <span class="text-xs text-slate-500 font-mono">{{ $project->slug }}</span>
                </div>
            </div>
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    <x-lucide-settings class="w-4 h-4" />
                    Paramètres
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        @if(session('success'))
            <div class="px-4 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-400 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg text-red-400 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Description --}}
        @if($project->description)
            <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                <p class="text-slate-300">{{ $project->description }}</p>
            </div>
        @endif

        {{-- Module Health Overview --}}
        <div>
            <h3 class="text-lg font-semibold text-white mb-4">Santé par module</h3>
            @if($moduleHealth->isEmpty())
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-8 text-center">
                    <x-lucide-boxes class="w-8 h-8 text-slate-600 mx-auto mb-3" />
                    <p class="text-slate-500">Aucun module. Créez votre premier module pour commencer.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($moduleHealth as $item)
                        <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-white">{{ $item['module']->name }}</h4>
                                <x-ui.badge :color="$item['module']->status === 'active' ? 'emerald' : 'slate'">
                                    {{ $item['module']->status }}
                                </x-ui.badge>
                            </div>
                            <x-project.module-health :health="$item['health']" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Members --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Membres</h3>
                @can('manageMembers', $project)
                    <button onclick="document.getElementById('add-member-dialog').showModal()"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors cursor-pointer">
                        <x-lucide-user-plus class="w-4 h-4" />
                        Ajouter
                    </button>
                @endcan
            </div>

            <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                        <tr>
                            <th class="px-4 py-3 text-left">Membre</th>
                            <th class="px-4 py-3 text-left">Rôle</th>
                            @can('manageMembers', $project)
                                <th class="px-4 py-3 text-right">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @foreach($project->members as $member)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-medium text-slate-300">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-slate-200">{{ $member->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $member->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <x-ui.badge :color="$member->pivot->role === 'owner' ? 'amber' : ($member->pivot->role === 'member' ? 'blue' : 'slate')">
                                        {{ $member->pivot->role }}
                                    </x-ui.badge>
                                </td>
                                @can('manageMembers', $project)
                                    <td class="px-4 py-3 text-right">
                                        @if($member->id !== auth()->id())
                                            <form action="{{ route('projects.members.destroy', [$project, $member]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors cursor-pointer">
                                                    <x-lucide-x class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add member dialog --}}
        @can('manageMembers', $project)
            <x-ui.dialog id="add-member-dialog" title="Ajouter un membre">
                <form action="{{ route('projects.members.store', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                               class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="user@example.com">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-1">Rôle</label>
                        <select name="role" id="role"
                                class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="member">Member</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                        Ajouter
                    </button>
                </form>
            </x-ui.dialog>
        @endcan
    </div>
</x-app-layout>
