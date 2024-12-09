<?php

use App\Http\Controllers\CounterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [UserController::class, 'profile']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/statistics', function () {
    return Inertia::render('Statistics');
})->middleware(['auth', 'verified'])->name('statistics');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/manage-counters', [CounterController::class, 'getCountersForManageCounters'])->name('manage-counters');
    Route::get('/CounterForm/{id?}', [CounterController::class, 'counterForm'])->name('counter-form');

    Route::post('/storeKid', [CounterController::class, 'storeKid'])->name('storeKid');
    Route::put('/updateKid/{id}', [CounterController::class, 'updateKid'])->name('updateKid');
    Route::post('/storePregnancy', [CounterController::class, 'storePregnancy'])->name('storePregnancy');
    Route::put('/updatePregnancy/{id}', [CounterController::class, 'updatePregnancy'])->name('updatePregnancy');
    Route::delete('/counter/{counterId}', [CounterController::class, 'deleteCounter'])->name('counter.delete');

    Route::get('/stats/aggregated', [StatsController::class, 'getAggregatedStats']);
    Route::get('/stats/kidsByAge', [StatsController::class, 'getKidsByAgeStats']);
    Route::get('/stats/pregnancyByMonth', [StatsController::class, 'getPregnancyByMonth']);

    Route::get('/getCounters', [UserController::class, 'getCounters'])->name('getCounters');
});

require __DIR__.'/auth.php';
