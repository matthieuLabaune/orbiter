# Architecture — Orbiter

## Diagramme des modules

```mermaid
graph TB
    subgraph Core
        P[Projects] --> M[Modules]
        M --> R[Requirements]
        R --> T[Tests & V&V]
        M --> TK[Tasks / Planning]
    end

    subgraph Architecture
        M --> D[Diagrams]
        M --> ADR[ADR]
    end

    subgraph Industrial
        R --> RS[Risk Score]
        R --> DR[Deploy Readiness]
        M --> LL[Lessons Learned]
        P --> BL[Baselines]
        R --> AN[Anomalies]
    end

    subgraph AI
        R --> CB[Context Brief]
    end

    subgraph Integration
        GH[GitHub Webhooks] --> R
        GH --> T
        CI[CI Pipeline] --> DR
    end
```

## Stack technique

- **Backend** : Laravel 13 (PHP 8.4) + Octane/FrankenPHP
- **Frontend** : Blade Components + Livewire 3
- **CSS** : Tailwind CSS 4
- **DB** : PostgreSQL 16 (JSONB)
- **Diagrammes** : Mermaid.js
- **Gantt** : frappe-gantt
- **Auth** : Laravel Breeze (Blade)
- **Tests** : Pest PHP
- **Infra** : Docker (FrankenPHP + PostgreSQL + Redis)
