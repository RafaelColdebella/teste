<?php

use App\Http\Controllers\Central\Applications\ApplicationController;
use App\Http\Controllers\Central\Auth\AuthController;
use App\Http\Controllers\Central\Users\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Central\Companies\CompanyAddressController;
use App\Http\Controllers\Central\Companies\CompanyApplicationController;
use App\Http\Controllers\Central\Companies\CompanyConfigurationController;
use App\Http\Controllers\Central\Companies\CompanyContactController;
use App\Http\Controllers\Central\Companies\CompanyController;
use App\Http\Controllers\Central\Companies\CompanyUserController;
use App\Http\Controllers\Central\Payments\PaymentController;
use App\Http\Controllers\Central\ProductClassifications\ProductClassificationController;
use App\Http\Middleware\KeycloakAuthentication;
use App\Services\Central\Payments\PaymentService;

Route::prefix('')->group(function () {
    return 'olá';
});