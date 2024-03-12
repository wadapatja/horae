<?php

use App\Http\Controllers\web\ChatGPTController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\PlantController;
use App\Http\Controllers\web\register\AccountController;
use App\Http\Controllers\web\GardenController;
use App\Http\Controllers\web\register\GardenController AS RegisterGardenController;
use App\Http\Controllers\web\TaskController;
use App\Livewire\TaskCalendar;
use App\Models\Plant;
use App\Models\Task;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator AS BreadcrumbsTrail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/* DASHBOARD */
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

/* REGISTER */
Route::get('/register/account', [AccountController::class, 'index'])->name('register.account');
Route::get('/register/garden', [RegisterGardenController::class, 'index'])->name('register.garden');

/* PLANTS */
Route::get('/plants', [PlantController::class, 'index'])->name('plant.index');
Route::get('/plants/create', [PlantController::class, 'new'])->name('plant.create');
Route::post('/plants', [PlantController::class, 'store'])->name('plant.store');
Route::get('/plants/{plant}/edit', [PlantController::class, 'edit'])->name('plant.edit');
Route::patch('/plants/{plant}', [PlantController::class, 'update'])->name('plant.update');
Route::delete('/plants/{plant}', [PlantController::class, 'delete'])->name('plant.delete');

/* TASKS */
Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
Route::get('/tasks/create', [TaskController::class, 'new'])->name('task.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/tasks/{task}', [TaskController::class, 'delete'])->name('task.delete');

/* GARDEN */
Route::get('/garden', [GardenController::class, 'index'])->name('garden');;

/* Livewire */
Route::get('/tasks/calendar', [TaskCalendar::class, 'render'])->name('task.livewire.index');

/* ChatGPT */
Route::get('/chatGPT/autocomplete/{characters}', [ChatGPTController::class, 'autocomplete'])->name('chatGPT.autocomplete');
Route::get('/chatGPT/getTasks/{plant_name}', [ChatGPTController::class, 'getTasks'])->name('chatGPT.getTasks');


/* Breadcrumbs */
Breadcrumbs::for('dashboard', function(BreadcrumbsTrail $trail): void {
    $trail->push('Dashboard', route('dashboard'));
});
Breadcrumbs::for('register.account', function(BreadcrumbsTrail $trail): void {
    $trail->push('Register account', route('register.account'));
});
Breadcrumbs::for('register.garden', function(BreadcrumbsTrail $trail): void {
    $trail->push('Register garden', route('register.garden'));
});
Breadcrumbs::for('plant.index', function(BreadcrumbsTrail $trail): void {
    $trail->push('Plants', route('plant.index'));
});
Breadcrumbs::for('plant.create', function(BreadcrumbsTrail $trail): void {
    $trail->parent('plant.index');
    $trail->push('New', route('plant.create'));
});
Breadcrumbs::for('plant.edit', function(BreadcrumbsTrail $trail, Plant $plant): void {
    $trail->parent('plant.index');
    $trail->push($plant->name, route('plant.edit', $plant));
});
Breadcrumbs::for('task.index', function(BreadcrumbsTrail $trail): void {
    $trail->push('Tasks', route('task.index'));
});
Breadcrumbs::for('task.create', function(BreadcrumbsTrail $trail): void {
    $trail->parent('task.index');
    $trail->push('New', route('task.create'));
});
Breadcrumbs::for('task.edit', function(BreadcrumbsTrail $trail, Task $task): void {
    $trail->parent('task.index');
    $trail->push($task->type, route('task.edit', $task));
});
Breadcrumbs::for('garden', function(BreadcrumbsTrail $trail): void {
    $trail->push('Map', route('garden'));
});
