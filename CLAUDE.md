# CLAUDE.md — Orbiter

## Tu es le développeur principal d'Orbiter.

Orbiter est un outil de gestion de projet open source inspiré de l'ingénierie système NASA/SpaceX, adapté au développement logiciel. Il offre une traçabilité complète exigence → test → code → preuve, pensé pour être exploitable par des humains ET par des agents IA.

Le PRD complet est dans `docs/prd.md` (le fichier orbiter-brief.md). Lis-le intégralement avant de coder quoi que ce soit. Ce fichier CLAUDE.md est ton guide de travail quotidien.

---

## Principes non négociables

### 1. Simplicité radicale
- **Blade + Livewire + Tailwind. Pas de Vue, pas de React, pas d'Inertia, pas de SPA.**
- Le seul JS externe autorisé : Mermaid.js (diagrammes) et frappe-gantt (Gantt).
- Alpine.js est disponible via Livewire mais à utiliser en dernier recours.
- Les modales utilisent `<dialog>` natif HTML avec `showModal()`.
- Les popovers utilisent l'API Popover native quand possible.
- Si tu te retrouves à écrire plus de 20 lignes de JavaScript pour un composant, tu te trompes probablement d'approche. Cherche d'abord une solution Blade/Livewire.

### 2. Le navigateur fait le travail
Inspiré par l'article de Spatie "Rethinking our frontend future" :
- Focus trapping → `<dialog>` natif
- Scroll lock → `<dialog>` natif
- Backdrop → `::backdrop` CSS
- Dismiss on Escape → `<dialog>` natif
- Popovers → API Popover (`popover` attribute)
- Dropdowns simples → `<details>/<summary>`
- Animations → CSS transitions, pas de JS

### 3. Traçabilité partout
Chaque entité dans Orbiter est liée à d'autres. Un requirement pointe vers des tests, un test vers un requirement, un commit vers un requirement, un ADR vers des modules. Si tu crées une entité sans relation, c'est un bug.

### 4. Convention over configuration
- IDs auto-générés : REQ-001, TEST-001, ADR-001, LESSON-001, ANOM-001, NC-001, DEF-001
- Statuts V&V : `untested` → `in_test` → `verified` → `validated` (+ `failed`)
- Risk Score : auto-calculé = impact × probability × (6 - detectability)
- Santé module : auto-calculée depuis les statuts V&V des requirements du module

---

## Stack

```
Laravel 11+ (PHP 8.3)
├── Blade Components (UI)
├── Livewire 3 (réactivité)
├── Tailwind CSS 4 (styling)
├── PostgreSQL (DB, JSONB)
├── Pest (tests)
└── app.js → Mermaid.js + frappe-gantt uniquement
```

### Packages Laravel recommandés
- `livewire/livewire` — réactivité sans JS
- `blade-ui-kit/blade-icons` + `mallardduck/blade-lucide-icons` — icônes
- `spatie/laravel-sluggable` — slugs propres
- `spatie/laravel-activitylog` — log d'activité (fil d'événements)
- `spatie/laravel-data` — DTOs propres pour l'API
- Ne PAS installer Inertia, Vue, React, ou tout framework JS front.

---

## Design

- Dark mode par défaut (#0a0f1e background, #0f172a cards)
- JetBrains Mono pour les IDs (REQ-001, TEST-001) et le code
- Lucide icons via blade-icons
- Couleurs sémantiques :
  - Vert emerald → validé, pass, GO
  - Bleu → vérifié, en cours
  - Ambre → en test, warning
  - Rouge → échoué, NO-GO, bloqué
  - Slate → non testé, inactif
- CSS transitions pour les animations, jamais de JS
- `<dialog>` natif pour toutes les modales

---

## Conventions
- Nommage des routes : `projects.requirements.index`, `projects.requirements.show`
- Nommage des vues : `pages.requirements.index`, `pages.requirements.show`
- Nommage Livewire : `Requirements\\RequirementList`
- Tous les IDs métier (REQ-001, TEST-001...) sont générés automatiquement par le modèle
- Les statuts V&V sont recalculés automatiquement quand un test_execution est créé

---

## Erreurs à éviter

- ❌ Installer Vue, React, Inertia, ou tout framework JS front
- ❌ Écrire du JavaScript pour des modales, tooltips, dropdowns
- ❌ Créer des entités sans relations (une REQ sans module, un test sans REQ)
- ❌ Calculer les statuts V&V manuellement — ils doivent être auto-calculés
- ❌ Mettre de la logique métier dans les Controllers — utilise les Services
- ❌ Oublier le DemoProjectSeeder — c'est la première impression du produit
- ❌ Faire des requêtes N+1 — utilise `->with()` systématiquement
- ❌ Ignorer le dark mode — tout doit être dark par défaut
