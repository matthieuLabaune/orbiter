<?php

use App\Http\Controllers\Api\BaselineApiController;
use App\Http\Controllers\Api\ContextBriefController;
use App\Http\Controllers\Api\DeployReadinessController;
use App\Http\Controllers\Api\GitHubWebhookController;
use App\Http\Controllers\Api\HealthReportController;
use App\Http\Controllers\Api\TestResultController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects/{project}')->group(function () {
    // Read endpoints
    Route::get('deploy-readiness', [DeployReadinessController::class, 'show']);
    Route::get('requirements/{requirement}/context-brief', [ContextBriefController::class, 'show']);
    Route::get('health-report', [HealthReportController::class, 'show']);

    // Write endpoints
    Route::post('test-results', [TestResultController::class, 'store']);
    Route::post('baselines', [BaselineApiController::class, 'store']);
    Route::get('baselines/{baseline}/diff/{other}', [BaselineApiController::class, 'diff']);

    // GitHub webhook
    Route::post('webhooks/github', [GitHubWebhookController::class, 'handle']);
});
