<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Méthodologie — Orbiter</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#050a18] text-slate-200 font-sans antialiased stars min-h-screen">
    <style>.stars{background-image:radial-gradient(1px 1px at 10% 20%,rgba(255,255,255,.4) 0%,transparent 100%),radial-gradient(1px 1px at 50% 10%,rgba(255,255,255,.2) 0%,transparent 100%),radial-gradient(1px 1px at 70% 80%,rgba(255,255,255,.35) 0%,transparent 100%),radial-gradient(1.5px 1.5px at 55% 45%,rgba(255,255,255,.4) 0%,transparent 100%);}</style>

    {{-- Nav --}}
    <nav class="max-w-4xl mx-auto px-6 py-6 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <x-application-logo class="w-8 h-8 text-blue-400" />
            <span class="text-xl font-bold text-white tracking-tight">Orbiter</span>
        </a>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Connexion</a>
            @endauth
        </div>
    </nav>

    <article class="max-w-4xl mx-auto px-6 pb-24">
        {{-- Hero --}}
        <div class="text-center py-16">
            <h1 class="text-4xl font-bold text-white mb-4">Méthodologie "Project as Context"</h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                Comment Orbiter structure vos projets pour les rendre exploitables par les humains ET les agents IA.
            </p>
        </div>

        {{-- V-Model --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <x-lucide-chevrons-down class="w-6 h-6 text-blue-400" />
                Le V-Model adapté au logiciel
            </h2>
            <div class="bg-slate-900/60 border border-slate-700/50 rounded-xl p-6">
                <pre class="mermaid">
graph LR
    subgraph Décomposition
        A[Besoin utilisateur] --> B[Exigences REQ]
        B --> C[Architecture modules]
        C --> D[Conception technique]
        D --> E[Code]
    end
    subgraph Intégration
        E --> F[Tests unitaires]
        F --> G[Tests intégration]
        G --> H[Vérification CI]
        H --> I[Validation client]
    end
    B -.-> H
    A -.-> I
                </pre>
                <p class="mt-4 text-sm text-slate-400">
                    On descend : besoin → exigence → architecture → code.
                    On remonte : tests unitaires → intégration → vérification → validation.
                    Chaque niveau a ses propres exigences et tests.
                </p>
            </div>
        </section>

        {{-- 6 principes --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-6">Les 6 principes d'ingénierie système</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach([
                    ['icon' => 'git-branch', 'title' => '1. Traçabilité bidirectionnelle', 'desc' => 'Chaque exigence trace vers des tests ET du code. Depuis n\'importe quel point, on peut remonter au besoin utilisateur.'],
                    ['icon' => 'chevrons-down', 'title' => '2. V-Model', 'desc' => 'Décomposition puis intégration. Chaque niveau a ses exigences, ses tests et ses responsables.'],
                    ['icon' => 'shield-check', 'title' => '3. V&V séparées', 'desc' => 'Vérification = conforme aux specs (tests CI). Validation = répond au vrai besoin (client confirme).'],
                    ['icon' => 'layers', 'title' => '4. Décomposition hiérarchique', 'desc' => 'Projet → Module → Composant → Fonction. L\'avancement se mesure à chaque niveau.'],
                    ['icon' => 'history', 'title' => '5. Configuration Management', 'desc' => 'Chaque changement est versionné, son impact évalué, la décision documentée (ADR).'],
                    ['icon' => 'database', 'title' => '6. System of Record', 'desc' => 'Tout vit dans Orbiter : exigences, tests, architecture, planning, décisions. Source unique de vérité.'],
                ] as $principle)
                    <div class="bg-slate-900/40 border border-slate-700/30 rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <x-dynamic-component :component="'lucide-' . $principle['icon']" class="w-5 h-5 text-blue-400" />
                            <h3 class="text-white font-semibold">{{ $principle['title'] }}</h3>
                        </div>
                        <p class="text-sm text-slate-400">{{ $principle['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 4 axes --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <x-lucide-bar-chart-3 class="w-6 h-6 text-emerald-400" />
                4 axes de mesure d'avancement
            </h2>
            <div class="bg-slate-900/60 border border-slate-700/50 rounded-xl p-6">
                <p class="text-slate-400 mb-6">L'avancement d'un projet ne se mesure pas en tickets fermés. Orbiter mesure 4 axes indépendants :</p>
                <div class="space-y-4">
                    @foreach([
                        ['label' => 'Formalisation', 'desc' => 'Les besoins sont-ils écrits avec des critères d\'acceptation ?', 'color' => 'slate', 'pct' => 100],
                        ['label' => 'Couverture', 'desc' => 'Chaque exigence a-t-elle au moins un test ?', 'color' => 'blue', 'pct' => 75],
                        ['label' => 'Vérification', 'desc' => 'Les tests passent-ils en CI ?', 'color' => 'amber', 'pct' => 50],
                        ['label' => 'Validation', 'desc' => 'Le client a-t-il confirmé en staging ?', 'color' => 'emerald', 'pct' => 25],
                    ] as $axis)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div>
                                    <span class="text-white font-medium text-sm">{{ $axis['label'] }}</span>
                                    <span class="text-slate-500 text-xs ml-2">{{ $axis['desc'] }}</span>
                                </div>
                                <span class="text-xs font-mono text-slate-400">{{ $axis['pct'] }}%</span>
                            </div>
                            <x-ui.progress-bar :value="$axis['pct']" :max="100" :color="$axis['color']" :showLabel="false" />
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-emerald-400 font-medium">
                    L'avancement réel = taux de validation. Les 3 autres sont des indicateurs avancés.
                </p>
            </div>
        </section>

        {{-- Comparison --}}
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <x-lucide-scale class="w-6 h-6 text-amber-400" />
                Gestion classique vs Orbiter
            </h2>
            <div class="bg-slate-900/60 border border-slate-700/50 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-700/50">
                            <th class="px-5 py-3 text-left text-slate-500 font-medium">Aspect</th>
                            <th class="px-5 py-3 text-left text-slate-400 font-medium">Gestion classique</th>
                            <th class="px-5 py-3 text-left text-blue-400 font-medium">Orbiter</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach([
                            ['Avancement', 'Tickets fermés / total', '4 axes : formalisation, couverture, vérification, validation'],
                            ['Traçabilité', 'Manuelle — liens Jira/Confluence', 'Automatique — parsing REQ-XXX dans commits et PR'],
                            ['Tests', 'CI passe/échoue globalement', 'Chaque test tracé vers une exigence, statut V&V par REQ'],
                            ['Risque', 'Estimation subjective (S/M/L)', 'Score FMEA quantifié : Impact × Probabilité × Détectabilité'],
                            ['Déploiement', 'PM dit "on y va" ou pas', 'GO/NO-GO automatique par module avec blocking items'],
                            ['Décisions', 'Perdues dans Slack/emails', 'ADR versionnés liés aux modules et exigences'],
                            ['IA', 'L\'agent lit le ticket Jira', 'Context Brief structuré : REQ + tests + ADR + lessons + risque'],
                        ] as $row)
                            <tr>
                                <td class="px-5 py-3 text-white font-medium">{{ $row[0] }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $row[1] }}</td>
                                <td class="px-5 py-3 text-slate-300">{{ $row[2] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- CTA --}}
        <div class="text-center py-8">
            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-lg transition-colors text-sm">
                Commencer avec Orbiter
            </a>
        </div>
    </article>

    <footer class="max-w-4xl mx-auto px-6 py-8 border-t border-slate-800/50 text-center text-xs text-slate-600">
        Orbiter — MIT License
    </footer>
</body>
</html>
