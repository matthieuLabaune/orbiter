<?php

use App\Models\Module;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\Test;
use App\Models\TestExecution;
use App\Models\User;
use App\Services\VVStatusService;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->project = Project::create(['name' => 'Test Project']);
    $this->module = Module::create([
        'project_id' => $this->project->id,
        'name' => 'Test Module',
        'status' => 'active',
    ]);
    $this->service = new VVStatusService();
});

it('sets untested when no tests are linked', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'No tests',
        'vv_status' => 'in_test',
    ]);

    $this->service->recalculateForRequirement($req);

    expect($req->fresh()->vv_status)->toBe('untested');
});

it('sets verified when all tests pass', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'All pass',
    ]);

    $test = Test::create(['project_id' => $this->project->id, 'title' => 'Test 1', 'type' => 'automated']);
    $req->tests()->attach($test->id);

    TestExecution::create([
        'test_id' => $test->id,
        'result' => 'pass',
        'executed_by' => $this->user->id,
        'executed_at' => now(),
    ]);

    $this->service->recalculateForRequirement($req);

    expect($req->fresh()->vv_status)->toBe('verified');
});

it('sets failed when any test fails', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Has failure',
    ]);

    $test1 = Test::create(['project_id' => $this->project->id, 'title' => 'Pass test', 'type' => 'automated']);
    $test2 = Test::create(['project_id' => $this->project->id, 'title' => 'Fail test', 'type' => 'automated']);
    $req->tests()->attach([$test1->id, $test2->id]);

    TestExecution::create(['test_id' => $test1->id, 'result' => 'pass', 'executed_by' => $this->user->id, 'executed_at' => now()]);
    TestExecution::create(['test_id' => $test2->id, 'result' => 'fail', 'executed_by' => $this->user->id, 'executed_at' => now()]);

    $this->service->recalculateForRequirement($req);

    expect($req->fresh()->vv_status)->toBe('failed');
});

it('sets in_test when tests exist but not all executed', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Partial',
    ]);

    $test1 = Test::create(['project_id' => $this->project->id, 'title' => 'Executed', 'type' => 'automated']);
    $test2 = Test::create(['project_id' => $this->project->id, 'title' => 'Not executed', 'type' => 'automated']);
    $req->tests()->attach([$test1->id, $test2->id]);

    TestExecution::create(['test_id' => $test1->id, 'result' => 'pass', 'executed_by' => $this->user->id, 'executed_at' => now()]);

    $this->service->recalculateForRequirement($req);

    expect($req->fresh()->vv_status)->toBe('in_test');
});

it('does not downgrade validated to verified', function () {
    $req = Requirement::create([
        'project_id' => $this->project->id,
        'module_id' => $this->module->id,
        'title' => 'Validated',
        'vv_status' => 'validated',
    ]);

    $test = Test::create(['project_id' => $this->project->id, 'title' => 'Test', 'type' => 'automated']);
    $req->tests()->attach($test->id);
    TestExecution::create(['test_id' => $test->id, 'result' => 'pass', 'executed_by' => $this->user->id, 'executed_at' => now()]);

    $this->service->recalculateForRequirement($req);

    expect($req->fresh()->vv_status)->toBe('validated');
});
