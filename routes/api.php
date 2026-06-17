<?php

use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CatamaranController;
use App\Http\Controllers\Api\V1\CatamaranPhotoController;
use App\Http\Controllers\Api\V1\CatamaranRouteController;
use App\Http\Controllers\Api\V1\ExtraController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\OtherController;
use App\Http\Controllers\Api\V1\PersonalRequestController;
use App\Http\Controllers\Api\V1\SummaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::apiResource('catamarans', CatamaranController::class);

    Route::get('catamarans/{catamaran}/bookings', [BookingController::class, 'indexByCatamaran']);
    Route::post('catamarans/{catamaran}/photos', [CatamaranPhotoController::class, 'store']);
    Route::post('catamarans/{catamaran}/routes', [CatamaranRouteController::class, 'store']);

    Route::delete('photos/{photo}', [CatamaranPhotoController::class, 'destroy']);
    Route::put('routes/{route}', [CatamaranRouteController::class, 'update']);
    Route::delete('routes/{route}', [CatamaranRouteController::class, 'destroy']);

    Route::apiResource('bookings', BookingController::class);

    Route::post('bookings/{booking}/extras', [ExtraController::class, 'store']);

    Route::get('bookings/{booking}/personal-requests', [PersonalRequestController::class, 'index']);
    Route::post('bookings/{booking}/personal-requests', [PersonalRequestController::class, 'store']);
    Route::put('personal-requests/{personalRequest}', [PersonalRequestController::class, 'update']);
    Route::delete('personal-requests/{personalRequest}', [PersonalRequestController::class, 'destroy']);

    Route::get('personal-requests/{personalRequest}/others', [OtherController::class, 'index']);
    Route::post('personal-requests/{personalRequest}/others', [OtherController::class, 'store']);
    Route::put('others/{other}', [OtherController::class, 'update']);
    Route::delete('others/{other}', [OtherController::class, 'destroy']);

    Route::get('bookings/{booking}/guest', [GuestController::class, 'show']);
    Route::post('bookings/{booking}/guest', [GuestController::class, 'store']);
    Route::put('guests/{guest}', [GuestController::class, 'update']);
    Route::delete('guests/{guest}', [GuestController::class, 'destroy']);

    Route::get('bookings/{booking}/summary', [SummaryController::class, 'show']);
    Route::post('bookings/{booking}/summary', [SummaryController::class, 'store']);
    Route::put('summaries/{summary}', [SummaryController::class, 'update']);
    Route::delete('summaries/{summary}', [SummaryController::class, 'destroy']);
});
