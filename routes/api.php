<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Test route - no auth
Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
});

// Protected - using different syntax
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/assessments', [AssessmentController::class, 'index']);
    Route::post('/assessments', [AssessmentController::class, 'store']);
    Route::get('/assessments/{id}', [AssessmentController::class, 'show']);
    Route::post('/assessments/batch-sync', [AssessmentController::class, 'batchSync']);

    Route::post('/assessments/{id}/photos', [PhotoController::class, 'store']);
    Route::get('/export/csv', [ExportController::class, 'csv']);
});
// Serve single photo by ID
Route::get('/photos/{id}', function ($id) {
    $photo = \App\Models\Photo::findOrFail($id);

    if ($photo->base64_data) {
        return response()->json([
            'id'     => $photo->id,
            'base64' => $photo->base64_data,
        ]);
    }

    return response()->json(['message' => 'Photo not found'], 404);
});
