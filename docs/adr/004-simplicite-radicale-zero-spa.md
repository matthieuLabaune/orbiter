# ADR-004 : Simplicité radicale — Zéro SPA

## Statut
Accepté

## Date
2026-03-25

## Contexte
Inspiré par l'article de Spatie "Rethinking our frontend future" et la philosophie SpaceX "Tools not Rules". Le navigateur moderne offre nativement : dialog, popover, details/summary, CSS transitions, form validation.

## Décision
Exploiter au maximum les APIs natives du navigateur. Zéro framework JS frontend. Le fichier app.js n'importe que Mermaid.js et frappe-gantt.

## Conséquences
- **Positif** : Moins de dépendances, moins de bugs, moins de maintenance
- **Positif** : Performance native du navigateur
- **Positif** : Accessibilité native (dialog gère le focus trap, escape, backdrop)
- **Négatif** : Certains patterns UI modernes sont plus verbeux sans framework

## Modules impactés
- Tous les modules frontend
