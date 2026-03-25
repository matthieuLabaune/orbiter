<?php

use App\Models\Module;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->project = Project::create(['name' => 'Test Project']);
    $this->project->members()->attach($this->user->id, ['role' => 'owner']);
    $this->module = Module::create([
        'project_id' => $this->project->id,
        'name' => 'Test Module',
        'status' => 'active',
    ]);
});

it('auto-generates sequential refs', function () {
    $req1 = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'First requirement',
    ]);

    $req2 = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Second requirement',
    ]);

    expect($req1->ref)->toBe('REQ-001');
    expect($req2->ref)->toBe('REQ-002');
});

it('computes risk score correctly', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Risky requirement',
        'risk_impact' => 4,
        'risk_probability' => 3,
        'risk_detectability' => 2,
    ]);

    // Score = 4 × 3 × (6 - 2) = 48
    expect($req->risk_score)->toBe(48);
});

it('returns null risk score when fields are missing', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'No risk data',
        'risk_impact' => 4,
    ]);

    expect($req->risk_score)->toBeNull();
});

it('belongs to module and project', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Test requirement',
    ]);

    expect($req->project->id)->toBe($this->project->id);
    expect($req->module->id)->toBe($this->module->id);
});
