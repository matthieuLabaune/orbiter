<aside class="w-64 bg-white border-r border-gray-200 dark:bg-slate-900 dark:border-slate-700/50 flex flex-col min-h-screen shrink-0">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-200 dark:border-slate-700/50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Orbiter</span>
            <span class="text-xs text-gray-400 dark:text-slate-500 font-mono">v1</span>
        </a>
    </div>

    <!-- Project selector -->
    @php
        $currentProject = request()->route('project');
        $userProjects = auth()->user()->projects ?? collect();
    @endphp
    <div class="px-3 py-2 border-b border-gray-200 dark:border-slate-700/50">
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none px-2 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                <div class="min-w-0">
                    <div class="text-[10px] text-gray-400 dark:text-slate-500 uppercase tracking-wider">Projet</div>
                    <div class="text-sm text-gray-800 dark:text-slate-200 font-medium truncate">
                        {{ $currentProject?->name ?? 'Sélectionner...' }}
                    </div>
                </div>
                <x-lucide-chevrons-up-down class="w-4 h-4 text-gray-400 dark:text-slate-500 shrink-0" />
            </summary>
            <div class="mt-1 space-y-0.5">
                @foreach($userProjects as $proj)
                    <a href="{{ route('projects.show', $proj) }}"
                       class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ $currentProject?->id === $proj->id ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' }}">
                        <span class="truncate">{{ $proj->name }}</span>
                    </a>
                @endforeach
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors">
                    <x-lucide-layout-grid class="w-3 h-3" />
                    Tous les projets
                </a>
            </div>
        </details>
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

            <div class="text-xs text-gray-400 dark:text-slate-500 uppercase tracking-wider px-2 mt-4 mb-2">Industriel</div>
            <a href="{{ route('projects.deploy-readiness', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.deploy-readiness') ? 'bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' }}">
                <x-lucide-rocket class="w-4 h-4" />
                Deploy Readiness
            </a>
            <a href="{{ route('projects.anomalies.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.anomalies.*') ? 'bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' }}">
                <x-lucide-triangle-alert class="w-4 h-4" />
                Anomalies
            </a>
            <a href="{{ route('projects.lessons.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.lessons.*') ? 'bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' }}">
                <x-lucide-lightbulb class="w-4 h-4" />
                Lessons Learned
            </a>
            <a href="{{ route('projects.baselines.index', $p) }}"
               class="flex items-center gap-2 px-2 py-1.5 rounded-md text-sm transition-colors {{ request()->routeIs('projects.baselines.*') ? 'bg-gray-100 dark:bg-slate-800 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50' }}">
                <x-lucide-archive class="w-4 h-4" />
                Baselines
            </a>
        @endif
    </nav>

    <!-- Theme toggle + User -->
    <div class="p-3 border-t border-gray-200 dark:border-slate-700/50 space-y-3">
        <button @click="darkMode = !darkMode"
                class="flex items-center gap-2 w-full px-2 py-1.5 rounded-md text-sm text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
            <x-lucide-sun class="w-4 h-4" x-show="darkMode" />
            <x-lucide-moon class="w-4 h-4" x-show="!darkMode" />
            <span x-text="darkMode ? 'Mode clair' : 'Mode sombre'"></span>
        </button>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-slate-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-slate-300">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm text-gray-800 dark:text-slate-200 truncate">{{ auth()->user()->name ?? 'User' }}</div>
            </div>
        </div>
    </div>
</aside>
