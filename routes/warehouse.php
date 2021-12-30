<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Warehouse\WarehouseController;
use App\Http\Controllers\Warehouse\WarehouseBookingController;
use App\Http\Controllers\Warehouse\BookingAgreementTermsController;




/*
|--------------------------------------------------------------------------
| Warehouse Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.', 'middleware' => ['auth:sanctum', 'verified', 'warehouse']], function () {


    Route::get('/home', [WarehouseController::class, 'index'])->name('home');
    Route::get('create', [WarehouseController::class, 'create'])->name('create');
    Route::post('/store', [WarehouseController::class, 'store'])->name('store');
    Route::get('{warehouse}', [WarehouseController::class, 'edit'])->name('edit');
    Route::put('{warehouse}', [WarehouseController::class, 'update'])->name('update');

    Route::patch('{warehouse}/deactivate', [WarehouseController::class, 'deactivate'])->name('deactivate');
    Route::patch('{warehouse}/activate', [WarehouseController::class, 'activate'])->name('activate');

    Route::get('warehouse/{warehouse}/warehouse_image/{warehouse_image}', [WarehouseController::class, 'setMainImage'])->name('set-main-image');

    Route::get('{warehouse}/schedule', [WarehouseBookingController::class, 'show'])->name('view-schedule');
    Route::get('{warehouse}/get_month_bookings', [WarehouseBookingController::class, 'getWarehouseBookings'])->name('bookings');
    Route::post('{warehouse}/schedule', [WarehouseBookingController::class, 'store'])->name('schedule.store');
    // Route::post('{warehouse}/update/{warehouse_booking}', [WarehouseBookingController::class, 'update'])->name('schedule.update');
    Route::delete('{warehouse}/delete/{warehouse_booking}', [WarehouseBookingController::class, 'destroy'])->name('schedule.delete');

});

Route::group(['prefix' => 'warehousebookings', 'as' => 'warehousebookings.', 'middleware' => ['auth:sanctum', 'verified', 'warehouse']], function () {

    Route::get('/', [WarehouseBookingController::class, 'index'])->name('index');
    Route::get('/invoices', [BookingAgreementTermsController::class, 'index'])->name('invoices');
    Route::get('/{warehouse_booking}', [WarehouseBookingController::class, 'edit'])->name('edit');
    Route::put('/{warehouse_booking}', [WarehouseBookingController::class, 'update'])->name('update');

    /* These two routes might be required in case we need terms to be agreed upon by both requestor and admin */
    Route::get('invoice/{booking_agreement_term}', [BookingAgreementTermsController::class, 'view'])->name('view-invoice');
    Route::get('invoice/payment/{booking_agreement_term}', [BookingAgreementTermsController::class, 'makePayment'])->name('invoice-payment');
    Route::post('invoice/{booking_agreement_term}', [BookingAgreementTermsController::class, 'createPayment'])->name('create-payment');

    // Route::get('invoice/{booking_agreement_term}', [BookingAgreementTermsController::class, 'createPayment'])->name('create-payment');
    Route::get('invoice/details/{booking_agreement_term}', [BookingAgreementTermsController::class, 'viewDetails'])->name('invoice-details');
    Route::post('invoice/proceed-payment/{booking_agreement_term}', [BookingAgreementTermsController::class, 'proceedPayment'])->name('invoice-proceedPayment');
    Route::get('invoice/offline-payment/{booking_agreement_term}', [BookingAgreementTermsController::class, 'offlinePayment'])->name('invoice-offline-payment');

});




