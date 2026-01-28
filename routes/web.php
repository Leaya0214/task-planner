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

    // Event calendar routes (must be before resource routes)
    Route::get('events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('events/calendar/events', [EventController::class, 'calendarEvents'])->name('events.calendar.events');
    Route::put('events/{event}/update-date', [EventController::class, 'updateEventDate'])->name('events.update-date');
    Route::post('events/quick-store', [EventController::class, 'quickStore'])->name('events.quick-store');

    Route::resource('events', EventController::class);
});


require __DIR__ . '/auth.php';
