<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $logDates = $user->logs()->pluck('log_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
    return view('dashboard', ['existingLogDates' => $logDates]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
    return view('profile-edit');
})->middleware(['auth'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/defaults', [ProfileController::class, 'updateDefaults'])->name('profile.defaults');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('/logs', [LogController::class, 'store'])->name('logs.store');
    Route::get('/logs/{id}', [LogController::class, 'show'])->name('logs.show');
    Route::get('/logs/{date}/edit', [LogController::class, 'edit'])->name('logs.edit');
    Route::put('/logs/{date}', [LogController::class, 'update'])->name('logs.update');
    Route::get('/logs/print/{date}', [LogController::class, 'printByDate'])->name('logs.printByDate');
    Route::post('/ai/generate', [AIController::class, 'generate'])->name('ai.generate');
    Route::post('/ai/generate-field', [AIController::class, 'generateField'])->name('ai.generate-field');
    Route::get('/print', [LogController::class, 'print'])->middleware(['auth'])->name('print');
});

require __DIR__.'/auth.php';