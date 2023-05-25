<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/schedules', [ScheduleController::class, 'list']);

Route::get('/schedules/{id}', [ScheduleController::class, 'get']);
