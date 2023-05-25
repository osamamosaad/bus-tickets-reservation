<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/reservations', [ReservationController::class, 'list']);

Route::get('/reservations/most-frequent-trip', [ReservationController::class, 'getMostFrequentTrip']);

Route::get('/reservations/{id}', [ReservationController::class, 'get']);

Route::post('/reservations', [ReservationController::class, 'create']);

Route::put('/reservations/{id}', [ReservationController::class, 'update']);

Route::delete('/reservations/{id}', [ReservationController::class, 'delete']);
