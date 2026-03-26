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
        'name' => 'Core',
        'status' => 'active',
    ]);
});

it('lists requirements', function () {
    Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Test Requirement',
    ]);

    $this->actingAs($this->user)
        ->get(route('projects.requirements.index', $this->project))
        ->assertOk();
});

it('creates a requirement with auto ref', function () {
    $this->actingAs($this->user)
        ->post(route('projects.requirements.store', $this->project), [
            'title' => 'New Requirement',
            'module_id' => $this->module->id,
            'priority' => 'P1',
        ])
        ->assertRedirect();

    $req = Requirement::where('title', 'New Requirement')->first();
    expect($req)->not->toBeNull();
    expect($req->ref)->toBe('REQ-001');
    expect($req->vv_status)->toBe('untested');
});

it('creates version on update', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Original Title',
        'priority' => 'P2',
    ]);

    $this->actingAs($this->user)
        ->put(route('projects.requirements.update', [$this->project, $req]), [
            'title' => 'Updated Title',
            'module_id' => $this->module->id,
            'priority' => 'P1',
            'change_reason' => 'Clarification',
        ])
        ->assertRedirect();

    $req->refresh();
    expect($req->title)->toBe('Updated Title');
    expect($req->version)->toBe(2);
    expect($req->versions()->count())->toBe(1);
    expect($req->versions->first()->change_reason)->toBe('Clarification');
});
