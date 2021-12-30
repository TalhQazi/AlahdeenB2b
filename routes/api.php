<?php

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\AdvertismentsController;
use App\Http\Controllers\Api\BusinessDetailsController;
use App\Http\Controllers\Api\BusinessTypeController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\HomePageCategoryController;
use App\Http\Controllers\Api\ProductBuyRequirementsController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\TestimonialsController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\Logistics\DriverFeedbackController;
use App\Http\Controllers\Api\Logistics\DriversController;
use App\Http\Controllers\Api\Logistics\DriversLocationController;
use App\Http\Controllers\Api\Logistics\VehiclesController;
use App\Http\Controllers\Api\PromotionalProductsController;
use App\Http\Controllers\Api\QuotationRequestController;
use App\Http\Controllers\Api\Warehouse\WarehouseController;
use App\Http\Controllers\Api\Warehouse\WarehouseBookingController;
use App\Http\Controllers\GeneralStatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$guest = [];
$authenticated = ['auth:sanctum', 'verified-phone'];
if (config('app.debug')) {
    array_push($guest, 'measure-time');
    array_push($authenticated, 'measure-time');
}

Route::group(['middleware' => $guest], function () {
    Route::post('/token', AuthController::class);

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetLink']);
    Route::post('/reset-password', [PasswordResetController::class, 'store']);

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', [CategoryController::class, 'search']);
        Route::get('/trending', [CategoryController::class, 'trending']);
        Route::get('{id}/products', [CategoryController::class, 'getCategoryProducts']);
        Route::get('/{category}', [CategoryController::class, 'show']);
    });

    Route::group(['prefix' => 'home_page_category', 'as' => 'home_page_category.'], function () {
        Route::get('/', [HomePageCategoryController::class, 'index']);
    });

    Route::group(['prefix' => 'advertisments', 'as' => 'advertisments.'], function () {
        Route::get('/{limit?}', [AdvertismentsController::class, 'index'])->name('list');
    });

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [ProductsController::class, 'search']);
        Route::get('/trending', [ProductsController::class, 'trending']);
        Route::get('/{product}', [ProductsController::class, 'show']);
        Route::get('/related/{product}', [ProductsController::class, 'relatedProducts']);
    });

    Route::group(['prefix' => 'cities', 'as' => 'cities.'], function () {
        Route::get('/', [CityController::class, 'index']);
    });

    Route::group(['prefix' => 'configurations', 'as' => 'configurations.'], function () {
        Route::get('/', [ConfigurationController::class, 'index']);
    });

    Route::group(['prefix' => 'testimonials', 'as' => 'testimonials.'], function () {
        Route::get('/', [TestimonialsController::class, 'index']);
    });

    Route::group(['prefix' => 'product_buy_requirements', 'as' => 'products_buy_requirements.'], function () {
        Route::get('/latest_requests', [ProductBuyRequirementsController::class, 'lastestRequests']);
    });

    Route::post('contact-us', [ContactUsController::class, 'store']);

    Route::group(['prefix' => 'company', 'as' => 'company.'], function () {
        Route::get('/', [BusinessDetailsController::class, 'index']);
        Route::get('/{id}', [BusinessDetailsController::class, 'show']);
    });

    Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.'], function () {
        Route::get('/', [WarehouseController::class, 'index']);
        Route::get('/popular', [WarehouseController::class, 'popularWarehouses']);
        Route::get('/{warehouse}', [WarehouseController::class, 'show']);
        Route::get('/related/{warehouse}', [WarehouseController::class, 'relatedWarehouses']);
    });

    Route::group(['prefix' => 'business-types', 'as' => 'business-types.'], function () {
        Route::get('/', [BusinessTypeController::class, 'index']);
    });

    Route::group(['prefix' => 'stats', 'as' => 'stats.'], function () {
        Route::get('/', [GeneralStatController::class, 'index']);
    });

    Route::group(['prefix' => 'promotions', 'as' => 'promotions.'], function () {
        Route::get('/', [PromotionalProductsController::class, 'index']);
    });

});

Route::group(['middleware' => $authenticated], function () {
    Route::group(['prefix' => 'product_buy_requirements', 'as' => 'products_buy_requirements.'], function () {
        Route::get('/', [ProductBuyRequirementsController::class, 'index']);
        Route::post('/', [ProductBuyRequirementsController::class, 'store']);
    });

    Route::group(['prefix' => 'files', 'as' => 'files.'], function () {
        Route::post('/image', [FileController::class, 'storeImage']);
        Route::post('/document', [FileController::class, 'storeDocument']);
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/chat-notifications', [UserController::class, 'chatNotifications']);
    });


    Route::group(['prefix' => 'contact-supplier', 'as' => 'contact-supplier.'], function () {
        Route::post('/', [QuotationRequestController::class, 'store']);
    });

    Route::group(['prefix' => 'warehouse-booking', 'as' => 'warehouse-booking.'], function () {
        Route::post('/{warehouse}', [WarehouseBookingController::class, 'store']);
    });

    Route::group(['prefix' => 'logistics', 'as' => 'logistics.'], function () {
        Route::get('driver/{driver}', [DriversController::class, 'show']);
        Route::post('driver', [DriversController::class, 'store']);
        Route::put('driver/{driver}', [DriversController::class, 'update']);
        Route::get('driver/{driver}/location', [DriversLocationController::class, 'show']);
        Route::post('driver/location', [DriversLocationController::class, 'store']);

        Route::get('vehicles', [VehiclesController::class, 'index']);

        Route::post('shipment-feedback', [DriverFeedbackController::class, 'store']);
    });
});

Route::fallback(function () {
    return response()->json([
        'status' => 'Error',
        'message' => 'Resource Not Found. If error persist please contact our support team.',
        'data' => null
    ], 404);
});
