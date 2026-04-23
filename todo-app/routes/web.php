<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ListController;

Route::middleware('auth')->group(function () {
    Route::get('/', [ListController::class, 'index'])->name('home');

    Route::resource('lists', ListController::class);

    Route::post('lists/{list}/share', [ListController::class, 'share'])->name('lists.share');
    Route::delete('lists/{list}/share/{user}', [ListController::class, 'unshare'])->name('lists.unshare');

    Route::scopeBindings()->group(function () {
        Route::resource('lists.tasks', TaskController::class);
        Route::patch('lists/{list}/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('lists.tasks.toggle');
    });
});
