@php
    $currentProject = request()->route('project');
    $userProjects = auth()->user()->projects ?? collect();
@endphp

<aside class="w-[220px] flex flex-col min-h-screen shrink-0"
       style="background: var(--o-sidebar-bg); border-right: 1px solid var(--o-sidebar-border);">

    {{-- Logo --}}
    <div class="px-4 pt-4 pb-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="w-6 h-6" style="color: var(--o-accent);" />
            <span class="text-[15px] font-semibold" style="color: var(--o-text);">Orbiter</span>
        </a>
    </div>

    {{-- Project selector --}}
    <div class="px-3 pb-2">
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none px-2 py-2 rounded-xl transition-all"
                     style="color: var(--o-text-2);"
                     onmouseover="this.style.background='var(--o-surface-2)'"
                     onmouseout="this.style.background='transparent'">
                <div class="min-w-0">
                    <div class="text-[10px] font-medium uppercase tracking-widest mb-0.5" style="color: var(--o-text-4);">Projet</div>
                    <div class="text-[13px] font-medium truncate" style="color: var(--o-text);">
                        {{ $currentProject?->name ?? 'Sélectionner' }}
                    </div>
                </div>
                <x-lucide-chevrons-up-down class="w-3.5 h-3.5 shrink-0" style="color: var(--o-text-4);" />
            </summary>
            <div class="mt-1 space-y-0.5">
                @foreach($userProjects as $proj)
                    @php $isActive = $currentProject?->id === $proj->id; @endphp
                    <a href="{{ route('projects.show', $proj) }}"
                       class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-[13px] transition-all"
                       style="color: {{ $isActive ? 'var(--o-accent)' : 'var(--o-text-2)' }}; background: {{ $isActive ? 'var(--o-sidebar-active)' : 'transparent' }};">
                        @if($isActive) <span class="w-1.5 h-1.5 rounded-full animate-pulse-dot" style="background: var(--o-accent);"></span> @endif
                        <span class="truncate">{{ $proj->name }}</span>
                    </a>
                @endforeach
                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-[11px] transition-colors"
                   style="color: var(--o-text-4);">
                    <x-lucide-layout-grid class="w-3 h-3" />
                    Tous les projets
                </a>
            </div>
        </details>
    </div>

    <div class="mx-4 mb-2" style="height: 1px; background: var(--o-border);"></div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-1 space-y-0.5 overflow-y-auto">
        @php
            $mainLinks = [
                ['route' => route('dashboard'), 'routeIs' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
            ];
            if ($currentProject) {
                $p = $currentProject;
                $mainLinks = array_merge($mainLinks, [
                    ['route' => route('projects.modules.index', $p), 'routeIs' => 'projects.modules.*', 'icon' => 'boxes', 'label' => 'Modules'],
                    ['route' => route('projects.user-stories.index', $p), 'routeIs' => 'projects.user-stories.*', 'icon' => 'book-open', 'label' => 'User Stories'],
                    ['route' => route('projects.requirements.index', $p), 'routeIs' => 'projects.requirements.*', 'icon' => 'list-checks', 'label' => 'Requirements'],
                    ['route' => route('projects.tests.index', $p), 'routeIs' => 'projects.tests.*', 'icon' => 'test-tubes', 'label' => 'Tests & V&V'],
                    ['route' => route('projects.tasks.index', $p), 'routeIs' => 'projects.tasks.*', 'icon' => 'gantt-chart', 'label' => 'Planning'],
                    ['route' => route('projects.diagrams.index', $p), 'routeIs' => 'projects.diagrams.*', 'icon' => 'network', 'label' => 'Architecture'],
                    ['route' => route('projects.adrs.index', $p), 'routeIs' => 'projects.adrs.*', 'icon' => 'file-text', 'label' => 'ADR'],
                ]);
            }
        @endphp

        @foreach($mainLinks as $link)
            @php $active = request()->routeIs($link['routeIs']); @endphp
            <a href="{{ $link['route'] }}"
               class="flex items-center gap-2.5 px-2.5 py-[7px] rounded-xl text-[13px] transition-all"
               style="color: {{ $active ? 'var(--o-accent)' : 'var(--o-text-3)' }}; background: {{ $active ? 'var(--o-sidebar-active)' : 'transparent' }}; font-weight: {{ $active ? '600' : '400' }};"
               @if(!$active)
               onmouseover="this.style.background='var(--o-surface-2)'; this.style.color='var(--o-text)'"
               onmouseout="this.style.background='transparent'; this.style.color='var(--o-text-3)'"
               @endif>
                <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-[16px] h-[16px] shrink-0" />
                {{ $link['label'] }}
            </a>
        @endforeach

        @if($currentProject)
            @php $p = $currentProject; @endphp
            <div class="pt-3 pb-1 px-2">
                <span class="text-[10px] font-medium uppercase tracking-widest" style="color: var(--o-text-4);">Industriel</span>
            </div>

            @php
                $indLinks = [
                    ['route' => route('projects.deploy-readiness', $p), 'routeIs' => 'projects.deploy-readiness', 'icon' => 'rocket', 'label' => 'Deploy Readiness'],
                    ['route' => route('projects.anomalies.index', $p), 'routeIs' => 'projects.anomalies.*', 'icon' => 'triangle-alert', 'label' => 'Anomalies'],
                    ['route' => route('projects.lessons.index', $p), 'routeIs' => 'projects.lessons.*', 'icon' => 'lightbulb', 'label' => 'Lessons'],
                    ['route' => route('projects.baselines.index', $p), 'routeIs' => 'projects.baselines.*', 'icon' => 'archive', 'label' => 'Baselines'],
                ];
            @endphp

            @foreach($indLinks as $link)
                @php $active = request()->routeIs($link['routeIs']); @endphp
                <a href="{{ $link['route'] }}"
                   class="flex items-center gap-2.5 px-2.5 py-[7px] rounded-xl text-[13px] transition-all"
                   style="color: {{ $active ? 'var(--o-accent)' : 'var(--o-text-3)' }}; background: {{ $active ? 'var(--o-sidebar-active)' : 'transparent' }}; font-weight: {{ $active ? '600' : '400' }};"
                   @if(!$active)
                   onmouseover="this.style.background='var(--o-surface-2)'; this.style.color='var(--o-text)'"
                   onmouseout="this.style.background='transparent'; this.style.color='var(--o-text-3)'"
                   @endif>
                    <x-dynamic-component :component="'lucide-' . $link['icon']" class="w-[16px] h-[16px] shrink-0" />
                    {{ $link['label'] }}
                </a>
            @endforeach
        @endif
    </nav>

    {{-- User --}}
    <div class="px-3 py-3" style="border-top: 1px solid var(--o-border);">
        <div class="flex items-center gap-2.5 px-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold"
                 style="background: var(--o-accent-bg); color: var(--o-accent);">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <span class="text-[13px] font-medium truncate" style="color: var(--o-text-2);">{{ auth()->user()->name ?? 'User' }}</span>
        </div>
    </div>
</aside>
