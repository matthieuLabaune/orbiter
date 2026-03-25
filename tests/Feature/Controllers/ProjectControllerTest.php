<?php

use App\Models\Project;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('lists user projects', function () {
    $project = Project::create(['name' => 'My Project']);
    $project->members()->attach($this->user->id, ['role' => 'owner']);

    $this->actingAs($this->user)
        ->get(route('projects.index'))
        ->assertOk()
        ->assertSee('My Project');
});

it('creates a project', function () {
    $this->actingAs($this->user)
        ->post(route('projects.store'), [
            'name' => 'New Project',
            'description' => 'A test project',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('projects', ['name' => 'New Project']);

    $project = Project::where('name', 'New Project')->first();
    expect($project->members()->where('user_id', $this->user->id)->exists())->toBeTrue();
    expect($project->slug)->toBe('new-project');
});

it('shows a project to members', function () {
    $project = Project::create(['name' => 'Visible Project']);
    $project->members()->attach($this->user->id, ['role' => 'member']);

    $this->actingAs($this->user)
        ->get(route('projects.show', $project))
        ->assertOk()
        ->assertSee('Visible Project');
});

it('denies access to non-members', function () {
    $project = Project::create(['name' => 'Private Project']);

    $this->actingAs($this->user)
        ->get(route('projects.show', $project))
        ->assertForbidden();
});

it('only owners can delete', function () {
    $project = Project::create(['name' => 'To Delete']);
    $project->members()->attach($this->user->id, ['role' => 'member']);

    $this->actingAs($this->user)
        ->delete(route('projects.destroy', $project))
        ->assertForbidden();
});
