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
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.requirements.index', $project) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    <x-lucide-list-checks class="w-4 h-4" />
                    Requirements
                </a>
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="inline-flex items-center gap-2 px-3 py-1.5 text-sm text-slate-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                        <x-lucide-settings class="w-4 h-4" />
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
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

        {{-- Health + Alerts row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <livewire:dashboard.health-widget :project="$project" />
            </div>
            <div class="space-y-6">
                <livewire:dashboard.alerts-widget :project="$project" />
            </div>
        </div>

        {{-- Activity feed + Members --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <livewire:dashboard.activity-feed :project="$project" />
            </div>
            <div>
                {{-- Members --}}
                <div class="bg-slate-900/80 border border-slate-700/50 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider">Membres</h3>
                        @can('manageMembers', $project)
                            <button onclick="document.getElementById('add-member-dialog').showModal()"
                                    class="text-xs text-slate-500 hover:text-white transition-colors cursor-pointer">
                                <x-lucide-user-plus class="w-4 h-4" />
                            </button>
                        @endcan
                    </div>
                    <div class="space-y-3">
                        @foreach($project->members as $member)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-700 flex items-center justify-center text-xs font-medium text-slate-300">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-200">{{ $member->name }}</div>
                                        <div class="text-[10px] text-slate-600">{{ $member->email }}</div>
                                    </div>
                                </div>
                                <x-ui.badge :color="$member->pivot->role === 'owner' ? 'amber' : ($member->pivot->role === 'member' ? 'blue' : 'slate')">
                                    {{ $member->pivot->role }}
                                </x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                </div>
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
                        <label for="role" class="block text-sm font-medium text-slate-300 mb-1">Role</label>
                        <select name="role" id="role"
                                class="w-full bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="member">Member</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                        Ajouter
                    </button>
                </form>
            </x-ui.dialog>
        @endcan
    </div>
</x-app-layout>
