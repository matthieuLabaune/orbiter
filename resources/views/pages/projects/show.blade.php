<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="hover:opacity-80 transition-colors" style="color: var(--orbiter-text-muted);">
                    <x-lucide-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-xl font-semibold" style="color: var(--orbiter-text);">{{ $project->name }}</h2>
                    <span class="text-xs font-mono" style="color: var(--orbiter-text-muted);">{{ $project->slug }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.requirements.index', $project) }}"
                   class="btn-secondary inline-flex items-center gap-2 px-3 py-1.5 text-sm transition-colors">
                    <x-lucide-list-checks class="w-4 h-4" />
                    Requirements
                </a>
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="btn-secondary inline-flex items-center gap-2 px-3 py-1.5 text-sm transition-colors">
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
            <div class="surface p-5">
                <p style="color: var(--orbiter-text-secondary);">{{ $project->description }}</p>
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
                <div class="surface p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium uppercase tracking-wider" style="color: var(--orbiter-text-muted);">Membres</h3>
                        @can('manageMembers', $project)
                            <button onclick="document.getElementById('add-member-dialog').showModal()"
                                    class="text-xs hover:opacity-80 transition-colors cursor-pointer" style="color: var(--orbiter-text-muted);">
                                <x-lucide-user-plus class="w-4 h-4" />
                            </button>
                        @endcan
                    </div>
                    <div class="space-y-3">
                        @foreach($project->members as $member)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-medium" style="background: var(--orbiter-surface-2); color: var(--orbiter-text-secondary);">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm" style="color: var(--orbiter-text);">{{ $member->name }}</div>
                                        <div class="text-[10px]" style="color: var(--orbiter-text-muted);">{{ $member->email }}</div>
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
                        <label for="email" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Email</label>
                        <input type="email" name="email" id="email" required
                               class="input-field w-full rounded-lg px-3 py-2 transition-colors"
                               placeholder="user@example.com">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium mb-1" style="color: var(--orbiter-text-secondary);">Role</label>
                        <select name="role" id="role"
                                class="input-field w-full rounded-lg px-3 py-2 transition-colors">
                            <option value="member">Member</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary w-full px-4 py-2 text-sm font-medium transition-colors">
                        Ajouter
                    </button>
                </form>
            </x-ui.dialog>
        @endcan
    </div>
</x-app-layout>
