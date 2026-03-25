# Phase 2 — Backend CRUD Design

## Context
Orbiter a son modèle de données (27 migrations, 18 modèles) mais aucun controller, service, ou vue métier. Cette phase livre le CRUD complet pour les 7 modules core.

## Architecture

### Pattern par module
```
Controller (route → vue)  →  Service (logique métier)  →  Model (données)
     ↓                            ↓
   Blade View              Policy (autorisations)
```

### Scoping projet
Toutes les routes métier sont scopées sous `/projects/{project}/...`. Un middleware `EnsureProjectMember` vérifie l'appartenance au projet.

### Routes
```
/projects                           → ProjectController (index, create, store, show, edit, update, destroy)
/projects/{project}/modules         → ModuleController
/projects/{project}/requirements    → RequirementController
/projects/{project}/tests           → TestController
/projects/{project}/tests/{test}/executions → TestExecutionController (store)
/projects/{project}/tasks           → TaskController
/projects/{project}/adrs            → AdrController
/projects/{project}/diagrams        → DiagramController
```

### Services
- `HealthScoreService` — calcul santé par module (4 axes)
- `RiskScoreService` — tri/filtrage par risk score
- `VVStatusService` — recalcul automatique statut V&V quand une exécution est créée
- `DiagramGeneratorService` — génère le Mermaid depuis les modules/dépendances

### Vues Blade
Chaque module a : `index.blade.php`, `show.blade.php`, `create.blade.php`, `edit.blade.php` dans `resources/views/pages/{module}/`.

### Git workflow
- Une branche par issue : `feat/issue-N-description`
- Commits avec `Closes #N` ou `Refs #N`
- PR auto-créée avec `gh pr create` référençant l'issue

## Ordre d'implémentation
1. #1 Projects (socle)
2. #2 Modules (dépendances)
3. #3 Requirements (V&V + Risk Score)
4. #4 Tests (exécutions + recalcul V&V)
5. #6 ADR
6. #5 Tasks
7. #7 Diagrams (Mermaid)
8. #24 Seeder enrichi
