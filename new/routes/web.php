<?php

use App\Http\Controllers\ContributeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
Route::get('/contribute', [ContributeController::class, 'create'])->name('contribute');
Route::post('/contribute', [ContributeController::class, 'store'])->name('contribute.store');
