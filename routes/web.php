<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/catamarans', [PageController::class, 'catamarans'])->name('pages.catamarans');
Route::get('/bookings', [PageController::class, 'bookings'])->name('pages.bookings');
Route::get('/guests', [PageController::class, 'guests'])->name('pages.guests');
Route::get('/routes', [PageController::class, 'routes'])->name('pages.routes');
Route::get('/summaries', [PageController::class, 'summaries'])->name('pages.summaries');
Route::get('/settings', [PageController::class, 'settings'])->name('pages.settings');
