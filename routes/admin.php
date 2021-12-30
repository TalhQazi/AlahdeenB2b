<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductDocumentsController as AdminProductDocumentsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SubscriptionOrderController as AdminSubscriptionOrderController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\WarehouseBookingController as AdminWarehouseBookingController;
use App\Http\Controllers\Admin\BookingAgreementTermsController as AdminBookingAgreementController;
use App\Http\Controllers\Admin\AdvertismentsController as AdminAdsController;
use App\Http\Controllers\Admin\DriversController;
use App\Http\Controllers\Admin\HomePageCategoryController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum', 'verified']], function () {

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('home');
        Route::get('edit/{category}', [CategoryController::class, 'edit'])->name('edit');
        Route::get('show/{category}', [CategoryController::class, 'show'])->name('show');
        Route::put('update/{category}', [CategoryController::class, 'update'])->name('update');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::delete('delete/{category}', [CategoryController::class, 'destroy'])->name('destroy');

        Route::get('display-order', [HomePageCategoryController::class, 'create'])->name('display-order');
        Route::post('display-order', [HomePageCategoryController::class, 'store'])->name('store-display-order');
        Route::get('get-categories',[CategoryController::class,'getCategories'])->name('suggested-categories');

        Route::get('deleted/show/{category_id}', [CategoryController::class, 'showDeleted'])->name('show-deleted');
        Route::get('restore/{category_id}', [CategoryController::class, 'restore'])->name('restore');
    });

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('home');
        Route::get('show/{product}', [AdminProductController::class, 'show'])->name('show');
        Route::get('edit/{product}', [AdminProductController::class, 'edit'])->name('edit');
        Route::get('deleted/show/{product_id}', [AdminProductController::class, 'showDeleted'])->name('show-deleted');
        Route::get('{product}/documents', [AdminProductDocumentsController::class, 'index'])->name('document-index');
    });

    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('home');
        Route::put('{user}/role', [AdminUserController::class, 'updateRole'])->name('role.update');
    });

    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::get('/', [AdminSubscriptionOrderController::class, 'index'])->name('home');
        Route::put('update-subscription/{subscription_order?}', [AdminSubscriptionOrderController::class, 'updatePaymentStatus'])->name('payment_status.update');
    });

    Route::group(['prefix' => 'testimonials', 'as' => 'testimonials.'], function () {
        Route::get('/', [AdminTestimonialController::class, 'index'])->name('home');
        Route::post('/', [AdminTestimonialController::class, 'store'])->name('store');
        Route::get('{testimonial}', [AdminTestimonialController::class, 'edit'])->name('edit');
        Route::put('{testimonial}', [AdminTestimonialController::class, 'update'])->name('update');
        Route::delete('{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('destroy');
        Route::patch('{testimonial_id}', [AdminTestimonialController::class, 'restore'])->name('restore');
    });

    Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.', 'middleware' => ['warehouse']], function () {
        Route::get('/', [AdminWarehouseController::class, 'index'])->name('home');
        Route::get('create', [AdminWarehouseController::class, 'create'])->name('create');
        Route::get('{warehouse}', [AdminWarehouseController::class, 'edit'])->name('edit');
        Route::patch('{warehouse}/approve', [AdminWarehouseController::class, 'approve'])->name('approve');
        Route::patch('{warehouse}/disapprove', [AdminWarehouseController::class, 'disapprove'])->name('disapprove');

        Route::delete('{warehouse}', [AdminWarehouseController::class, 'destroy'])->name('destroy');
        Route::patch('{warehouse_id}', [AdminWarehouseController::class, 'restore'])->name('restore');
    });

    Route::group(['prefix' => 'warehousebookings', 'as' => 'warehousebookings.', 'middleware' => ['warehouse']], function () {
        Route::get('/', [AdminWarehouseBookingController::class, 'index'])->name('home');
        Route::get('invoices', [AdminBookingAgreementController::class, 'index'])->name('invoices');
        Route::put('invoices/{booking_agreement_term}', [AdminBookingAgreementController::class, 'updateStatus'])->name('invoice.update-status');
        Route::get('invoice/{booking_agreement_term}', [AdminBookingAgreementController::class, 'view'])->name('view-booking-invoice');

        Route::get('{warehouse_booking}', [AdminWarehouseBookingController::class, 'view'])->name('view');
        Route::put('{warehouse_booking}', [AdminWarehouseBookingController::class, 'update'])->name('update');
        Route::patch('{warehouse_booking}', [AdminWarehouseBookingController::class, 'reject'])->name('reject');

        Route::get('{warehouse_booking}/agreement', [AdminBookingAgreementController::class, 'create'])->name('create-agreement');
        Route::post('{warehouse_booking}/agreement', [AdminBookingAgreementController::class, 'store'])->name('store-agreement');
    });


    Route::group(['prefix' => 'advertisments', 'as' => 'advertisments.'], function () {
        Route::get('/', [AdminAdsController::class, 'index'])->name('home');
        Route::get('/create', [AdminAdsController::class, 'create'])->name('create');
        Route::post('/', [AdminAdsController::class, 'store'])->name('store');
        Route::get('/{advertisment}/edit', [AdminAdsController::class, 'edit'])->name('edit');
        Route::put('/{advertisment}', [AdminAdsController::class, 'update'])->name('update');
        Route::delete('{advertisment}', [AdminAdsController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'logistics', 'as' => 'logistics.'], function () {
        Route::get('drivers', [DriversController::class, 'index'])->name('drivers.index');
        Route::get('driver/{driver}', [DriversController::class, 'show'])->name('driver.show');
        Route::put('driver/{driver}', [DriversController::class, 'update'])->name('driver.verify');

    });


});

Route::get('categories/get-categories',[CategoryController::class,'getCategories'])->name('suggested-categories');
