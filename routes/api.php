<?php

use App\Http\Controllers\Api\ContextBriefController;
use App\Http\Controllers\Api\DeployReadinessController;
use App\Http\Controllers\Api\HealthReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects/{project}')->group(function () {
    Route::get('deploy-readiness', [DeployReadinessController::class, 'show']);
    Route::get('requirements/{requirement}/context-brief', [ContextBriefController::class, 'show']);
    Route::get('health-report', [HealthReportController::class, 'show']);
});
