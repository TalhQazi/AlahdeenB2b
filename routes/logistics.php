<?php

use App\Http\Controllers\Logistics\AvailableRidesController;
use App\Http\Controllers\Logistics\BookingConsignmentController;
use App\Http\Controllers\Logistics\BookingRequestController;
use App\Http\Controllers\Logistics\DriversController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'logistics', 'as' => 'logistics.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('booking-request', [BookingRequestController::class, 'index'])->name('booking_request.index');
    Route::get('booking-request/create', [BookingRequestController::class, 'create'])->name('booking_request.create');
    Route::get('booking-request/{booking_request}', [BookingRequestController::class, 'edit'])->name('booking_request.edit');
    Route::post('booking-request', [BookingRequestController::class, 'store'])->name('booking_request.store');
    Route::put('booking-request/{booking_request}', [BookingRequestController::class, 'update'])->name('booking_request.update');
    Route::get('booking-request/destroy/{booking_request}', [BookingRequestController::class, 'destroy'])->name('booking_request.destroy');

    Route::get('driver/available-rides', [AvailableRidesController::class, 'index'])->name('drivers.available_rides');

    Route::get('drivers/about', [DriversController::class, 'create'])->name('drivers.about');
    Route::post('drivers', [DriversController::class, 'store'])->name('drivers.store');
    Route::put('drivers/{driver}', [DriversController::class, 'update'])->name('drivers.update');
    Route::get('avaiable_ride/accept/{booking_request}', [AvailableRidesController::class, 'accept'])->name('avaiable_ride.accept');
    Route::get('avaiable_ride/start_ride/{booking_request}', [AvailableRidesController::class, 'startRide'])->name('avaiable_ride.start_ride');
    Route::get('avaiable_ride/end_ride/{booking_request}', [AvailableRidesController::class, 'endRide'])->name('avaiable_ride.end_ride');


});
