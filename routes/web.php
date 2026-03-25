<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestExecutionController;
use App\Http\Controllers\AdrController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard redirects to projects
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Project members
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::patch('/projects/{project}/members/{user}', [ProjectMemberController::class, 'update'])->name('projects.members.update');
    Route::delete('/projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');

    // Requirements
    Route::resource('projects.requirements', RequirementController::class);

    // Tests
    Route::resource('projects.tests', TestController::class);
    Route::post('/projects/{project}/tests/{test}/executions', [TestExecutionController::class, 'store'])
        ->name('projects.tests.executions.store');

    // ADR
    Route::resource('projects.adrs', AdrController::class);

    // Tasks
    Route::resource('projects.tasks', TaskController::class);

    // Diagrams
    Route::resource('projects.diagrams', DiagramController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
