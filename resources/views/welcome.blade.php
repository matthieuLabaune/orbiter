<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orbiter — Project management built for traceability</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .stars {
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 60%, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 50% 10%, rgba(255,255,255,0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.35) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 40%, rgba(255,255,255,0.25) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 15% 85%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 55% 45%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 15%, rgba(255,255,255,0.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 35%, rgba(255,255,255,0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 65% 70%, rgba(255,255,255,0.15) 0%, transparent 100%);
        }
        .glow { text-shadow: 0 0 40px rgba(59, 130, 246, 0.3); }
    </style>
</head>
<body class="bg-[#050a18] text-slate-200 font-sans antialiased stars min-h-screen">

    {{-- Nav --}}
    <nav class="max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-2">
            {{-- Logo: simple planet with ring --}}
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" class="text-blue-400">
                <circle cx="16" cy="16" r="8" fill="currentColor" opacity="0.2"/>
                <circle cx="16" cy="16" r="8" stroke="currentColor" stroke-width="1.5"/>
                <ellipse cx="16" cy="16" rx="14" ry="5" stroke="currentColor" stroke-width="1" opacity="0.6" transform="rotate(-20 16 16)"/>
            </svg>
            <span class="text-xl font-bold text-white tracking-tight">Orbiter</span>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Connexion</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                    Commencer
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-4xl mx-auto px-6 pt-20 pb-24 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-medium mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
            Open Source — MIT License
        </div>
        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight mb-6 glow">
            Project management built for
            <span class="text-blue-400">traceability</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
            Traçabilité complète exigence &rarr; test &rarr; code &rarr; preuve.
            Inspiré de l'ingénierie système NASA/SpaceX, adapté au logiciel.
            Exploitable par des humains <em>et</em> par des agents IA.
        </p>
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-colors text-sm">
                Commencer gratuitement
            </a>
            <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener"
               class="px-6 py-3 border border-slate-700 hover:border-slate-600 text-slate-300 hover:text-white font-medium rounded-lg transition-colors text-sm flex items-center gap-2">
                <x-lucide-github class="w-4 h-4" />
                GitHub
            </a>
        </div>
    </section>

    {{-- Concepts --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['icon' => 'git-branch', 'title' => 'Traçabilité bidirectionnelle', 'desc' => 'De l\'exigence au commit, du commit à l\'exigence. Si un lien manque, Orbiter le signale.', 'color' => 'blue'],
                ['icon' => 'shield-check', 'title' => 'V&V séparées', 'desc' => 'Vérifié (tests CI) ≠ Validé (client confirme). Les deux sont trackés séparément.', 'color' => 'emerald'],
                ['icon' => 'triangle-alert', 'title' => 'Risk Score FMEA', 'desc' => 'Impact × Probabilité × (6 - Détectabilité). On teste en priorité ce qui est risqué.', 'color' => 'amber'],
                ['icon' => 'rocket', 'title' => 'Deploy Readiness', 'desc' => 'Check GO/NO-GO automatique par module avant chaque déploiement. Inspiré NASA.', 'color' => 'red'],
                ['icon' => 'brain', 'title' => 'Context Brief IA', 'desc' => 'Briefing contextuel par exigence — REQ + tests + ADR + lessons — consommable par Claude Code.', 'color' => 'purple'],
                ['icon' => 'archive', 'title' => 'Configuration Baseline', 'desc' => 'Snapshot immuable de l\'état du projet à chaque release. Comparez v2.3 et v2.4 en un clic.', 'color' => 'slate'],
            ] as $concept)
                @php
                    $borderColors = ['blue' => 'border-blue-500/20 hover:border-blue-500/40', 'emerald' => 'border-emerald-500/20 hover:border-emerald-500/40', 'amber' => 'border-amber-500/20 hover:border-amber-500/40', 'red' => 'border-red-500/20 hover:border-red-500/40', 'purple' => 'border-purple-500/20 hover:border-purple-500/40', 'slate' => 'border-slate-700/50 hover:border-slate-600/50'];
                    $iconColors = ['blue' => 'text-blue-400', 'emerald' => 'text-emerald-400', 'amber' => 'text-amber-400', 'red' => 'text-red-400', 'purple' => 'text-purple-400', 'slate' => 'text-slate-400'];
                @endphp
                <div class="bg-slate-900/40 border {{ $borderColors[$concept['color']] }} rounded-xl p-6 transition-colors">
                    <x-dynamic-component :component="'lucide-' . $concept['icon']" class="w-6 h-6 {{ $iconColors[$concept['color']] }} mb-3" />
                    <h3 class="text-white font-semibold mb-2">{{ $concept['title'] }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">{{ $concept['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section class="max-w-4xl mx-auto px-6 pb-24">
        <h2 class="text-2xl font-bold text-white text-center mb-12">Comment ça marche</h2>
        <div class="flex flex-col md:flex-row items-center gap-4">
            @foreach([
                ['step' => '1', 'title' => 'Formalisez', 'desc' => 'Écrivez vos exigences avec critères d\'acceptation et score de risque'],
                ['step' => '2', 'title' => 'Testez', 'desc' => 'Liez des tests, exécutez-les. Le statut V&V se met à jour automatiquement'],
                ['step' => '3', 'title' => 'Codez', 'desc' => 'Mentionnez REQ-XXX dans vos commits. Orbiter trace le lien automatiquement'],
                ['step' => '4', 'title' => 'Déployez', 'desc' => 'Le Deploy Readiness vérifie que tout est GO avant le déploiement'],
            ] as $step)
                <div class="flex-1 text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-400 font-bold text-sm flex items-center justify-center mx-auto mb-3">
                        {{ $step['step'] }}
                    </div>
                    <h3 class="text-white font-semibold mb-1">{{ $step['title'] }}</h3>
                    <p class="text-xs text-slate-500">{{ $step['desc'] }}</p>
                </div>
                @if(!$loop->last)
                    <x-lucide-chevron-right class="w-5 h-5 text-slate-700 hidden md:block shrink-0" />
                @endif
            @endforeach
        </div>
    </section>

    {{-- Stack --}}
    <section class="max-w-4xl mx-auto px-6 pb-24 text-center">
        <h2 class="text-2xl font-bold text-white mb-4">Simplicité radicale</h2>
        <p class="text-slate-400 mb-8 max-w-xl mx-auto">
            Pas de Vue, pas de React, pas de SPA. Le navigateur fait le travail.
        </p>
        <div class="inline-block bg-slate-900/60 border border-slate-700/50 rounded-xl p-6 text-left">
            <pre class="text-sm text-slate-300 font-mono leading-relaxed"><span class="text-blue-400">Laravel 13</span> (PHP 8.4)
├── <span class="text-emerald-400">Blade Components</span> (UI)
├── <span class="text-emerald-400">Livewire 4</span> (réactivité)
├── <span class="text-emerald-400">Tailwind CSS 4</span> (styling)
├── <span class="text-amber-400">PostgreSQL 16</span> (JSONB)
├── <span class="text-amber-400">FrankenPHP</span> (Octane)
├── <span class="text-slate-400">Pest PHP</span> (tests)
└── <span class="text-purple-400">Mermaid.js</span> + <span class="text-purple-400">frappe-gantt</span></pre>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="max-w-6xl mx-auto px-6 py-8 border-t border-slate-800/50 flex items-center justify-between text-xs text-slate-600">
        <div class="flex items-center gap-2">
            <svg width="20" height="20" viewBox="0 0 32 32" fill="none" class="text-slate-600">
                <circle cx="16" cy="16" r="8" stroke="currentColor" stroke-width="1.5"/>
                <ellipse cx="16" cy="16" rx="14" ry="5" stroke="currentColor" stroke-width="1" opacity="0.6" transform="rotate(-20 16 16)"/>
            </svg>
            Orbiter — MIT License
        </div>
        <div class="flex items-center gap-4">
            <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener" class="hover:text-slate-400 transition-colors">GitHub</a>
        </div>
    </footer>

</body>
</html>
