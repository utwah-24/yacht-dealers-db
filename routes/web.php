<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\CatamaranController;
use App\Http\Controllers\Web\CatamaranRouteController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/catamarans', [PageController::class, 'catamarans'])->name('pages.catamarans');
Route::post('/catamarans', [CatamaranController::class, 'store'])->name('pages.catamarans.store');
Route::delete('/catamarans/bulk', [CatamaranController::class, 'bulkDestroy'])->name('pages.catamarans.bulk-destroy');
Route::put('/catamarans/{catamaran}', [CatamaranController::class, 'update'])->name('pages.catamarans.update');
Route::delete('/catamarans/{catamaran}', [CatamaranController::class, 'destroy'])->name('pages.catamarans.destroy');
Route::post('/catamarans/{catamaran}/photos', [CatamaranController::class, 'storePhoto'])->name('pages.catamarans.photos.store');
Route::get('/bookings', [PageController::class, 'bookings'])->name('pages.bookings');
Route::get('/guests', [PageController::class, 'guests'])->name('pages.guests');
Route::get('/routes', [PageController::class, 'routes'])->name('pages.routes');
Route::post('/routes', [CatamaranRouteController::class, 'store'])->name('pages.routes.store');
Route::put('/routes/{route}', [CatamaranRouteController::class, 'update'])->name('pages.routes.update');
Route::delete('/routes/{route}', [CatamaranRouteController::class, 'destroy'])->name('pages.routes.destroy');
Route::get('/summaries', [PageController::class, 'summaries'])->name('pages.summaries');
Route::get('/settings', [PageController::class, 'settings'])->name('pages.settings');
