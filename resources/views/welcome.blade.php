<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orbiter — Project management built for traceability</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=instrument-serif:400,400i&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', system-ui, sans-serif; }
        .font-display { font-family: 'Instrument Serif', Georgia, serif; }

        .stars {
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(6,182,212,0.3) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 60%, rgba(255,255,255,0.15) 0%, transparent 100%),
                radial-gradient(1px 1px at 50% 10%, rgba(6,182,212,0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.2) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 40%, rgba(6,182,212,0.15) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 15% 85%, rgba(255,255,255,0.25) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 55% 45%, rgba(6,182,212,0.25) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 15%, rgba(255,255,255,0.15) 0%, transparent 100%);
        }

        .hero-glow {
            background: radial-gradient(ellipse 600px 400px at 50% 0%, rgba(6,182,212,0.08), transparent);
        }

        .card-hover { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-2px); border-color: rgba(6,182,212,0.3); box-shadow: 0 0 30px rgba(6,182,212,0.06); }
    </style>
</head>
<body class="bg-[#030712] text-slate-300 antialiased stars min-h-screen">

    {{-- Nav --}}
    <nav class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(6,182,212,0.12);">
                <x-application-logo class="w-5 h-5 text-cyan-400" />
            </div>
            <span class="text-lg font-bold text-white tracking-tight">Orbiter</span>
        </a>
        <div class="flex items-center gap-5">
            <a href="{{ route('methodology') }}" class="text-sm text-slate-500 hover:text-slate-300 transition-colors">Méthodologie</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm">Commencer</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-4xl mx-auto px-6 pt-24 pb-28 text-center relative">
        <div class="hero-glow absolute inset-0 pointer-events-none"></div>
        <div class="relative">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium mb-8"
                 style="background: rgba(6,182,212,0.08); border: 1px solid rgba(6,182,212,0.15); color: #06b6d4;">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse-dot"></span>
                Open Source — MIT License
            </div>
            <h1 class="text-5xl md:text-7xl font-bold text-white leading-[1.1] mb-6 tracking-tight">
                Project management<br>
                built for <span class="font-display italic text-cyan-400">traceability</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Traçabilité complète exigence &rarr; test &rarr; code &rarr; preuve.<br>
                Inspiré de l'ingénierie système NASA/SpaceX.
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary px-6 py-3">
                    Commencer gratuitement
                </a>
                <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener"
                   class="btn-secondary px-6 py-3 flex items-center gap-2">
                    <x-lucide-github class="w-4 h-4" />
                    GitHub
                </a>
            </div>
        </div>
    </section>

    {{-- Concepts --}}
    <section class="max-w-6xl mx-auto px-6 pb-28">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['icon' => 'git-branch', 'title' => 'Traçabilité bidirectionnelle', 'desc' => 'De l\'exigence au commit, du commit à l\'exigence. Si un lien manque, Orbiter le signale.'],
                ['icon' => 'shield-check', 'title' => 'V&V séparées', 'desc' => 'Vérifié (tests CI) ≠ Validé (client confirme). Les deux sont trackés séparément.'],
                ['icon' => 'triangle-alert', 'title' => 'Risk Score FMEA', 'desc' => 'Impact × Probabilité × (6 - Détectabilité). On teste en priorité ce qui est risqué.'],
                ['icon' => 'rocket', 'title' => 'Deploy Readiness', 'desc' => 'Check GO/NO-GO automatique par module avant chaque déploiement. Inspiré NASA.'],
                ['icon' => 'brain', 'title' => 'Context Brief IA', 'desc' => 'Briefing contextuel par exigence — consommable par Claude Code ou tout agent IA.'],
                ['icon' => 'archive', 'title' => 'Configuration Baseline', 'desc' => 'Snapshot immuable de l\'état du projet à chaque release.'],
            ] as $concept)
                <div class="card-hover rounded-xl p-6" style="background: rgba(15,23,42,0.5); border: 1px solid rgba(51,65,85,0.3);">
                    <x-dynamic-component :component="'lucide-' . $concept['icon']" class="w-5 h-5 text-cyan-400 mb-4" />
                    <h3 class="text-white font-semibold mb-2 text-[15px]">{{ $concept['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $concept['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section class="max-w-4xl mx-auto px-6 pb-28">
        <h2 class="text-2xl font-bold text-white text-center mb-12 tracking-tight">Comment ça marche</h2>
        <div class="flex flex-col md:flex-row items-start gap-0">
            @foreach([
                ['step' => '01', 'title' => 'Formalisez', 'desc' => 'Écrivez vos exigences avec critères d\'acceptation et score de risque'],
                ['step' => '02', 'title' => 'Testez', 'desc' => 'Liez des tests, exécutez-les. Le statut V&V se met à jour automatiquement'],
                ['step' => '03', 'title' => 'Codez', 'desc' => 'Mentionnez REQ-XXX dans vos commits. Orbiter trace le lien automatiquement'],
                ['step' => '04', 'title' => 'Déployez', 'desc' => 'Le Deploy Readiness vérifie que tout est GO avant le déploiement'],
            ] as $step)
                <div class="flex-1 text-center px-4 relative">
                    <div class="text-[10px] font-mono text-cyan-500 mb-2 tracking-[0.2em]">{{ $step['step'] }}</div>
                    <h3 class="text-white font-semibold mb-1.5">{{ $step['title'] }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                    @if(!$loop->last)
                        <div class="hidden md:block absolute right-0 top-4 w-px h-8" style="background: linear-gradient(180deg, transparent, rgba(6,182,212,0.3), transparent);"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Comparison --}}
    <section class="max-w-5xl mx-auto px-6 pb-28">
        <h2 class="text-2xl font-bold text-white text-center mb-3 tracking-tight">Pourquoi pas Jira ?</h2>
        <p class="text-slate-500 text-center text-sm mb-10">La gestion de projet classique n'a pas été conçue pour la traçabilité.</p>
        <div class="rounded-xl overflow-hidden" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(51,65,85,0.3);">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(51,65,85,0.3);">
                        <th class="px-5 py-3 text-left text-slate-600 font-medium w-1/4">Aspect</th>
                        <th class="px-5 py-3 text-left text-slate-500 font-medium">Jira / Linear / Notion</th>
                        <th class="px-5 py-3 text-left text-cyan-400 font-medium">Orbiter</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['Avancement', 'Tickets fermés / total', '4 axes : formalisation → couverture → vérification → validation'],
                        ['Traçabilité', 'Liens manuels entre tickets', 'Auto-parsing REQ-XXX dans commits et PR — zéro effort'],
                        ['Tests', 'CI passe ou échoue globalement', 'Chaque test tracé vers une exigence, statut V&V par REQ'],
                        ['Risque', 'Estimation subjective (S/M/L)', 'Score FMEA quantifié : Impact × Probabilité × Détectabilité'],
                        ['Déploiement', '"On y va ?" en réunion', 'GO/NO-GO automatique par module avec blocking items'],
                        ['IA', 'L\'agent lit le ticket', 'Context Brief structuré avec tests + ADR + lessons + risque'],
                    ] as $row)
                        <tr style="border-bottom: 1px solid rgba(51,65,85,0.15);">
                            <td class="px-5 py-3 text-white font-medium">{{ $row[0] }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $row[1] }}</td>
                            <td class="px-5 py-3 text-slate-300">{{ $row[2] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('methodology') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors">
                En savoir plus sur la méthodologie &rarr;
            </a>
        </div>
    </section>

    {{-- Use cases --}}
    <section class="max-w-5xl mx-auto px-6 pb-28">
        <h2 class="text-2xl font-bold text-white text-center mb-10 tracking-tight">Cas d'usage concrets</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['icon' => 'shield-check', 'title' => 'Avant un déploiement', 'desc' => 'Le Deploy Readiness vérifie que 100% des P0 sont vérifiées. Si TEST-007 fail, le déploiement est bloqué avec la raison exacte.', 'badge' => 'GO / NO-GO'],
                ['icon' => 'brain', 'title' => 'Quand Claude Code implémente', 'desc' => 'L\'agent appelle /context-brief et reçoit : l\'exigence, les tests, les ADR, les lessons learned. Contexte complet.', 'badge' => 'Context Brief'],
                ['icon' => 'triangle-alert', 'title' => 'Quand un bug apparaît', 'desc' => 'Anomalie, non-conformité ou défaut ? Orbiter classe, relie à l\'exigence violée, repasse le V&V à "échoué".', 'badge' => 'Taxonomy'],
            ] as $case)
                <div class="card-hover rounded-xl p-6" style="background: rgba(15,23,42,0.5); border: 1px solid rgba(51,65,85,0.3);">
                    <x-dynamic-component :component="'lucide-' . $case['icon']" class="w-6 h-6 text-cyan-400 mb-4" />
                    <h3 class="text-white font-semibold mb-2">{{ $case['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-4">{{ $case['desc'] }}</p>
                    <span class="text-[10px] px-2 py-1 rounded-full font-mono" style="background: rgba(6,182,212,0.08); border: 1px solid rgba(6,182,212,0.15); color: #06b6d4;">{{ $case['badge'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Stack --}}
    <section class="max-w-3xl mx-auto px-6 pb-28 text-center">
        <h2 class="text-2xl font-bold text-white mb-3 tracking-tight">Simplicité radicale</h2>
        <p class="text-slate-500 mb-8 text-sm">Pas de Vue, pas de React, pas de SPA. Le navigateur fait le travail.</p>
        <div class="inline-block rounded-xl p-6 text-left font-mono text-sm" style="background: rgba(15,23,42,0.6); border: 1px solid rgba(51,65,85,0.3);">
            <pre class="leading-relaxed"><span class="text-cyan-400">Laravel 13</span> <span class="text-slate-600">(PHP 8.4)</span>
├── <span class="text-emerald-400">Blade Components</span> <span class="text-slate-600">(UI)</span>
├── <span class="text-emerald-400">Livewire 4</span> <span class="text-slate-600">(réactivité)</span>
├── <span class="text-emerald-400">Tailwind CSS 4</span> <span class="text-slate-600">(styling)</span>
├── <span class="text-amber-400">PostgreSQL 16</span> <span class="text-slate-600">(JSONB)</span>
├── <span class="text-amber-400">FrankenPHP</span> <span class="text-slate-600">(Octane)</span>
├── <span class="text-slate-500">Pest PHP</span> <span class="text-slate-600">(tests)</span>
└── <span class="text-cyan-400">Mermaid.js</span> + <span class="text-cyan-400">frappe-gantt</span></pre>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="max-w-6xl mx-auto px-6 py-8 flex items-center justify-between text-xs text-slate-700" style="border-top: 1px solid rgba(51,65,85,0.2);">
        <div class="flex items-center gap-2">
            <x-application-logo class="w-4 h-4 text-slate-700" />
            Orbiter — MIT License
        </div>
        <div class="flex items-center gap-5">
            <a href="{{ route('methodology') }}" class="hover:text-slate-400 transition-colors">Méthodologie</a>
            <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener" class="hover:text-slate-400 transition-colors">GitHub</a>
        </div>
    </footer>

</body>
</html>
