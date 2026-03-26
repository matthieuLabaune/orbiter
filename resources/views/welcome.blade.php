<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orbiter — Project management built for traceability</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=geist:400,500,600,700&family=instrument-serif:400,400i&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Geist', -apple-system, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        .font-display { font-family: 'Instrument Serif', Georgia, serif; }
        h1,h2,h3 { letter-spacing: -0.025em; }
    </style>
</head>
<body class="antialiased" style="background: var(--o-bg); color: var(--o-text);">

    {{-- Nav --}}
    <nav class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2.5">
            <x-application-logo class="w-7 h-7" style="color: var(--o-accent);" />
            <span class="text-[17px] font-bold tracking-tight" style="color: var(--o-text);">Orbiter</span>
        </a>
        <div class="flex items-center gap-5">
            <a href="{{ route('methodology') }}" class="text-sm" style="color: var(--o-text-3);">Méthodologie</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium" style="color: var(--o-text-2);">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm">Commencer</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-4xl mx-auto px-6 pt-20 pb-24 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium mb-8"
             style="background: var(--o-accent-bg); color: var(--o-accent);">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse-dot" style="background: var(--o-accent);"></span>
            Open Source — MIT License
        </div>
        <h1 class="text-5xl md:text-[72px] font-bold leading-[1.05] mb-6 tracking-tight" style="color: var(--o-text);">
            Project management<br>
            built for <span class="font-display italic" style="color: var(--o-accent);">traceability</span>
        </h1>
        <p class="text-lg text-balance max-w-xl mx-auto mb-10 leading-relaxed" style="color: var(--o-text-3);">
            Traçabilité complète exigence &rarr; test &rarr; code &rarr; preuve.
            Inspiré de l'ingénierie système NASA/SpaceX.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('register') }}" class="btn-primary px-6 py-3 text-[15px]">Commencer gratuitement</a>
            <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener" class="btn-secondary px-6 py-3 text-[15px]">
                <x-lucide-github class="w-4 h-4" /> GitHub
            </a>
        </div>
    </section>

    {{-- App mockup --}}
    <section class="max-w-5xl mx-auto px-6 pb-20">
        <div class="surface-elevated p-1.5 overflow-hidden">
            <div class="rounded-xl overflow-hidden" style="background: var(--o-surface-2);">
                <div class="flex items-center gap-2 px-4 py-2.5" style="border-bottom: 1px solid var(--o-border);">
                    <div class="flex gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" style="background: var(--o-red);"></span>
                        <span class="w-2.5 h-2.5 rounded-full" style="background: var(--o-orange);"></span>
                        <span class="w-2.5 h-2.5 rounded-full" style="background: var(--o-green);"></span>
                    </div>
                    <div class="flex-1 text-center">
                        <span class="text-[11px] px-3 py-0.5 rounded-md" style="background: var(--o-bg); color: var(--o-text-4);">localhost:8080/projects/orbiter-v1</span>
                    </div>
                </div>
                <div class="p-6 flex gap-4">
                    <div class="w-40 shrink-0 space-y-2">
                        <div class="text-[11px] font-bold mb-3" style="color: var(--o-text);">Orbiter</div>
                        @foreach(['Dashboard', 'Modules', 'Requirements', 'Tests & V&V', 'Planning'] as $i => $item)
                            <div class="text-[10px] px-2 py-1 rounded-lg {{ $i === 0 ? 'font-medium' : '' }}"
                                 style="{{ $i === 0 ? 'background: var(--o-accent-bg); color: var(--o-accent);' : 'color: var(--o-text-3);' }}">
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                    <div class="flex-1 space-y-3">
                        <div class="text-[13px] font-bold" style="color: var(--o-text);">Santé du projet</div>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach([['16', 'Formalisées', '--o-text-3'], ['9', 'Couvertes', '--o-accent'], ['7', 'Vérifiées', '--o-orange'], ['2', 'Validées', '--o-green']] as $stat)
                                <div class="text-center p-2 rounded-xl" style="background: var(--o-bg);">
                                    <div class="text-lg font-bold" style="color: var({{ $stat[2] }});">{{ $stat[0] }}</div>
                                    <div class="text-[9px]" style="color: var(--o-text-4);">{{ $stat[1] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="rounded-xl overflow-hidden" style="border: 1px solid var(--o-border);">
                            <div class="px-3 py-1.5 text-[9px] font-medium" style="background: var(--o-bg); color: var(--o-text-4);">REQ · TITRE · V&V · RISQUE</div>
                            @foreach([['REQ-001', 'Créer et gérer des projets', 'Validé', '20'], ['REQ-003', 'CRUD exigences avec ID auto', 'Validé', '50'], ['REQ-004', 'Statut V&V automatique', 'Vérifié', '60']] as $row)
                                <div class="flex items-center gap-3 px-3 py-1.5 text-[9px]" style="border-top: 1px solid var(--o-border);">
                                    <span class="font-mono font-medium" style="color: var(--o-accent);">{{ $row[0] }}</span>
                                    <span class="flex-1" style="color: var(--o-text-2);">{{ $row[1] }}</span>
                                    <span class="badge badge-green text-[8px] py-0 px-1.5">{{ $row[2] }}</span>
                                    <span class="font-mono" style="color: var(--o-text-4);">{{ $row[3] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-5xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['icon' => 'git-branch', 'title' => 'Traçabilité bidirectionnelle', 'desc' => 'De l\'exigence au commit, du commit à l\'exigence. Si un lien manque, Orbiter le signale.'],
                ['icon' => 'shield-check', 'title' => 'V&V séparées', 'desc' => 'Vérifié (tests CI) ≠ Validé (client confirme). Les deux sont trackés séparément.'],
                ['icon' => 'triangle-alert', 'title' => 'Risk Score FMEA', 'desc' => 'Impact × Probabilité × (6 - Détectabilité). On teste en priorité ce qui est risqué.'],
                ['icon' => 'rocket', 'title' => 'Deploy Readiness', 'desc' => 'Check GO/NO-GO automatique par module. Inspiré des Flight Readiness Reviews NASA.'],
                ['icon' => 'brain', 'title' => 'Context Brief IA', 'desc' => 'Briefing contextuel par exigence — consommable par Claude Code ou tout agent IA.'],
                ['icon' => 'archive', 'title' => 'Configuration Baseline', 'desc' => 'Snapshot immuable de l\'état du projet à chaque release.'],
            ] as $f)
                <div class="surface p-5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                    <x-dynamic-component :component="'lucide-' . $f['icon']" class="w-5 h-5 mb-3" style="color: var(--o-accent);" />
                    <h3 class="text-[15px] font-semibold mb-1.5" style="color: var(--o-text);">{{ $f['title'] }}</h3>
                    <p class="text-[13px] leading-relaxed" style="color: var(--o-text-3);">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section class="max-w-4xl mx-auto px-6 pb-24">
        <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--o-text);">Comment ça marche</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['01', 'Formalisez', 'Écrivez vos exigences avec critères d\'acceptation et score de risque'],
                ['02', 'Testez', 'Liez des tests. Le statut V&V se met à jour automatiquement'],
                ['03', 'Codez', 'Mentionnez REQ-XXX dans vos commits. Orbiter trace le lien'],
                ['04', 'Déployez', 'Deploy Readiness vérifie que tout est GO'],
            ] as $step)
                <div class="text-center">
                    <div class="text-[11px] font-mono font-bold mb-2" style="color: var(--o-accent);">{{ $step[0] }}</div>
                    <h3 class="text-[15px] font-semibold mb-1" style="color: var(--o-text);">{{ $step[1] }}</h3>
                    <p class="text-[12px] leading-relaxed" style="color: var(--o-text-3);">{{ $step[2] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Comparison --}}
    <section class="max-w-4xl mx-auto px-6 pb-24">
        <h2 class="text-3xl font-bold text-center mb-3" style="color: var(--o-text);">Pourquoi pas Jira ?</h2>
        <p class="text-center text-sm mb-10" style="color: var(--o-text-3);">La gestion de projet classique n'a pas été conçue pour la traçabilité.</p>
        <div class="surface overflow-hidden">
            <table class="w-full text-[13px]">
                <thead>
                    <tr style="border-bottom: 1px solid var(--o-border);">
                        <th class="px-5 py-3 text-left font-medium" style="color: var(--o-text-3);">Aspect</th>
                        <th class="px-5 py-3 text-left font-medium" style="color: var(--o-text-4);">Jira / Linear</th>
                        <th class="px-5 py-3 text-left font-medium" style="color: var(--o-accent);">Orbiter</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['Avancement', 'Tickets fermés / total', '4 axes : formalisation → couverture → vérification → validation'],
                        ['Traçabilité', 'Liens manuels entre tickets', 'Auto-parsing REQ-XXX dans commits et PR'],
                        ['Tests', 'CI passe ou échoue globalement', 'Chaque test tracé vers une exigence, statut V&V par REQ'],
                        ['Risque', 'Estimation subjective (S/M/L)', 'Score FMEA : Impact × Probabilité × Détectabilité'],
                        ['Déploiement', '"On y va ?" en réunion', 'GO/NO-GO automatique par module'],
                        ['IA', 'L\'agent lit le ticket', 'Context Brief structuré : tests + ADR + lessons + risque'],
                    ] as $row)
                        <tr style="border-bottom: 1px solid var(--o-border);">
                            <td class="px-5 py-3 font-medium" style="color: var(--o-text);">{{ $row[0] }}</td>
                            <td class="px-5 py-3" style="color: var(--o-text-4);">{{ $row[1] }}</td>
                            <td class="px-5 py-3" style="color: var(--o-text-2);">{{ $row[2] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('methodology') }}" class="text-sm font-medium" style="color: var(--o-accent);">
                En savoir plus sur la méthodologie &rarr;
            </a>
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-3xl mx-auto px-6 pb-24 text-center">
        <div class="surface p-12">
            <h2 class="text-2xl font-bold mb-3" style="color: var(--o-text);">Prêt à structurer vos projets ?</h2>
            <p class="text-sm mb-6" style="color: var(--o-text-3);">Gratuit, open source, auto-hébergeable.</p>
            <a href="{{ route('register') }}" class="btn-primary px-8 py-3 text-[15px]">Commencer avec Orbiter</a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="max-w-6xl mx-auto px-6 py-8 flex items-center justify-between text-xs" style="border-top: 1px solid var(--o-border); color: var(--o-text-4);">
        <div class="flex items-center gap-2">
            <x-application-logo class="w-4 h-4" style="color: var(--o-text-4);" />
            Orbiter — MIT License
        </div>
        <div class="flex items-center gap-5">
            <a href="{{ route('methodology') }}" class="hover:opacity-70 transition-opacity">Méthodologie</a>
            <a href="https://github.com/matthieuLabaune/orbiter" target="_blank" rel="noopener" class="hover:opacity-70 transition-opacity">GitHub</a>
        </div>
    </footer>
</body>
</html>
