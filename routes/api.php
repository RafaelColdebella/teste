<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participants\ParticipantController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Species\SpecieController;

Route::put('participants/{participant}/balance', [ParticipantController::class, 'updateBalance']);
Route::get('participants/{participant}/balance', [ParticipantController::class, 'getBalance']);
Route::get('participants/receivers', [ParticipantController::class, 'getAllReceivers']);
Route::get('participants/payers', [ParticipantController::class, 'getAllPayers']);
Route::apiResource('participants', ParticipantController::class);

Route::apiResource('species', SpecieController::class);

Route::put('payments/{payment}/finish', [PaymentController::class, 'finish']);
Route::get('payments/total', [PaymentController::class, 'getTotals']);
Route::apiResource('payments', PaymentController::class);
