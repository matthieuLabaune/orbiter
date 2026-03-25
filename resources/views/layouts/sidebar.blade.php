<aside class="w-64 bg-slate-900 border-r border-slate-700/50 flex flex-col min-h-screen shrink-0">
    <!-- Logo -->
    <div class="p-4 border-b border-slate-700/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-xl font-bold text-white tracking-tight">Orbiter</span>
            <span class="text-xs text-slate-500 font-mono">v1</span>
        </a>
    </div>

    <!-- Project selector -->
    <div class="px-3 py-3 border-b border-slate-700/50">
        <div class="text-xs text-slate-500 uppercase tracking-wider mb-1">Projet</div>
        @php $currentProject = request()->route('project'); @endphp
        <div class="text-sm text-slate-200 font-medium truncate">
            {{ $currentProject?->name ?? 'Sélectionner...' }}
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <div class="text-xs text-slate-500 uppercase tracking-wider px-2 mb-2">Navigation</div>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
            <x-lucide-layout-dashboard class="w-4 h-4" />
            Dashboard
        </a>

        @if($currentProject)
            @php $p = $currentProject; @endphp

            <a href="{{ route('projects.modules.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.modules.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-boxes class="w-4 h-4" />
                Modules
            </a>
            <a href="{{ route('projects.requirements.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.requirements.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-list-checks class="w-4 h-4" />
                Requirements
            </a>
            <a href="{{ route('projects.tests.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.tests.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-test-tubes class="w-4 h-4" />
                Tests & V&V
            </a>
            <a href="{{ route('projects.tasks.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.tasks.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-gantt-chart class="w-4 h-4" />
                Planning
            </a>
            <a href="{{ route('projects.diagrams.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.diagrams.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-network class="w-4 h-4" />
                Architecture
            </a>
            <a href="{{ route('projects.adrs.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.adrs.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <x-lucide-file-text class="w-4 h-4" />
                ADR
            </a>

            <div class="text-xs text-slate-500 uppercase tracking-wider px-2 mt-4 mb-2">Industriel</div>
            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                <x-lucide-rocket class="w-4 h-4" />
                Deploy Readiness
            </a>
            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                <x-lucide-triangle-alert class="w-4 h-4" />
                Anomalies
            </a>
            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                <x-lucide-lightbulb class="w-4 h-4" />
                Lessons Learned
            </a>
            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm text-slate-400 hover:text-white hover:bg-slate-800/50 transition-colors">
                <x-lucide-archive class="w-4 h-4" />
                Baselines
            </a>
        @endif
    </nav>

    <!-- User -->
    <div class="p-3 border-t border-slate-700/50">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-slate-700 flex items-center justify-center text-xs font-medium text-slate-300">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm text-slate-200 truncate">{{ auth()->user()->name ?? 'User' }}</div>
            </div>
        </div>
    </div>
</aside>
