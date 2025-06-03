<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MembershipPackageController;
use App\Http\Controllers\NotificationStatusController;
use App\Http\Controllers\NotificationTypeController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionServiceController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard.index');
});

Route::resource('customers', CustomerController::class);
Route::resource('membership-packages', MembershipPackageController::class);
Route::resource('notification-statuses', NotificationStatusController::class);
Route::resource('notification-types', NotificationTypeController::class);
Route::resource('outlets', OutletController::class);
Route::resource('payment-methods', PaymentMethodController::class);
Route::resource('payment-statuses', PaymentStatusController::class);
Route::resource('roles', RoleController::class);
Route::resource('services', ServiceController::class);
Route::resource('service-types', ServiceTypeController::class);
Route::resource('shift', ShiftController::class);
Route::resource('staff', StaffController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('transaction-services', TransactionServiceController::class);
Route::resource('vehicle-types', VehicleTypeController::class);

