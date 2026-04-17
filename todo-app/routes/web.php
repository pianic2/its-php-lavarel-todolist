<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ListController;

Route::get('/', [ListController::class, 'index'])->name('home');

Route::resource('lists', ListController::class);

Route::scopeBindings()->group(function () {
    Route::resource('lists.tasks', TaskController::class);
    Route::patch('lists/{list}/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('lists.tasks.toggle');
});
