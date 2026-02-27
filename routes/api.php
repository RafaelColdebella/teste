<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participants\ParticipantController;

Route::apiResource('participants', ParticipantController::class);