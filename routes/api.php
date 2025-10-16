<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ShortLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return response()->json(['message' => 'API fonctionne !']);
});

// Routes publiques
Route::post('/shorten', [ShortLinkController::class, 'shorten']);
Route::get('/s/{code}', [ShortLinkController::class, 'redirect']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {


    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    Route::post('/logout', [AuthController::class, 'logout']);
    */


    Route::get('/modules', [ModuleController::class, 'index']);
    Route::post('/modules/{id}/activate', [ModuleController::class, 'activate']);
    Route::post('/modules/{id}/deactivate', [ModuleController::class, 'deactivate']);

    Route::get('/links', [ShortLinkController::class, 'index']);
    Route::delete('/links/{id}', [ShortLinkController::class, 'destroy']);
});