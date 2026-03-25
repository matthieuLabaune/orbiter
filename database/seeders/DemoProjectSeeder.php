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
        // Demo user
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@orbiter.dev',
            'password' => bcrypt('password'),
        ]);

        // Project
        $project = Project::create([
            'name' => 'Orbiter v1',
            'description' => 'Outil de gestion de projet open source inspiré de l\'ingénierie système NASA/SpaceX. Traçabilité complète exigence → test → code → preuve.',
        ]);

        $project->members()->attach($user->id, ['role' => 'owner']);

        // Modules
        $modules = collect([
            ['name' => 'Projects', 'description' => 'Gestion des projets, membres et rôles', 'status' => 'active'],
            ['name' => 'Requirements', 'description' => 'Exigences avec traçabilité, versioning et statut V&V', 'status' => 'active'],
            ['name' => 'Tests & V&V', 'description' => 'Registre de tests, exécutions et couverture des exigences', 'status' => 'active'],
            ['name' => 'Planning', 'description' => 'Tâches, dépendances, Gantt et Kanban', 'status' => 'active'],
            ['name' => 'Architecture', 'description' => 'Diagrammes Mermaid et visualisation des modules', 'status' => 'active'],
            ['name' => 'ADR', 'description' => 'Architecture Decision Records — journal des décisions', 'status' => 'active'],
            ['name' => 'Dashboard', 'description' => 'Synthèse santé projet, alertes et activité', 'status' => 'draft'],
        ])->mapWithKeys(function ($data) use ($project, $user) {
            $module = Module::create([
                'project_id' => $project->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'owner_id' => $user->id,
                'status' => $data['status'],
            ]);
            return [$data['name'] => $module];
        });

        // Module dependencies
        $modules['Requirements']->dependencies()->attach($modules['Projects']->id, ['type' => 'depends_on']);
        $modules['Tests & V&V']->dependencies()->attach($modules['Requirements']->id, ['type' => 'depends_on']);
        $modules['Planning']->dependencies()->attach($modules['Requirements']->id, ['type' => 'depends_on']);
        $modules['Architecture']->dependencies()->attach($modules['Projects']->id, ['type' => 'depends_on']);
        $modules['ADR']->dependencies()->attach($modules['Projects']->id, ['type' => 'depends_on']);
        $modules['Dashboard']->dependencies()->attach($modules['Requirements']->id, ['type' => 'depends_on']);
        $modules['Dashboard']->dependencies()->attach($modules['Tests & V&V']->id, ['type' => 'depends_on']);

        // Requirements
        $reqs = collect([
            ['module' => 'Projects', 'title' => 'Créer et gérer des projets', 'description' => 'Un utilisateur peut créer un projet avec nom, description et slug unique.', 'acceptance_criteria' => "- Formulaire de création avec validation\n- Slug auto-généré\n- Liste des projets de l'utilisateur", 'priority' => 'P0', 'vv_status' => 'validated', 'risk_impact' => 4, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Projects', 'title' => 'Gestion des membres et rôles', 'description' => 'Chaque projet a des membres avec rôles (owner, member, viewer).', 'acceptance_criteria' => "- Inviter un membre par email\n- Assigner un rôle\n- Révoquer l'accès", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Requirements', 'title' => 'CRUD exigences avec ID auto', 'description' => 'Créer des exigences avec REF auto-générée (REQ-001), liées à un module.', 'acceptance_criteria' => "- ID REQ-XXX auto-généré\n- Titre, description, critères d'acceptation\n- Module rattaché obligatoire", 'priority' => 'P0', 'vv_status' => 'validated', 'risk_impact' => 5, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'Requirements', 'title' => 'Statut V&V automatique', 'description' => 'Le statut V&V est recalculé automatiquement depuis les résultats de tests.', 'acceptance_criteria' => "- Tous tests pass → vérifié\n- Un test fail → échoué\n- Validation manuelle par le client", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 5, 'risk_probability' => 3, 'risk_detectability' => 2],
            ['module' => 'Requirements', 'title' => 'Risk Score FMEA', 'description' => 'Chaque exigence a un score de risque calculé : Impact × Probabilité × (6 - Détectabilité).', 'acceptance_criteria' => "- 3 champs select 1-5\n- Score auto-calculé\n- Tri par risque dans le dashboard", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 3, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Tests & V&V', 'title' => 'Registre de tests liés aux exigences', 'description' => 'Chaque test a un ID (TEST-XXX), une procédure et des exigences couvertes.', 'acceptance_criteria' => "- ID TEST-XXX auto-généré\n- Lien many-to-many avec requirements\n- Type : manuel, automatisé, review", 'priority' => 'P0', 'vv_status' => 'verified', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'Tests & V&V', 'title' => 'Historique des exécutions de tests', 'description' => 'Chaque exécution de test enregistre : date, résultat, exécuteur, notes.', 'acceptance_criteria' => "- Résultat pass/fail/skip\n- Horodatage automatique\n- Historique consultable", 'priority' => 'P0', 'vv_status' => 'in_test', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Planning', 'title' => 'Tâches avec dépendances', 'description' => 'Créer des tâches liées à un module, avec dates et dépendances entre tâches.', 'acceptance_criteria' => "- Tâche liée à un module\n- Statut : À faire, En cours, Terminé, Bloqué\n- Dépendances entre tâches", 'priority' => 'P1', 'vv_status' => 'in_test', 'risk_impact' => 3, 'risk_probability' => 2, 'risk_detectability' => 2],
            ['module' => 'Planning', 'title' => 'Vue Gantt interactive', 'description' => 'Visualisation timeline des tâches avec dépendances, groupées par module.', 'acceptance_criteria' => "- Timeline avec frappe-gantt\n- Groupement par module\n- Flèches de dépendances", 'priority' => 'P2', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 3, 'risk_detectability' => 3],
            ['module' => 'Architecture', 'title' => 'Diagrammes Mermaid auto-générés', 'description' => 'Génération automatique du diagramme d\'architecture depuis les modules et dépendances.', 'acceptance_criteria' => "- Diagramme Mermaid rendu côté client\n- Blocs cliquables vers les modules\n- Édition du source Mermaid", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 2, 'risk_probability' => 2, 'risk_detectability' => 1],
            ['module' => 'ADR', 'title' => 'CRUD ADR avec statuts', 'description' => 'Créer des Architecture Decision Records avec statut (Proposé → Accepté → Déprécié).', 'acceptance_criteria' => "- ID ADR-XXX auto-généré\n- Template pré-rempli\n- Liens vers modules et requirements", 'priority' => 'P1', 'vv_status' => 'verified', 'risk_impact' => 2, 'risk_probability' => 1, 'risk_detectability' => 1],
            ['module' => 'Dashboard', 'title' => 'Dashboard santé projet', 'description' => 'Vue synthétique avec santé par module, couverture, alertes et activité récente.', 'acceptance_criteria' => "- Santé par module (4 axes)\n- Alertes : REQ sans tests, tests échoués\n- Activité récente", 'priority' => 'P0', 'vv_status' => 'untested', 'risk_impact' => 3, 'risk_probability' => 3, 'risk_detectability' => 2],
            ['module' => 'Dashboard', 'title' => 'Deploy Readiness Review', 'description' => 'Check GO/NO-GO automatique par module avant déploiement.', 'acceptance_criteria' => "- GO/NO-GO par module\n- Seuil configurable\n- Endpoint API pour la CI", 'priority' => 'P1', 'vv_status' => 'untested', 'risk_impact' => 4, 'risk_probability' => 2, 'risk_detectability' => 3],
        ])->map(function ($data) use ($project, $modules) {
            return Requirement::create([
                'project_id' => $project->id,
                'module_id' => $modules[$data['module']]->id,
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

        // Tests
        $tests = collect([
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
        ])->map(function ($data) use ($project, $reqs) {
            $test = Test::create([
                'project_id' => $project->id,
                'title' => $data['title'],
                'procedure' => $data['procedure'],
                'expected_result' => $data['expected_result'],
                'type' => $data['type'],
            ]);
            foreach ($data['reqs'] as $reqIndex) {
                $test->requirements()->attach($reqs[$reqIndex]->id);
            }
            return $test;
        });

        // Test executions
        $executions = [
            [0, 'pass'], [1, 'pass'], [2, 'pass'], [3, 'pass'],
            [4, 'pass'], [5, 'pass'], [6, 'fail'], [7, 'pass'],
            [8, 'pass'], [9, 'pass'],
        ];
        foreach ($executions as [$testIdx, $result]) {
            TestExecution::create([
                'test_id' => $tests[$testIdx]->id,
                'result' => $result,
                'executed_by' => $user->id,
                'executed_at' => now()->subDays(rand(1, 14)),
                'notes' => $result === 'fail' ? 'Régression détectée sur le calcul du résultat' : null,
            ]);
        }

        // Tasks
        $taskData = [
            ['module' => 'Projects', 'title' => 'Migrations et modèles Project/User', 'status' => 'done', 'progress' => 100, 'days_offset' => [-14, -7]],
            ['module' => 'Projects', 'title' => 'CRUD Projects + vues Blade', 'status' => 'done', 'progress' => 100, 'days_offset' => [-7, -3]],
            ['module' => 'Requirements', 'title' => 'Modèle Requirement + HasSequentialRef', 'status' => 'done', 'progress' => 100, 'days_offset' => [-10, -5]],
            ['module' => 'Requirements', 'title' => 'Livewire RequirementList avec filtres', 'status' => 'done', 'progress' => 100, 'days_offset' => [-5, -2]],
            ['module' => 'Requirements', 'title' => 'Vue détail Requirement + Risk Score', 'status' => 'in_progress', 'progress' => 70, 'days_offset' => [-2, 3]],
            ['module' => 'Tests & V&V', 'title' => 'Modèle Test + TestExecution', 'status' => 'done', 'progress' => 100, 'days_offset' => [-8, -4]],
            ['module' => 'Tests & V&V', 'title' => 'Matrice de traçabilité Livewire', 'status' => 'in_progress', 'progress' => 40, 'days_offset' => [-1, 5]],
            ['module' => 'Planning', 'title' => 'Modèle Task + dépendances', 'status' => 'done', 'progress' => 100, 'days_offset' => [-6, -3]],
            ['module' => 'Planning', 'title' => 'Vue Kanban Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [2, 8]],
            ['module' => 'Planning', 'title' => 'Intégration frappe-gantt', 'status' => 'todo', 'progress' => 0, 'days_offset' => [5, 12]],
            ['module' => 'Architecture', 'title' => 'Génération Mermaid depuis modules', 'status' => 'in_progress', 'progress' => 60, 'days_offset' => [-3, 4]],
            ['module' => 'Architecture', 'title' => 'Éditeur de diagramme Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [4, 10]],
            ['module' => 'ADR', 'title' => 'CRUD ADR + vues Blade', 'status' => 'done', 'progress' => 100, 'days_offset' => [-5, -1]],
            ['module' => 'Dashboard', 'title' => 'HealthWidget Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [3, 10]],
            ['module' => 'Dashboard', 'title' => 'AlertsWidget Livewire', 'status' => 'todo', 'progress' => 0, 'days_offset' => [5, 12]],
            ['module' => 'Dashboard', 'title' => 'Deploy Readiness endpoint API', 'status' => 'blocked', 'progress' => 0, 'days_offset' => [8, 15]],
        ];

        $tasks = collect($taskData)->map(function ($data) use ($project, $modules, $user) {
            return Task::create([
                'project_id' => $project->id,
                'module_id' => $modules[$data['module']]->id,
                'title' => $data['title'],
                'assignee_id' => $user->id,
                'status' => $data['status'],
                'progress' => $data['progress'],
                'start_date' => now()->addDays($data['days_offset'][0]),
                'end_date' => now()->addDays($data['days_offset'][1]),
            ]);
        });

        // Task dependencies
        $tasks[3]->blockedBy()->attach($tasks[2]->id); // RequirementList blocked by Requirement model
        $tasks[4]->blockedBy()->attach($tasks[3]->id); // Requirement detail blocked by list
        $tasks[6]->blockedBy()->attach($tasks[5]->id); // Traceability matrix blocked by Test model
        $tasks[9]->blockedBy()->attach($tasks[8]->id); // Gantt blocked by Kanban
        $tasks[11]->blockedBy()->attach($tasks[10]->id); // Diagram editor blocked by Mermaid gen
        $tasks[14]->blockedBy()->attach($tasks[13]->id); // Alerts blocked by Health
        $tasks[15]->blockedBy()->attach($tasks[14]->id); // Deploy readiness blocked by Alerts

        // ADRs
        $adrs = collect([
            ['title' => 'Blade + Livewire plutôt qu\'un SPA', 'context' => 'Un outil de gestion de projet est essentiellement composé de listes, tableaux, formulaires et modales.', 'decision' => 'Utiliser Blade Components + Livewire 3 pour toute l\'interface. Zéro framework JS frontend.', 'consequences' => 'Simplicité, performance SSR, moins de bugs frontend. Certaines interactions riches nécessiteront Alpine.js.', 'status' => 'accepted', 'modules' => ['Projects', 'Requirements', 'Dashboard']],
            ['title' => 'PostgreSQL pour le support JSONB', 'context' => 'Orbiter stocke des données semi-structurées (baselines, deploy readiness, tags).', 'decision' => 'Utiliser PostgreSQL 16 comme base de données principale.', 'consequences' => 'JSONB natif avec indexation GIN. Moins répandu que MySQL dans l\'écosystème Laravel.', 'status' => 'accepted', 'modules' => ['Projects']],
            ['title' => 'Mermaid.js pour les diagrammes', 'context' => 'Orbiter a besoin de visualiser l\'architecture des modules et leurs dépendances.', 'decision' => 'Utiliser Mermaid.js pour le rendu côté client. Source stocké en base et versionné.', 'consequences' => 'Rendu côté client, syntaxe texte versionnée. Limité pour les diagrammes très complexes.', 'status' => 'accepted', 'modules' => ['Architecture']],
            ['title' => 'Simplicité radicale — Zéro SPA', 'context' => 'Le navigateur moderne offre nativement : dialog, popover, details/summary, CSS transitions.', 'decision' => 'Exploiter au maximum les APIs natives du navigateur. Zéro framework JS frontend.', 'consequences' => 'Moins de dépendances, performance native. Certains patterns UI modernes sont plus verbeux.', 'status' => 'accepted', 'modules' => ['Projects', 'Requirements', 'Tests & V&V', 'Dashboard']],
        ])->map(function ($data) use ($project, $user, $modules) {
            $adr = Adr::create([
                'project_id' => $project->id,
                'title' => $data['title'],
                'context' => $data['context'],
                'decision' => $data['decision'],
                'consequences' => $data['consequences'],
                'status' => $data['status'],
                'author_id' => $user->id,
            ]);
            foreach ($data['modules'] as $moduleName) {
                $adr->modules()->attach($modules[$moduleName]->id);
            }
            return $adr;
        });

        // Diagram
        Diagram::create([
            'project_id' => $project->id,
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

        // Lessons Learned
        Lesson::create([
            'project_id' => $project->id,
            'title' => 'Le trait HasSequentialRef doit gérer la concurrence',
            'description' => 'Lors des tests de charge, deux requirements créées simultanément ont reçu le même ref REQ-005. Solution : utiliser un lock advisory PostgreSQL dans le trait.',
            'module_id' => $modules['Requirements']->id,
            'tags' => ['concurrence', 'postgresql', 'trait'],
            'author_id' => $user->id,
        ]);

        Lesson::create([
            'project_id' => $project->id,
            'title' => 'Les modales <dialog> gèrent le focus trap nativement',
            'description' => 'Pas besoin de JS pour le focus trap. Le <dialog> natif bloque le scroll, capture le focus et gère Escape. Énorme gain de simplicité vs une modale React/Vue.',
            'module_id' => $modules['Dashboard']->id,
            'tags' => ['frontend', 'dialog', 'simplicity'],
            'author_id' => $user->id,
        ]);

        Lesson::create([
            'project_id' => $project->id,
            'title' => 'Toujours eager-load les relations dans les listes',
            'description' => 'La page RequirementList faisait 200+ requêtes à cause du N+1 sur module et tests. Résolu avec ->with([\'module\', \'tests\']). Règle : toujours utiliser with() dans les listes.',
            'module_id' => $modules['Requirements']->id,
            'requirement_id' => $reqs[2]->id,
            'tags' => ['performance', 'eloquent', 'n+1'],
            'author_id' => $user->id,
        ]);

        // Baseline
        Baseline::create([
            'project_id' => $project->id,
            'ref' => 'v0.1.0',
            'title' => 'Baseline initiale — Bootstrap',
            'description' => 'Premier snapshot après le bootstrap du projet. Modèles, migrations et seeder en place.',
            'snapshot' => [
                'requirements_count' => $reqs->count(),
                'requirements_by_status' => $reqs->groupBy('vv_status')->map->count(),
                'tests_count' => $tests->count(),
                'modules_count' => $modules->count(),
                'adrs_count' => $adrs->count(),
            ],
            'signed_by' => 'Demo User',
            'is_immutable' => true,
        ]);

        // Anomaly example
        Anomaly::create([
            'project_id' => $project->id,
            'title' => 'Historique exécutions ne trie pas par date',
            'description' => 'Les exécutions de tests s\'affichent dans l\'ordre de création, pas par date d\'exécution. Impact faible mais confus pour l\'utilisateur.',
            'type' => 'anomaly',
            'module_id' => $modules['Tests & V&V']->id,
            'severity' => 'low',
            'status' => 'open',
            'assignee_id' => $user->id,
        ]);

        Anomaly::create([
            'project_id' => $project->id,
            'title' => 'Le statut V&V ne repasse pas à "échoué" après un test fail',
            'description' => 'Quand un test exécuté fail, le statut V&V de la REQ associée devrait repasser à "failed" automatiquement. Actuellement, il reste sur "verified".',
            'type' => 'non_conformity',
            'requirement_id' => $reqs[3]->id,
            'module_id' => $modules['Requirements']->id,
            'severity' => 'high',
            'status' => 'investigating',
            'assignee_id' => $user->id,
        ]);
    }
}
