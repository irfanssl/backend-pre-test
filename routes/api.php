<?php

use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('users', [AuthController::class, 'register']); // register route
Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']); // login route
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout',[AuthController::class, 'logout']);
    });
});




/**
 * Url : /tasks
 */
Route::prefix('tasks')->name('tasks.')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::put('{task}', [TaskController::class, 'update'])->name('update');
        Route::patch('{task}/status', [TaskController::class, 'updateStatus'])->name('update-status');
        Route::patch('{task}/report', [TaskController::class, 'updateReport'])->name('update-report');
        Route::get('{task}', [TaskController::class, 'show'])->name('show');
    });
});