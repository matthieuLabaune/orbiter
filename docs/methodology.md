# Méthodologie "Project as Context" — Orbiter

## Principe

Orbiter structure le projet comme un **contexte exploitable** par les humains ET les agents IA.
Chaque artefact (exigence, test, décision, leçon) est lié aux autres, formant un graphe de connaissances navigable.

## Les 6 principes d'ingénierie système

1. **Traçabilité bidirectionnelle** — De l'exigence au commit, du commit à l'exigence
2. **V-Model** — Décomposition puis intégration, chaque niveau a ses tests
3. **V&V séparées** — Vérifié ≠ Validé
4. **Décomposition hiérarchique** — Projet → Module → Requirement → Test
5. **Configuration Management** — Chaque changement est versionné et documenté
6. **System of Record** — Tout vit dans Orbiter, source unique de vérité

## Mesure d'avancement

L'avancement se mesure sur 4 axes :
1. **Formalisation** — % d'exigences avec critères d'acceptation
2. **Couverture** — % d'exigences avec au moins un test
3. **Vérification** — % d'exigences dont tous les tests passent
4. **Validation** — % d'exigences confirmées par le client (= avancement réel)
