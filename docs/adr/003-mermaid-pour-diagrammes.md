# ADR-003 : Mermaid.js pour les diagrammes

## Statut
Accepté

## Date
2026-03-25

## Contexte
Orbiter a besoin de visualiser l'architecture des modules et leurs dépendances. La source du diagramme doit être versionnée et éditable en texte.

## Décision
Utiliser Mermaid.js pour le rendu côté client. Le source Mermaid est stocké en base et versionné.

## Conséquences
- **Positif** : Rendu côté client, pas de service backend supplémentaire
- **Positif** : Syntaxe texte versionnée en base
- **Positif** : Large support (GitHub, documentation, etc.)
- **Négatif** : Limité pour les diagrammes très complexes

## Modules impactés
- Architecture (diagrammes de modules)
