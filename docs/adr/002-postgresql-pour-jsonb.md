# ADR-002 : PostgreSQL pour le support JSONB

## Statut
Accepté

## Date
2026-03-25

## Contexte
Orbiter stocke des données semi-structurées (snapshots de baseline, statuts de modules dans les deploy readiness reviews, tags). Le JSONB de PostgreSQL permet de stocker ces données efficacement avec indexation.

## Décision
Utiliser PostgreSQL 16 comme base de données principale.

## Conséquences
- **Positif** : JSONB natif avec indexation GIN pour les recherches
- **Positif** : Maturité et fiabilité de PostgreSQL
- **Négatif** : Moins répandu que MySQL dans l'écosystème Laravel

## Modules impactés
- Baselines (snapshot JSONB)
- Deploy Readiness Reviews (module_statuses, blocking_items JSONB)
- Lessons Learned (tags JSON)
