<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class);
    Route::resource('events', EventController::class);

    Route::prefix('events')->group(function () {
        Route::get('/calendar', [EventController::class, 'calendar'])->name('events.calendar');
        Route::get('/calendar/events', [EventController::class, 'calendarEvents'])->name('events.calendar.events');
        Route::put('/{event}/update-date', [EventController::class, 'updateEventDate'])->name('events.update-date');
        Route::post('/quick-store', [EventController::class, 'quickStore'])->name('events.quick-store');
    });
});


require __DIR__ . '/auth.php';
