<?php

namespace Database\Seeders;

use App\Models\Adr;
use App\Models\Anomaly;
use App\Models\Baseline;
use App\Models\DeployReadinessReview;
use App\Models\Diagram;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\Task;
use App\Models\Test;
use App\Models\TestExecution;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoProjectSeeder extends Seeder
{
    public function run(): void
    {
        // ================================================================
        // USERS
        // ================================================================

        $demo = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@orbiter.dev',
            'password' => bcrypt('password'),
        ]);

        $alice = User::factory()->create([
            'name' => 'Alice Martin',
            'email' => 'alice@orbiter.dev',
            'password' => bcrypt('password'),
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob Chen',
            'email' => 'bob@orbiter.dev',
            'password' => bcrypt('password'),
        ]);

        // ================================================================
        // PROJECT 1 — Orbiter v1 (mature)
        // ================================================================

        $orbiter = Project::create([
            'name' => 'Orbiter v1',
            'description' => 'Outil de gestion de projet open source inspiré de l\'ingénierie système NASA/SpaceX. Traçabilité complète exigence → test → code → preuve.',
        ]);

        $orbiter->members()->attach($demo->id, ['role' => 'owner']);
        $orbiter->members()->attach($alice->id, ['role' => 'member']);
        $orbiter->members()->attach($bob->id, ['role' => 'viewer']);

        // --- Orbiter Modules ---
        $oMods = collect([
            ['name' => 'Projects', 'description' => 'Gestion des projets, membres et rôles', 'status' => 'active'],
            ['name' => 'Requirements', 'description' => 'Exigences avec traçabilité, versioning et statut V&V', 'status' => 'active'],
            ['name' => 'Tests & V&V', 'description' => 'Registre de tests, exécutions et couverture des exigences', 'status' => 'active'],
            ['name' => 'Planning', 'description' => 'Tâches, dépendances, Gantt et Kanban', 'status' => 'active'],
            ['name' => 'Architecture', 'description' => 'Diagrammes Mermaid et visualisation des modules', 'status' => 'active'],
            ['name' => 'ADR', 'description' => 'Architecture Decision Records — journal des décisions', 'status' => 'active'],
            ['name' => 'Dashboard', 'description' => 'Synthèse santé projet, alertes et activité', 'status' => 'draft'],
        ])->mapWithKeys(function ($data) use ($orbiter, $demo) {
            $module = Module::create([
                'project_id' => $orbiter->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'owner_id' => $demo->id,
                'status' => $data['status'],
            ]);

            return [$data['name'] => $module];
        });

        // Module dependencies
        $oMods['Requirements']->dependencies()->attach($oMods['Projects']->id, ['type' => 'depends_on']);
        $oMods['Tests & V&V']->dependencies()->attach($oMods['Requirements']->id, ['type' => 'depends_on']);
        $oMods['Planning']->dependencies()->attach($oMods['Requirements']->id, ['type' => 'depends_on']);
        $oMods['Architecture']->dependencies()->attach($oMods['Projects']->id, ['type' => 'depends_on']);
        $oMods['ADR']->dependencies()->attach($oMods['Projects']->id, ['type' => 'depends_on']);
        $oMods['Dashboard']->dependencies()->attach($oMods['Requirements']->id, ['type' => 'depends_on']);
        $oMods['Dashboard']->dependencies()->attach($oMods['Tests & V&V']->id, ['type' => 'depends_on']);

        // --- Orbiter Requirements (original 13 + 3 new = 16) ---
        $oReqs = collect([
            ['module' => 'Projects',      'title' => 'Créer et gérer des projets', 'description' => 'Un utilisateur peut créer un projet avec nom, description et slug unique.', 'acceptance_criteria' => "- Formulaire de création avec validation\n- Slug auto-généré\n- Liste des projets de l'utilisateur", 'priority' => 'P0', 'vv_status' => 'validated', 'risk_impact' => 4, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Projects',      'title' => 'Gestion des membres et rôles', 'description' => 'Chaque projet a des membres avec rôles (owner, member, viewer).', 'acceptance_criteria' => "- Inviter un membre par email\n- Assigner un rôle\n- Révoquer l'accès", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Requirements',  'title' => 'CRUD exigences avec ID auto', 'description' => 'Créer des exigences avec REF auto-générée (REQ-001), liées à un module.', 'acceptance_criteria' => "- ID REQ-XXX auto-généré\n- Titre, description, critères d'acceptation\n- Module rattaché obligatoire", 'priority' => 'P0', 'vv_status' => 'validated', 'risk_impact' => 5, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'Requirements',  'title' => 'Statut V&V automatique', 'description' => 'Le statut V&V est recalculé automatiquement depuis les résultats de tests.', 'acceptance_criteria' => "- Tous tests pass → vérifié\n- Un test fail → échoué\n- Validation manuelle par le client", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 5, 'risk_probability' => 3, 'risk_detectability' => 2],
            ['module' => 'Requirements',  'title' => 'Risk Score FMEA', 'description' => 'Chaque exigence a un score de risque calculé : Impact × Probabilité × (6 - Détectabilité).', 'acceptance_criteria' => "- 3 champs select 1-5\n- Score auto-calculé\n- Tri par risque dans le dashboard", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 3, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Tests & V&V',   'title' => 'Registre de tests liés aux exigences', 'description' => 'Chaque test a un ID (TEST-XXX), une procédure et des exigences couvertes.', 'acceptance_criteria' => "- ID TEST-XXX auto-généré\n- Lien many-to-many avec requirements\n- Type : manuel, automatisé, review", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'Tests & V&V',   'title' => 'Historique des exécutions de tests', 'description' => 'Chaque exécution de test enregistre : date, résultat, exécuteur, notes.', 'acceptance_criteria' => "- Résultat pass/fail/skip\n- Horodatage automatique\n- Historique consultable", 'priority' => 'P0', 'vv_status' => 'in_test', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Planning',      'title' => 'Tâches avec dépendances', 'description' => 'Créer des tâches liées à un module, avec dates et dépendances entre tâches.', 'acceptance_criteria' => "- Tâche liée à un module\n- Statut : À faire, En cours, Terminé, Bloqué\n- Dépendances entre tâches", 'priority' => 'P1', 'vv_status' => 'in_test', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Planning',      'title' => 'Vue Gantt interactive', 'description' => 'Visualisation timeline des tâches avec dépendances, groupées par module.', 'acceptance_criteria' => "- Timeline avec frappe-gantt\n- Groupement par module\n- Flèches de dépendances", 'priority' => 'P2', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 3, 'risk_detectability' => 3],
            ['module' => 'Architecture',  'title' => 'Diagrammes Mermaid auto-générés', 'description' => 'Génération automatique du diagramme d\'architecture depuis les modules et dépendances.', 'acceptance_criteria' => "- Diagramme Mermaid rendu côté client\n- Blocs cliquables vers les modules\n- Édition du source Mermaid", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'ADR',           'title' => 'CRUD ADR avec statuts', 'description' => 'Créer des Architecture Decision Records avec statut (Proposé → Accepté → Déprécié).', 'acceptance_criteria' => "- ID ADR-XXX auto-généré\n- Template pré-rempli\n- Liens vers modules et requirements", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 2, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Dashboard',     'title' => 'Dashboard santé projet', 'description' => 'Vue synthétique avec santé par module, couverture, alertes et activité récente.', 'acceptance_criteria' => "- Santé par module (4 axes)\n- Alertes : REQ sans tests, tests échoués\n- Activité récente", 'priority' => 'P0', 'vv_status' => 'untested', 'risk_impact' => 3, 'risk_probability' => 3, 'risk_detectability' => 2],
            ['module' => 'Dashboard',     'title' => 'Deploy Readiness Review', 'description' => 'Check GO/NO-GO automatique par module avant déploiement.', 'acceptance_criteria' => "- GO/NO-GO par module\n- Seuil configurable\n- Endpoint API pour la CI", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 3],
            // New requirements (REQ-014..016)
            ['module' => 'Dashboard',     'title' => 'Context Brief IA', 'description' => 'Générer un context brief IA résumant l\'état du projet pour un LLM assistant.', 'acceptance_criteria' => "- Résumé structuré automatique\n- Export JSON / Markdown\n- Inclut REQ, tests, anomalies, ADR", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 4],
            ['module' => 'Dashboard',     'title' => 'Baselines immuables', 'description' => 'Créer des snapshots immuables de l\'état du projet à un instant T.', 'acceptance_criteria' => "- Snapshot JSON complet\n- Immuable après création\n- Comparaison entre baselines", 'priority' => 'P2', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 1, 'risk_detectability' => 2],
            ['module' => 'Dashboard',     'title' => 'Anomaly Taxonomy', 'description' => 'Classification des anomalies en 3 types : anomalie, non-conformité, défaut.', 'acceptance_criteria' => "- 3 types avec préfixes distincts (ANOM, NC, DEF)\n- Sévérité et statut par anomalie\n- Lien optionnel vers requirement", 'priority' => 'P1', 'vv_status' => 'in_test', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 3],
        ])->map(function ($data) use ($orbiter, $oMods) {
            return Requirement::create([
                'project_id' => $orbiter->id,
                'module_id' => $oMods[$data['module']]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'acceptance_criteria' => $data['acceptance_criteria'],
                'priority' => $data['priority'],
                'vv_status' => $data['vv_status'],
                'risk_impact' => $data['risk_impact'],
                'risk_probability' => $data['risk_probability'],
                'risk_detectability' => $data['risk_detectability'],
            ]);
        });

        // --- Orbiter Tests ---
        $oTests = collect([
            ['title' => 'Création de projet avec slug unique', 'procedure' => "1. POST /projects avec name='Test Project'\n2. Vérifier que le slug est 'test-project'\n3. Vérifier que le projet apparaît dans la liste", 'expected_result' => 'Projet créé avec slug unique auto-généré', 'type' => 'automated', 'reqs' => [0]],
            ['title' => 'Attribution de rôle membre', 'procedure' => "1. Ajouter un utilisateur au projet\n2. Assigner le rôle 'member'\n3. Vérifier les permissions", 'expected_result' => 'Le membre a les permissions correspondant à son rôle', 'type' => 'automated', 'reqs' => [1]],
            ['title' => 'Auto-génération REQ-XXX', 'procedure' => "1. Créer une requirement dans un projet\n2. Vérifier que ref = REQ-001\n3. Créer une seconde → REQ-002", 'expected_result' => 'Les IDs sont séquentiels et formatés REQ-XXX', 'type' => 'automated', 'reqs' => [2]],
            ['title' => 'Recalcul automatique statut V&V', 'procedure' => "1. Créer REQ + test lié\n2. Enregistrer execution pass → statut vérifié\n3. Enregistrer execution fail → statut échoué", 'expected_result' => 'Le statut V&V reflète les résultats des tests', 'type' => 'automated', 'reqs' => [3]],
            ['title' => 'Calcul Risk Score FMEA', 'procedure' => "1. Créer REQ avec impact=4, prob=3, detect=2\n2. Vérifier score = 4×3×(6-2) = 48", 'expected_result' => 'Score calculé = Impact × Probabilité × (6 - Détectabilité)', 'type' => 'automated', 'reqs' => [4]],
            ['title' => 'Registre de tests many-to-many', 'procedure' => "1. Créer un test avec TEST-XXX auto\n2. Lier à 2 requirements\n3. Vérifier la relation bidirectionnelle", 'expected_result' => 'Test lié aux requirements et inversement', 'type' => 'automated', 'reqs' => [5]],
            ['title' => 'Historique des exécutions', 'procedure' => "1. Exécuter un test 3 fois\n2. Vérifier l'historique chronologique\n3. Vérifier que last_result retourne le dernier résultat", 'expected_result' => 'Historique complet avec last_result correct', 'type' => 'automated', 'reqs' => [6]],
            ['title' => 'Dépendances entre tâches', 'procedure' => "1. Créer tâche A et B\n2. B bloqué par A\n3. Vérifier que B.blockedBy contient A", 'expected_result' => 'Les dépendances sont correctement enregistrées', 'type' => 'automated', 'reqs' => [7]],
            ['title' => 'ADR avec liens modules', 'procedure' => "1. Créer ADR\n2. Lier à 2 modules\n3. Vérifier les pivots", 'expected_result' => 'ADR lié aux modules via pivot', 'type' => 'automated', 'reqs' => [10]],
            ['title' => 'Validation client en staging', 'procedure' => "1. Déployer en staging\n2. Client teste les features\n3. Client clique 'Validé'", 'expected_result' => 'REQ passe en statut validated', 'type' => 'review', 'reqs' => [0, 2]],
        ])->map(function ($data) use ($orbiter, $oReqs) {
            $test = Test::create([
                'project_id' => $orbiter->id,
                'title' => $data['title'],
                'procedure' => $data['procedure'],
                'expected_result' => $data['expected_result'],
                'type' => $data['type'],
            ]);
            foreach ($data['reqs'] as $reqIndex) {
                $test->requirements()->attach($oReqs[$reqIndex]->id);
            }

            return $test;
        });

        // --- Orbiter Test Executions (original single run + history runs) ---

        // Original single-run executions (baseline)
        $baseExecutions = [
            [0, 'pass', null, 14],
            [1, 'pass', null, 12],
            [2, 'pass', null, 11],
            [3, 'pass', null, 10],
            [4, 'pass', null, 9],
            [5, 'pass', null, 8],
            [6, 'fail', 'Régression détectée sur le calcul du résultat', 8],
            [7, 'pass', null, 6],
            [8, 'pass', null, 5],
            [9, 'pass', null, 4],
        ];
        foreach ($baseExecutions as [$testIdx, $result, $notes, $daysAgo]) {
            TestExecution::create([
                'test_id' => $oTests[$testIdx]->id,
                'result' => $result,
                'executed_by' => $demo->id,
                'executed_at' => now()->subDays($daysAgo),
                'notes' => $notes,
            ]);
        }

        // History runs — Test 0 (slug creation): pass 14d, pass 7d, pass 2d ago
        TestExecution::create(['test_id' => $oTests[0]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(7), 'notes' => null]);
        TestExecution::create(['test_id' => $oTests[0]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(2), 'notes' => null]);

        // History runs — Test 3 (V&V recalcul): fail 10d, fail 5d, pass 1d ago
        TestExecution::create(['test_id' => $oTests[3]->id, 'result' => 'fail', 'executed_by' => $demo->id, 'executed_at' => now()->subDays(5), 'notes' => 'Statut ne repasse pas à failed après un test fail']);
        TestExecution::create(['test_id' => $oTests[3]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(1), 'notes' => 'Corrigé — observer événement ajouté']);

        // History runs — Test 6 (historique): skip 8d, fail 4d ago
        TestExecution::create(['test_id' => $oTests[6]->id, 'result' => 'skip', 'executed_by' => $bob->id, 'executed_at' => now()->subDays(8), 'notes' => 'Skipped — feature pas encore implémentée']);
        TestExecution::create(['test_id' => $oTests[6]->id, 'result' => 'fail', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(4), 'notes' => 'Tri par date incorrect — ORDER BY created_at au lieu de executed_at']);

        // --- Orbiter Tasks ---
        $oTaskData = [
            ['module' => 'Projects',      'title' => 'Migrations et modèles Project/User', 'status' => 'done', 'progress' => 100, 'days_offset' => [-14, -7], 'assignee' => $demo],
            ['module' => 'Projects',      'title' => 'CRUD Projects + vues Blade', 'status' => 'done', 'progress' => 100, 'days_offset' => [-7, -3], 'assignee' => $demo],
            ['module' => 'Requirements',  'title' => 'Modèle Requirement + HasSequentialRef', 'status' => 'done', 'progress' => 100, 'days_offset' => [-10, -5], 'assignee' => $demo],
            ['module' => 'Requirements',  'title' => 'Livewire RequirementList avec filtres', 'status' => 'done', 'progress' => 100, 'days_offset' => [-5, -2], 'assignee' => $alice],
            ['module' => 'Requirements',  'title' => 'Vue détail Requirement + Risk Score', 'status' => 'in_progress', 'progress' => 70, 'days_offset' => [-2, 3], 'assignee' => $alice],
            ['module' => 'Tests & V&V',   'title' => 'Modèle Test + TestExecution', 'status' => 'done', 'progress' => 100, 'days_offset' => [-8, -4], 'assignee' => $demo],
            ['module' => 'Tests & V&V',   'title' => 'Matrice de traçabilité Livewire', 'status' => 'in_progress', 'progress' => 40, 'days_offset' => [-1, 5], 'assignee' => $alice],
            ['module' => 'Planning',      'title' => 'Modèle Task + dépendances', 'status' => 'done', 'progress' => 100, 'days_offset' => [-6, -3], 'assignee' => $demo],
            ['module' => 'Planning',      'title' => 'Vue Kanban Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [2, 8], 'assignee' => $alice],
            ['module' => 'Planning',      'title' => 'Intégration frappe-gantt', 'status' => 'todo', 'progress' => 0, 'days_offset' => [5, 12], 'assignee' => $demo],
            ['module' => 'Architecture',  'title' => 'Génération Mermaid depuis modules', 'status' => 'in_progress', 'progress' => 60, 'days_offset' => [-3, 4], 'assignee' => $demo],
            ['module' => 'Architecture',  'title' => 'Éditeur de diagramme Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [4, 10], 'assignee' => $alice],
            ['module' => 'ADR',           'title' => 'CRUD ADR + vues Blade', 'status' => 'done', 'progress' => 100, 'days_offset' => [-5, -1], 'assignee' => $demo],
            ['module' => 'Dashboard',     'title' => 'HealthWidget Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [3, 10], 'assignee' => $alice],
            ['module' => 'Dashboard',     'title' => 'AlertsWidget Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [5, 12], 'assignee' => $demo],
            ['module' => 'Dashboard',     'title' => 'Deploy Readiness endpoint API', 'status' => 'blocked', 'progress' => 0, 'days_offset' => [8, 15], 'assignee' => $demo],
        ];

        $oTasks = collect($oTaskData)->map(function ($data) use ($orbiter, $oMods) {
            return Task::create([
                'project_id' => $orbiter->id,
                'module_id' => $oMods[$data['module']]->id,
                'title' => $data['title'],
                'assignee_id' => $data['assignee']->id,
                'status' => $data['status'],
                'progress' => $data['progress'],
                'start_date' => now()->addDays($data['days_offset'][0]),
                'end_date' => now()->addDays($data['days_offset'][1]),
            ]);
        });

        // Task dependencies
        $oTasks[3]->blockedBy()->attach($oTasks[2]->id);  // RequirementList blocked by Requirement model
        $oTasks[4]->blockedBy()->attach($oTasks[3]->id);  // Requirement detail blocked by list
        $oTasks[6]->blockedBy()->attach($oTasks[5]->id);  // Traceability matrix blocked by Test model
        $oTasks[9]->blockedBy()->attach($oTasks[8]->id);  // Gantt blocked by Kanban
        $oTasks[11]->blockedBy()->attach($oTasks[10]->id); // Diagram editor blocked by Mermaid gen
        $oTasks[14]->blockedBy()->attach($oTasks[13]->id); // Alerts blocked by Health
        $oTasks[15]->blockedBy()->attach($oTasks[14]->id); // Deploy readiness blocked by Alerts

        // --- Orbiter ADRs (original 4 + 2 new = 6) ---
        $oAdrs = collect([
            ['title' => 'Blade + Livewire plutôt qu\'un SPA', 'context' => 'Un outil de gestion de projet est essentiellement composé de listes, tableaux, formulaires et modales.', 'decision' => 'Utiliser Blade Components + Livewire 3 pour toute l\'interface. Zéro framework JS frontend.', 'consequences' => 'Simplicité, performance SSR, moins de bugs frontend. Certaines interactions riches nécessiteront Alpine.js.', 'status' => 'accepted', 'author' => $demo, 'modules' => ['Projects', 'Requirements', 'Dashboard']],
            ['title' => 'PostgreSQL pour le support JSONB', 'context' => 'Orbiter stocke des données semi-structurées (baselines, deploy readiness, tags).', 'decision' => 'Utiliser PostgreSQL 16 comme base de données principale.', 'consequences' => 'JSONB natif avec indexation GIN. Moins répandu que MySQL dans l\'écosystème Laravel.', 'status' => 'accepted', 'author' => $demo, 'modules' => ['Projects']],
            ['title' => 'Mermaid.js pour les diagrammes', 'context' => 'Orbiter a besoin de visualiser l\'architecture des modules et leurs dépendances.', 'decision' => 'Utiliser Mermaid.js pour le rendu côté client. Source stocké en base et versionné.', 'consequences' => 'Rendu côté client, syntaxe texte versionnée. Limité pour les diagrammes très complexes.', 'status' => 'accepted', 'author' => $demo, 'modules' => ['Architecture']],
            ['title' => 'Simplicité radicale — Zéro SPA', 'context' => 'Le navigateur moderne offre nativement : dialog, popover, details/summary, CSS transitions.', 'decision' => 'Exploiter au maximum les APIs natives du navigateur. Zéro framework JS frontend.', 'consequences' => 'Moins de dépendances, performance native. Certains patterns UI modernes sont plus verbeux.', 'status' => 'accepted', 'author' => $demo, 'modules' => ['Projects', 'Requirements', 'Tests & V&V', 'Dashboard']],
            // New ADRs
            ['title' => 'FrankenPHP plutôt qu\'Apache/Nginx', 'context' => 'Le déploiement nécessite un serveur HTTP performant avec support natif de PHP. FrankenPHP offre un worker mode qui garde l\'app en mémoire.', 'decision' => 'Utiliser FrankenPHP comme serveur HTTP en production et développement. Worker mode activé pour les performances.', 'consequences' => 'Performances 2-3x vs php-fpm. Écosystème encore jeune, moins de documentation.', 'status' => 'accepted', 'author' => $alice, 'modules' => ['Projects']],
            ['title' => 'Docker Compose pour le dev local', 'context' => 'L\'équipe a besoin d\'un environnement de développement reproductible avec PostgreSQL, Redis et Mailpit.', 'decision' => 'Fournir un docker-compose.yml avec tous les services. Un seul `docker compose up` pour démarrer.', 'consequences' => 'Onboarding simplifié. Requiert Docker Desktop installé sur la machine du développeur.', 'status' => 'accepted', 'author' => $alice, 'modules' => ['Projects']],
        ])->map(function ($data) use ($orbiter, $oMods) {
            $adr = Adr::create([
                'project_id' => $orbiter->id,
                'title' => $data['title'],
                'context' => $data['context'],
                'decision' => $data['decision'],
                'consequences' => $data['consequences'],
                'status' => $data['status'],
                'author_id' => $data['author']->id,
            ]);
            foreach ($data['modules'] as $moduleName) {
                $adr->modules()->attach($oMods[$moduleName]->id);
            }

            return $adr;
        });

        // --- Orbiter Diagrams (original + new V&V flow) ---
        Diagram::create([
            'project_id' => $orbiter->id,
            'title' => 'Architecture Orbiter v1',
            'mermaid_source' => <<<'MERMAID'
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
MERMAID,
            'version' => 1,
        ]);

        Diagram::create([
            'project_id' => $orbiter->id,
            'title' => 'Flux V&V — Du commit au déploiement',
            'mermaid_source' => <<<'MERMAID'
sequenceDiagram
    participant Dev as Developer
    participant Git as Git Repository
    participant CI as CI Pipeline
    participant API as Orbiter API
    participant VV as V&V Status
    participant DR as Deploy Readiness

    Dev->>Git: git push (feature branch)
    Git->>CI: Webhook trigger
    CI->>CI: Run test suite
    CI->>API: POST /executions (results)
    API->>VV: Recalculate V&V status
    VV-->>API: Updated statuses
    API->>DR: Check GO/NO-GO
    DR-->>API: Module readiness
    alt All modules GO
        API->>CI: Deploy approved
        CI->>Dev: ✅ Deployment successful
    else Any module NO-GO
        API->>CI: Deploy blocked
        CI->>Dev: ❌ Blocking items list
    end
MERMAID,
            'version' => 1,
        ]);

        // --- Orbiter Lessons (original 3 + 2 new = 5) ---
        Lesson::create([
            'project_id' => $orbiter->id,
            'title' => 'Le trait HasSequentialRef doit gérer la concurrence',
            'description' => 'Lors des tests de charge, deux requirements créées simultanément ont reçu le même ref REQ-005. Solution : utiliser un lock advisory PostgreSQL dans le trait.',
            'module_id' => $oMods['Requirements']->id,
            'tags' => ['concurrence', 'postgresql', 'trait'],
            'author_id' => $demo->id,
        ]);

        Lesson::create([
            'project_id' => $orbiter->id,
            'title' => 'Les modales <dialog> gèrent le focus trap nativement',
            'description' => 'Pas besoin de JS pour le focus trap. Le <dialog> natif bloque le scroll, capture le focus et gère Escape. Énorme gain de simplicité vs une modale React/Vue.',
            'module_id' => $oMods['Dashboard']->id,
            'tags' => ['frontend', 'dialog', 'simplicity'],
            'author_id' => $demo->id,
        ]);

        Lesson::create([
            'project_id' => $orbiter->id,
            'title' => 'Toujours eager-load les relations dans les listes',
            'description' => 'La page RequirementList faisait 200+ requêtes à cause du N+1 sur module et tests. Résolu avec ->with([\'module\', \'tests\']). Règle : toujours utiliser with() dans les listes.',
            'module_id' => $oMods['Requirements']->id,
            'requirement_id' => $oReqs[2]->id,
            'tags' => ['performance', 'eloquent', 'n+1'],
            'author_id' => $demo->id,
        ]);

        Lesson::create([
            'project_id' => $orbiter->id,
            'title' => 'PostgreSQL advisory locks pour la concurrence des refs',
            'description' => 'Les advisory locks PostgreSQL (pg_advisory_xact_lock) sont parfaits pour sérialiser la génération de refs séquentielles. Pas de deadlock, pas de table lock, et le lock est libéré automatiquement à la fin de la transaction.',
            'module_id' => $oMods['Requirements']->id,
            'tags' => ['postgresql', 'concurrence', 'locking'],
            'author_id' => $alice->id,
        ]);

        Lesson::create([
            'project_id' => $orbiter->id,
            'title' => 'Le pattern Service + Controller garde les controllers fins',
            'description' => 'En extrayant la logique métier dans des Service classes (ProjectService, RequirementService), les controllers restent à ~10 lignes par méthode. Plus facile à tester, plus facile à réutiliser depuis Livewire ou l\'API.',
            'module_id' => $oMods['Projects']->id,
            'tags' => ['architecture', 'patterns', 'clean-code'],
            'author_id' => $alice->id,
        ]);

        // --- Orbiter Baselines (original + new v0.2.0-rc1) ---
        Baseline::create([
            'project_id' => $orbiter->id,
            'ref' => 'v0.1.0',
            'title' => 'Baseline initiale — Bootstrap',
            'description' => 'Premier snapshot après le bootstrap du projet. Modèles, migrations et seeder en place.',
            'snapshot' => [
                'requirements_count' => 13,
                'requirements_by_status' => [
                    'validated' => 2,
                    'verified' => 5,
                    'in_test' => 2,
                    'untested' => 4,
                ],
                'tests_count' => 10,
                'modules_count' => 7,
                'adrs_count' => 4,
            ],
            'signed_by' => 'Demo User',
            'is_immutable' => true,
        ]);

        Baseline::create([
            'project_id' => $orbiter->id,
            'ref' => 'v0.2.0-rc1',
            'title' => 'Release Candidate — Phase 2 CRUD',
            'description' => 'Snapshot avant la release v0.2.0. CRUD complet pour tous les modules, mais Deploy Readiness NO-GO à cause de tests manquants.',
            'snapshot' => [
                'requirements_count' => $oReqs->count(),
                'requirements_by_status' => $oReqs->groupBy('vv_status')->map->count()->toArray(),
                'tests_count' => $oTests->count(),
                'modules_count' => $oMods->count(),
                'adrs_count' => $oAdrs->count(),
                'anomalies_open' => 3,
                'lessons_count' => 5,
                'deploy_readiness' => 'no_go',
            ],
            'signed_by' => null,
            'is_immutable' => true,
        ]);

        // --- Orbiter Anomalies (original 2 + 3 new = 5) ---
        Anomaly::create([
            'project_id' => $orbiter->id,
            'title' => 'Historique exécutions ne trie pas par date',
            'description' => 'Les exécutions de tests s\'affichent dans l\'ordre de création, pas par date d\'exécution. Impact faible mais confus pour l\'utilisateur.',
            'type' => 'anomaly',
            'module_id' => $oMods['Tests & V&V']->id,
            'severity' => 'low',
            'status' => 'open',
            'assignee_id' => $demo->id,
        ]);

        Anomaly::create([
            'project_id' => $orbiter->id,
            'title' => 'Le statut V&V ne repasse pas à "échoué" après un test fail',
            'description' => 'Quand un test exécuté fail, le statut V&V de la REQ associée devrait repasser à "failed" automatiquement. Actuellement, il reste sur "verified".',
            'type' => 'non_conformity',
            'requirement_id' => $oReqs[3]->id,
            'module_id' => $oMods['Requirements']->id,
            'severity' => 'high',
            'status' => 'investigating',
            'assignee_id' => $demo->id,
        ]);

        Anomaly::create([
            'project_id' => $orbiter->id,
            'title' => 'Erreur 500 sur création de module sans nom',
            'description' => 'La création d\'un module avec un champ name vide renvoie une erreur 500 au lieu d\'une validation 422. Le FormRequest n\'a pas de règle required sur name.',
            'type' => 'defect',
            'module_id' => $oMods['Projects']->id,
            'severity' => 'critical',
            'status' => 'resolved',
            'assignee_id' => $alice->id,
            'resolved_at' => now()->subDays(2),
        ]);

        Anomaly::create([
            'project_id' => $orbiter->id,
            'title' => 'Le Risk Score affiche N/A quand un seul champ est rempli',
            'description' => 'Si l\'utilisateur remplit uniquement risk_impact mais pas risk_probability ni risk_detectability, le score affiche "N/A" sans explication. Devrait indiquer quels champs manquent.',
            'type' => 'anomaly',
            'module_id' => $oMods['Requirements']->id,
            'severity' => 'low',
            'status' => 'open',
            'assignee_id' => $bob->id,
        ]);

        Anomaly::create([
            'project_id' => $orbiter->id,
            'title' => 'Les filtres de la liste requirements ne persistent pas',
            'description' => 'Quand on filtre par module ou statut dans RequirementList puis qu\'on clique sur un requirement et revient en arrière, les filtres sont réinitialisés. Les filtres devraient persister via query string.',
            'type' => 'non_conformity',
            'requirement_id' => $oReqs[2]->id,
            'module_id' => $oMods['Requirements']->id,
            'severity' => 'medium',
            'status' => 'open',
            'assignee_id' => $alice->id,
        ]);

        // --- Orbiter Deploy Readiness Review ---
        DeployReadinessReview::create([
            'project_id' => $orbiter->id,
            'ref' => 'DRR-001',
            'target_version' => 'v0.2.0',
            'result' => 'no_go',
            'decided_by' => $demo->id,
            'decided_at' => now()->subDays(3),
            'module_statuses' => [
                'Projects' => ['status' => 'go', 'reason' => '100% REQ verified'],
                'Requirements' => ['status' => 'go', 'reason' => '85% REQ verified'],
                'Tests & V&V' => ['status' => 'no_go', 'reason' => '1 test failing (TEST-007)'],
                'Planning' => ['status' => 'no_go', 'reason' => '50% REQ untested'],
                'Architecture' => ['status' => 'go', 'reason' => 'No blocking items'],
                'ADR' => ['status' => 'go', 'reason' => 'All ADR accepted'],
                'Dashboard' => ['status' => 'no_go', 'reason' => '0% REQ tested'],
            ],
            'blocking_items' => [
                ['type' => 'test_failure', 'ref' => 'TEST-007', 'reason' => 'Historique des exécutions régression'],
                ['type' => 'untested_requirement', 'ref' => 'REQ-009', 'reason' => 'Vue Gantt non testée'],
                ['type' => 'untested_requirement', 'ref' => 'REQ-012', 'reason' => 'Dashboard non testé'],
            ],
            'override_reason' => null,
        ]);

        // ================================================================
        // PROJECT 2 — API Gateway (in progress, owned by Alice)
        // ================================================================

        $gateway = Project::create([
            'name' => 'API Gateway',
            'description' => 'Gateway HTTP unifié pour les microservices internes. Authentification centralisée, rate limiting et observabilité.',
        ]);

        $gateway->members()->attach($alice->id, ['role' => 'owner']);
        $gateway->members()->attach($demo->id, ['role' => 'member']);

        // --- Gateway Modules ---
        $gMods = collect([
            ['name' => 'Auth', 'description' => 'Authentification JWT et OAuth2 pour les clients API', 'status' => 'active', 'owner' => $alice],
            ['name' => 'Routing', 'description' => 'Routage dynamique vers les microservices backend', 'status' => 'active', 'owner' => $alice],
            ['name' => 'Rate Limiting', 'description' => 'Limitation de débit par client, endpoint et plan tarifaire', 'status' => 'draft', 'owner' => $demo],
            ['name' => 'Logging', 'description' => 'Journalisation structurée des requêtes et métriques de performance', 'status' => 'draft', 'owner' => $demo],
        ])->mapWithKeys(function ($data) use ($gateway) {
            $module = Module::create([
                'project_id' => $gateway->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'owner_id' => $data['owner']->id,
                'status' => $data['status'],
            ]);

            return [$data['name'] => $module];
        });

        // Gateway module dependencies
        $gMods['Routing']->dependencies()->attach($gMods['Auth']->id, ['type' => 'depends_on']);
        $gMods['Rate Limiting']->dependencies()->attach($gMods['Routing']->id, ['type' => 'depends_on']);
        $gMods['Logging']->dependencies()->attach($gMods['Routing']->id, ['type' => 'depends_on']);

        // --- Gateway Requirements ---
        $gReqs = collect([
            ['module' => 'Auth',          'title' => 'Authentification JWT avec refresh tokens', 'description' => 'Les clients API s\'authentifient via JWT. Les tokens expirent après 15 min avec refresh token de 7 jours.', 'acceptance_criteria' => "- Endpoint POST /auth/token\n- JWT signé RS256\n- Refresh token en base", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 5, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'Auth',          'title' => 'Support OAuth2 authorization code flow', 'description' => 'Implémenter le flow OAuth2 authorization code pour les applications tierces.', 'acceptance_criteria' => "- /authorize et /token endpoints\n- PKCE obligatoire\n- Scopes granulaires", 'priority' => 'P1', 'vv_status' => 'in_test', 'risk_impact' => 4, 'risk_probability' => 3, 'risk_detectability' => 2],
            ['module' => 'Routing',       'title' => 'Routage basé sur configuration YAML', 'description' => 'Les routes sont définies en YAML et chargées au démarrage. Hot-reload sans downtime.', 'acceptance_criteria' => "- Fichier routes.yaml\n- Validation au chargement\n- Hot-reload via signal", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 5, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Routing',       'title' => 'Health check et circuit breaker', 'description' => 'Vérification périodique de la santé des backends. Circuit breaker automatique en cas de défaillance.', 'acceptance_criteria' => "- Health check toutes les 10s\n- Circuit breaker après 3 failures\n- Half-open après 30s", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Rate Limiting', 'title' => 'Rate limiting par sliding window', 'description' => 'Limitation de débit basée sur un sliding window Redis. Configurable par client et endpoint.', 'acceptance_criteria' => "- Sliding window algorithm\n- Headers X-RateLimit-*\n- Redis backend", 'priority' => 'P0', 'vv_status' => 'in_test', 'risk_impact' => 4, 'risk_probability' => 3, 'risk_detectability' => 1],
            ['module' => 'Rate Limiting', 'title' => 'Plans tarifaires et quotas', 'description' => 'Définir des plans (free, pro, enterprise) avec des quotas différents par plan.', 'acceptance_criteria' => "- 3 plans prédéfinis\n- Quotas par minute et par jour\n- Upgrade sans interruption", 'priority' => 'P2', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 2, 'risk_detectability' => 3],
            ['module' => 'Logging',       'title' => 'Journalisation structurée JSON', 'description' => 'Chaque requête produit un log structuré JSON avec request_id, latency, status code.', 'acceptance_criteria' => "- Format JSON structuré\n- Correlation ID propagé\n- Latency en ms", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 3, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Logging',       'title' => 'Export vers OpenTelemetry', 'description' => 'Exporter les traces et métriques vers un collecteur OpenTelemetry compatible.', 'acceptance_criteria' => "- OTLP gRPC exporter\n- Traces avec spans\n- Métriques RED (Rate, Errors, Duration)", 'priority' => 'P2', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 3, 'risk_detectability' => 3],
        ])->map(function ($data) use ($gateway, $gMods) {
            return Requirement::create([
                'project_id' => $gateway->id,
                'module_id' => $gMods[$data['module']]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'acceptance_criteria' => $data['acceptance_criteria'],
                'priority' => $data['priority'],
                'vv_status' => $data['vv_status'],
                'risk_impact' => $data['risk_impact'],
                'risk_probability' => $data['risk_probability'],
                'risk_detectability' => $data['risk_detectability'],
            ]);
        });

        // --- Gateway Tests ---
        $gTests = collect([
            ['title' => 'JWT token generation and validation', 'procedure' => "1. POST /auth/token avec credentials valides\n2. Vérifier le JWT RS256\n3. Appeler un endpoint protégé\n4. Vérifier que le token expiré est rejeté", 'expected_result' => 'JWT valide émis et validé correctement', 'type' => 'automated', 'reqs' => [0]],
            ['title' => 'Route matching et proxy', 'procedure' => "1. Charger routes.yaml avec 3 backends\n2. Envoyer requête GET /api/users\n3. Vérifier le proxy vers le bon backend\n4. Vérifier les headers propagés", 'expected_result' => 'Requête routée vers le bon backend', 'type' => 'automated', 'reqs' => [2]],
            ['title' => 'Sliding window rate limit', 'procedure' => "1. Configurer limit 10 req/min\n2. Envoyer 10 requêtes → toutes passent\n3. 11ème requête → 429 Too Many Requests\n4. Attendre 1 min → requête passe", 'expected_result' => 'Rate limiting respecte le sliding window', 'type' => 'automated', 'reqs' => [4]],
            ['title' => 'Structured log output', 'procedure' => "1. Envoyer une requête à travers le gateway\n2. Capturer le log JSON\n3. Vérifier les champs request_id, latency, status", 'expected_result' => 'Log JSON structuré avec tous les champs requis', 'type' => 'automated', 'reqs' => [6]],
        ])->map(function ($data) use ($gateway, $gReqs) {
            $test = Test::create([
                'project_id' => $gateway->id,
                'title' => $data['title'],
                'procedure' => $data['procedure'],
                'expected_result' => $data['expected_result'],
                'type' => $data['type'],
            ]);
            foreach ($data['reqs'] as $reqIndex) {
                $test->requirements()->attach($gReqs[$reqIndex]->id);
            }

            return $test;
        });

        // Gateway test executions
        TestExecution::create(['test_id' => $gTests[0]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(5), 'notes' => null]);
        TestExecution::create(['test_id' => $gTests[1]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(4), 'notes' => null]);
        TestExecution::create(['test_id' => $gTests[2]->id, 'result' => 'fail', 'executed_by' => $demo->id, 'executed_at' => now()->subDays(3), 'notes' => 'Off-by-one dans le calcul du window boundary']);
        TestExecution::create(['test_id' => $gTests[2]->id, 'result' => 'pass', 'executed_by' => $demo->id, 'executed_at' => now()->subDays(1), 'notes' => 'Fix appliqué — boundary inclusif']);
        TestExecution::create(['test_id' => $gTests[3]->id, 'result' => 'pass', 'executed_by' => $alice->id, 'executed_at' => now()->subDays(2), 'notes' => null]);

        // --- Gateway Tasks ---
        $gTaskData = [
            ['module' => 'Auth',          'title' => 'Implémentation JWT RS256', 'status' => 'done', 'progress' => 100, 'days_offset' => [-10, -5], 'assignee' => $alice],
            ['module' => 'Auth',          'title' => 'Flow OAuth2 PKCE', 'status' => 'in_progress', 'progress' => 60, 'days_offset' => [-3, 4], 'assignee' => $alice],
            ['module' => 'Routing',       'title' => 'Parser YAML + proxy middleware', 'status' => 'done', 'progress' => 100, 'days_offset' => [-8, -3], 'assignee' => $demo],
            ['module' => 'Rate Limiting', 'title' => 'Redis sliding window', 'status' => 'in_progress', 'progress' => 80, 'days_offset' => [-2, 3], 'assignee' => $demo],
            ['module' => 'Logging',       'title' => 'Structured JSON logger', 'status' => 'done', 'progress' => 100, 'days_offset' => [-6, -2], 'assignee' => $alice],
        ];

        $gTasks = collect($gTaskData)->map(function ($data) use ($gateway, $gMods) {
            return Task::create([
                'project_id' => $gateway->id,
                'module_id' => $gMods[$data['module']]->id,
                'title' => $data['title'],
                'assignee_id' => $data['assignee']->id,
                'status' => $data['status'],
                'progress' => $data['progress'],
                'start_date' => now()->addDays($data['days_offset'][0]),
                'end_date' => now()->addDays($data['days_offset'][1]),
            ]);
        });

        $gTasks[1]->blockedBy()->attach($gTasks[0]->id); // OAuth2 blocked by JWT
        $gTasks[3]->blockedBy()->attach($gTasks[2]->id); // Rate limiting blocked by routing

        // --- Gateway ADRs ---
        Adr::create([
            'project_id' => $gateway->id,
            'title' => 'Redis pour le rate limiting et le cache',
            'context' => 'Le rate limiting sliding window nécessite des opérations atomiques rapides avec TTL.',
            'decision' => 'Utiliser Redis 7 avec Lua scripts pour les opérations atomiques de rate limiting.',
            'consequences' => 'Performance optimale pour le rate limiting. Ajoute Redis comme dépendance infrastructure.',
            'status' => 'accepted',
            'author_id' => $demo->id,
        ])->modules()->attach($gMods['Rate Limiting']->id);

        Adr::create([
            'project_id' => $gateway->id,
            'title' => 'OpenTelemetry plutôt que Prometheus direct',
            'context' => 'L\'observabilité doit être vendor-neutral pour supporter différents backends (Grafana, Datadog, etc.).',
            'decision' => 'Exporter via le protocole OTLP. Le collecteur OpenTelemetry fait le fanout vers les backends.',
            'consequences' => 'Vendor-neutral. Le collecteur OTel ajoute un hop réseau supplémentaire.',
            'status' => 'proposed',
            'author_id' => $alice->id,
        ])->modules()->attach($gMods['Logging']->id);

        // ================================================================
        // PROJECT 3 — Mobile App (early stage, owned by Demo User)
        // ================================================================

        $mobile = Project::create([
            'name' => 'Mobile App',
            'description' => 'Application mobile compagnon pour Orbiter. Consultation des dashboards, notifications push et approbation des Deploy Readiness Reviews en mobilité.',
        ]);

        $mobile->members()->attach($demo->id, ['role' => 'owner']);

        // --- Mobile Modules ---
        $mMods = collect([
            ['name' => 'UI', 'description' => 'Interface utilisateur React Native avec navigation et composants partagés', 'status' => 'draft', 'owner' => $demo],
            ['name' => 'Data Sync', 'description' => 'Synchronisation offline-first avec l\'API Orbiter', 'status' => 'draft', 'owner' => $demo],
            ['name' => 'Push Notifications', 'description' => 'Notifications push via Firebase Cloud Messaging pour les alertes projet', 'status' => 'draft', 'owner' => $demo],
        ])->mapWithKeys(function ($data) use ($mobile) {
            $module = Module::create([
                'project_id' => $mobile->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'owner_id' => $data['owner']->id,
                'status' => $data['status'],
            ]);

            return [$data['name'] => $module];
        });

        // Mobile module dependencies
        $mMods['Data Sync']->dependencies()->attach($mMods['UI']->id, ['type' => 'depends_on']);
        $mMods['Push Notifications']->dependencies()->attach($mMods['Data Sync']->id, ['type' => 'depends_on']);

        // --- Mobile Requirements ---
        $mReqs = collect([
            ['module' => 'UI',                  'title' => 'Dashboard projet en lecture seule', 'description' => 'Afficher le dashboard santé projet avec les mêmes données que la version web.', 'acceptance_criteria' => "- Vue dashboard responsive\n- Données en temps réel\n- Pull-to-refresh", 'priority' => 'P0', 'vv_status' => 'untested', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'UI',                  'title' => 'Navigation entre projets', 'description' => 'Switcher entre les projets de l\'utilisateur avec une navigation bottom tab.', 'acceptance_criteria' => "- Bottom tab navigation\n- Liste des projets\n- Dernier projet mémorisé", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Data Sync',           'title' => 'Cache offline des données projet', 'description' => 'Les données du dashboard sont cachées localement pour consultation hors ligne.', 'acceptance_criteria' => "- SQLite local\n- Sync au retour online\n- Indicateur de fraîcheur", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 3, 'risk_probability' => 3, 'risk_detectability' => 3],
            ['module' => 'Push Notifications',  'title' => 'Notifications sur anomalies critiques', 'description' => 'Recevoir une push notification quand une anomalie critique est créée sur un projet suivi.', 'acceptance_criteria' => "- FCM integration\n- Filtre par sévérité\n- Deep link vers l'anomalie", 'priority' => 'P0', 'vv_status' => 'untested', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 2],
        ])->map(function ($data) use ($mobile, $mMods) {
            return Requirement::create([
                'project_id' => $mobile->id,
                'module_id' => $mMods[$data['module']]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'acceptance_criteria' => $data['acceptance_criteria'],
                'priority' => $data['priority'],
                'vv_status' => $data['vv_status'],
                'risk_impact' => $data['risk_impact'],
                'risk_probability' => $data['risk_probability'],
                'risk_detectability' => $data['risk_detectability'],
            ]);
        });

        // --- Mobile Tests (1 test, no executions) ---
        $mTest = Test::create([
            'project_id' => $mobile->id,
            'title' => 'Affichage dashboard projet',
            'procedure' => "1. Ouvrir l'app sur un device\n2. Se connecter avec un compte ayant un projet\n3. Vérifier que le dashboard affiche les mêmes données que le web",
            'expected_result' => 'Dashboard mobile identique au web en lecture seule',
            'type' => 'manual',
        ]);
        $mTest->requirements()->attach($mReqs[0]->id);

        // --- Mobile Tasks (all todo) ---
        Task::create([
            'project_id' => $mobile->id,
            'module_id' => $mMods['UI']->id,
            'title' => 'Setup React Native + navigation',
            'assignee_id' => $demo->id,
            'status' => 'todo',
            'progress' => 0,
            'start_date' => now()->addDays(15),
            'end_date' => now()->addDays(25),
        ]);

        Task::create([
            'project_id' => $mobile->id,
            'module_id' => $mMods['Data Sync']->id,
            'title' => 'Client API + SQLite cache',
            'assignee_id' => $demo->id,
            'status' => 'todo',
            'progress' => 0,
            'start_date' => now()->addDays(20),
            'end_date' => now()->addDays(35),
        ]);

        Task::create([
            'project_id' => $mobile->id,
            'module_id' => $mMods['Push Notifications']->id,
            'title' => 'Intégration Firebase Cloud Messaging',
            'assignee_id' => $demo->id,
            'status' => 'todo',
            'progress' => 0,
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(40),
        ]);
    }
}
