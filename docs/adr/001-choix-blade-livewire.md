# ADR-001 : Blade + Livewire plutôt qu'un SPA

## Statut
Accepté

## Date
2026-03-25

## Contexte
Un outil de gestion de projet est essentiellement composé de listes, tableaux, formulaires et modales. Aucun de ces cas d'usage ne justifie un SPA complet avec build JS complexe, hydration et state management côté client.

## Décision
Utiliser Blade Components + Livewire 3 pour toute l'interface. Le seul JavaScript externe autorisé est Mermaid.js (diagrammes) et frappe-gantt (Gantt interactif). Les modales utilisent `<dialog>` natif HTML.

## Conséquences
- **Positif** : Simplicité, performance SSR, pas de build JS complexe, moins de bugs frontend
- **Positif** : Le navigateur fait le travail (dialog, popover, details/summary)
- **Négatif** : Certaines interactions riches (drag & drop Kanban) nécessiteront Alpine.js
- **Négatif** : Pas d'écosystème de composants React/Vue

## Modules impactés
- Tous les modules frontend
