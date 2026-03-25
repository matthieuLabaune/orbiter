@php
    $currentProject = request()->route('project');
    $userProjects = auth()->user()->projects ?? collect();

    $navActive = 'font-medium';
    $navInactive = '';
@endphp

<aside class="w-60 flex flex-col min-h-screen shrink-0 border-r"
       style="background: var(--orbiter-sidebar-bg); border-color: var(--orbiter-border);">

    {{-- Logo --}}
    <div class="px-5 pt-5 pb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: var(--orbiter-accent-glow);">
                <x-application-logo class="w-5 h-5" style="color: var(--orbiter-accent);" />
            </div>
            <div>
                <span class="text-base font-bold tracking-tight" style="color: var(--orbiter-text);">Orbiter</span>
                <span class="text-[9px] font-mono ml-1 opacity-40">v0.4</span>
            </div>
        </a>
    </div>

    {{-- Project selector --}}
    <div class="px-3 pb-3">
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none px-2 py-2 rounded-lg transition-all duration-150"
                     style="color: var(--orbiter-text-secondary);"
                     onmouseover="this.style.background='var(--orbiter-accent-glow)'"
                     onmouseout="this.style.background='transparent'">
                <div class="min-w-0">
                    <div class="text-[9px] uppercase tracking-[0.15em] mb-0.5" style="color: var(--orbiter-text-muted);">Projet</div>
                    <div class="text-sm font-medium truncate" style="color: var(--orbiter-text);">
                        {{ $currentProject?->name ?? 'Sélectionner...' }}
                    </div>
                </div>
                <x-lucide-chevrons-up-down class="w-3.5 h-3.5 shrink-0 opacity-40" />
            </summary>
            <div class="mt-1 space-y-0.5 pb-1">
                @foreach($userProjects as $proj)
                    <a href="{{ route('projects.show', $proj) }}"
                       class="flex items-center px-2 py-1.5 rounded-md text-sm transition-all duration-150"
                       style="color: {{ $currentProject?->id === $proj->id ? 'var(--orbiter-accent)' : 'var(--orbiter-text-secondary)' }};">
                        @if($currentProject?->id === $proj->id)
                            <span class="w-1 h-1 rounded-full mr-2 animate-pulse-dot" style="background: var(--orbiter-accent);"></span>
                        @endif
                        <span class="truncate">{{ $proj->name }}</span>
                    </a>
                @endforeach
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-1.5 px-2 py-1 rounded-md text-xs transition-colors"
                   style="color: var(--orbiter-text-muted);">
                    <x-lucide-layout-grid class="w-3 h-3" />
                    Tous les projets
                </a>
            </div>
        </details>
    </div>

    <div class="glow-line mx-3"></div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
        <div class="text-[9px] uppercase tracking-[0.15em] px-2 mb-2" style="color: var(--orbiter-text-muted);">Navigation</div>

        @php
            $links = [
                ['route' => 'dashboard', 'routeIs' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'project' => false],
            ];

            if ($currentProject) {
                $p = $currentProject;
                $links = array_merge($links, [
                    ['route' => route('projects.modules.index', $p), 'routeIs' => 'projects.modules.*', 'icon' => 'boxes', 'label' => 'Modules'],
                    ['route' => route('projects.requirements.index', $p), 'routeIs' => 'projects.requirements.*', 'icon' => 'list-checks', 'label' => 'Requirements'],
                    ['route' => route('projects.tests.index', $p), 'routeIs' => 'projects.tests.*', 'icon' => 'test-tubes', 'label' => 'Tests & V&V'],
                    ['route' => route('projects.tasks.index', $p), 'routeIs' => 'projects.tasks.*', 'icon' => 'gantt-chart', 'label' => 'Planning'],
                    ['route' => route('projects.diagrams.index', $p), 'routeIs' => 'projects.diagrams.*', 'icon' => 'network', 'label' => 'Architecture'],
                    ['route' => route('projects.adrs.index', $p), 'routeIs' => 'projects.adrs.*', 'icon' => 'file-text', 'label' => 'ADR'],
                ]);
            }
        @endphp

        @foreach($links as $link)
            @php $active = request()->routeIs($link['routeIs']); @endphp
            <a href="{{ is_string($link['route']) && !str_starts_with($link['route'], 'http') ? route($link['route']) : $link['route'] }}"
               class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg text-[13px] transition-all duration-150"
               style="color: {{ $active ? 'var(--orbiter-accent)' : 'var(--orbiter-text-secondary)' }}; background: {{ $active ? 'var(--orbiter-accent-glow)' : 'transparent' }};"
               @if(!$active)
               onmouseover="this.style.color='var(--orbiter-text)'; this.style.background='var(--orbiter-accent-glow)'"
               onmouseout="this.style.color='var(--orbiter-text-secondary)'; this.style.background='transparent'"
               @endif>
                @if($active) <span class="w-0.5 h-4 rounded-full" style="background: var(--orbiter-accent);"></span> @endif
                <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-4 h-4 shrink-0" />
                <span class="{{ $active ? 'font-medium' : '' }}">{{ $link['label'] }}</span>
            </a>
        @endforeach

        @if($currentProject)
            @php $p = $currentProject; @endphp
            <div class="text-[9px] uppercase tracking-[0.15em] px-2 mt-5 mb-2" style="color: var(--orbiter-text-muted);">Industriel</div>

            @php
                $industrialLinks = [
                    ['route' => route('projects.deploy-readiness', $p), 'routeIs' => 'projects.deploy-readiness', 'icon' => 'rocket', 'label' => 'Deploy Readiness'],
                    ['route' => route('projects.anomalies.index', $p), 'routeIs' => 'projects.anomalies.*', 'icon' => 'triangle-alert', 'label' => 'Anomalies'],
                    ['route' => route('projects.lessons.index', $p), 'routeIs' => 'projects.lessons.*', 'icon' => 'lightbulb', 'label' => 'Lessons'],
                    ['route' => route('projects.baselines.index', $p), 'routeIs' => 'projects.baselines.*', 'icon' => 'archive', 'label' => 'Baselines'],
                ];
            @endphp

            @foreach($industrialLinks as $link)
                @php $active = request()->routeIs($link['routeIs']); @endphp
                <a href="{{ $link['route'] }}"
                   class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg text-[13px] transition-all duration-150"
                   style="color: {{ $active ? 'var(--orbiter-accent)' : 'var(--orbiter-text-secondary)' }}; background: {{ $active ? 'var(--orbiter-accent-glow)' : 'transparent' }};"
                   @if(!$active)
                   onmouseover="this.style.color='var(--orbiter-text)'; this.style.background='var(--orbiter-accent-glow)'"
                   onmouseout="this.style.color='var(--orbiter-text-secondary)'; this.style.background='transparent'"
                   @endif>
                    @if($active) <span class="w-0.5 h-4 rounded-full" style="background: var(--orbiter-accent);"></span> @endif
                    <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-4 h-4 shrink-0" />
                    <span class="{{ $active ? 'font-medium' : '' }}">{{ $link['label'] }}</span>
                </a>
            @endforeach
        @endif
    </nav>

    {{-- User --}}
    <div class="px-3 py-3 border-t" style="border-color: var(--orbiter-border);">
        <div class="flex items-center gap-2.5 px-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-medium" style="background: var(--orbiter-accent-glow); color: var(--orbiter-accent);">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium truncate" style="color: var(--orbiter-text);">{{ auth()->user()->name ?? 'User' }}</div>
            </div>
        </div>
    </div>
</aside>
