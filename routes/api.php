<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participants\ParticipantController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\ProfitController;

Route::apiResource('participants', ParticipantController::class);

Route::apiResource('payments', PaymentController::class);

Route::get('profit/total', [ProfitController::class, 'getTotalProfit']);

Route::post('profit/total', [ProfitController::class, 'getTotalProfitByDate']);
