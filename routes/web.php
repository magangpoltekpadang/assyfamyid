<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MembershipPackageController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard.index');
});

// Route::get('/customers', [CustomerController::class, 'index']);

Route::resource('customers', CustomerController::class);
Route::resource('membership-packages', MembershipPackageController::class);
Route::resource('service-types', ServiceTypeController::class);
Route::resource('vehicle-types', VehicleTypeController::class);

