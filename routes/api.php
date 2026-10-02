<?php

use App\Http\Controllers\CalculationController;
use App\Http\Controllers\ChatAssistantController;
use App\Http\Controllers\IdeaCheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// AI Assistant Chat Proxy
Route::post('/chat', [ChatAssistantController::class, 'chat']);
Route::get('/chat/history', [ChatAssistantController::class, 'history']);

// Idea Check Evaluation Routes
Route::get('/idea-checks', [IdeaCheckController::class, 'index']);
Route::post('/idea-checks', [IdeaCheckController::class, 'store']);

// Calculation Log Routes
Route::get('/calculations', [CalculationController::class, 'index']);
Route::post('/calculations', [CalculationController::class, 'store']);
