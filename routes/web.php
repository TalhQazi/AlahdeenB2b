<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Common\AjaxController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductDocumentsController;
use App\Http\Controllers\UserProductInterestController;
use App\Http\Controllers\UserProductServiceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BusinessDetailController;
use App\Http\Controllers\AdditionalBusinessDetailController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BusinessContactDetailController;
use App\Http\Controllers\BusinessCertificationController;
use App\Http\Controllers\BusinessAwardController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatLabelController;
use App\Http\Controllers\LeadQuotationController;
use App\Http\Controllers\ProductBuyRequirementController;
use App\Http\Controllers\ChatReminderController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationRequestController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\BusinessDirectorController;
use App\Http\Controllers\CompanyPageBannerController;
use App\Http\Controllers\CompanyPageController;
use App\Http\Controllers\CompanyPageProductController;
use App\Http\Controllers\MatterSheetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\PromotionalProductController;
use App\Http\Controllers\Inventory\InventoryController as InventoryController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Controllers\Inventory\ProductDefinitionController;
use App\Http\Controllers\MatterSheetProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Front\DriverRegisterController;

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

Route::get('/symbolic-link', function () {
    Artisan::call('storage:link');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('route:clear');
    return 'CACHE CLEARED'; //Return anything
});

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['guest'])->group(function () {
    Route::post('forgot-password', [PasswordResetController::class, 'sendPasswordResetLink'])->name('password.reset.link');
    Route::post('reset-password', [PasswordResetController::class, 'store'])->name('password.update');

    Route::get('invite/{token}', [InviteController::class, 'accept'])->name('invite.accept');
    Route::post('invite', [InviteController::class, 'approve'])->name('invite.approve');
});



Route::middleware(['auth:sanctum', 'verified', 'check-user-type'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => '', 'as' => 'phone.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('verify-phone', [OtpController::class, 'index'])->name('verify.home');
    Route::post('verify-otp', [OtpController::class, 'verify'])->name('verify.otp');
    Route::post('resend-otp', [OtpController::class, 'resend'])->name('resend.otp');
    Route::post('update-phone-number', [OtpController::class, 'updatePhone'])->name('update.number');
});


Route::group(['prefix' => 'product', 'as' => 'product.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    Route::get('/home', [ProductController::class, 'index'])->name('home');
    Route::get('create', [ProductController::class, 'create'])->name('create');
    Route::post('store', [ProductController::class, 'store'])->name('store');
    Route::get('show/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('edit/{product}', [ProductController::class, 'edit'])->name('edit');
    Route::put('update/{product}', [ProductController::class, 'update'])->name('update');
    Route::get('set_main_image/{product}/{product_image}', [ProductController::class, 'setMainImage'])->name('set-main-image');
    Route::get('delete/product_image/{product}/{product_image}', [ProductController::class, 'deleteImage'])->name('delete-image');
    Route::get('delete/{product}', [ProductController::class, 'destroy'])->name('destroy');
    Route::get('restore/{product_id}', [ProductController::class, 'restore'])->name('restore');
    Route::get('deleted/show/{product_id}', [ProductController::class, 'showDeleted'])->name('show-deleted');

    Route::get('get_products',[ProductController::class,'getProducts'])->name('suggested-products');
    Route::get('get_seller_products',[ProductController::class,'getSellerProducts'])->name('seller-products');
    Route::get('{product}/documents', [ProductDocumentsController::class, 'index'])->name('document-index');
    Route::post('{product}/documents/store', [ProductDocumentsController::class, 'store'])->name('document-store');
    Route::get('{product}/documents/{product_document}/show', [ProductDocumentsController::class, 'show'])->name('document-show');
    Route::get('/{product}/documents/{product_document}/delete', [ProductDocumentsController::class, 'destroy'])->name('document-delete');

    Route::post('set-as-featured/{product}', [ProductController::class, 'setAsFeatured'])->name('set-as-featured');

});

Route::group(['prefix' => 'product-interest', 'as' => 'product.interest.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    Route::get('/', [UserProductInterestController::class, 'index'])->name('home');
    Route::post('store', [UserProductInterestController::class, 'store'])->name('store');
    Route::put('update/{user_product_interest}', [UserProductInterestController::class, 'update'])->name('update');
    Route::get('delete/{user_product_interest}', [UserProductInterestController::class, 'destroy'])->name('delete');
});

Route::group(['prefix' => 'product-services', 'as' => 'product.services.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    Route::get('/', [UserProductServiceController::class, 'index'])->name('home');
    Route::post('store', [UserProductServiceController::class, 'store'])->name('store');
    Route::post('update/{user_product_service}', [UserProductServiceController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'subscription', 'as' => 'subscription.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    Route::get('/subs-package', [SubscriptionController::class, 'index'])->name('home');
    Route::post('select', [SubscriptionController::class, 'selectPackage'])->name('selectPackage');
    Route::get('details', [SubscriptionController::class, 'show'])->name('details');
    Route::get('remove-cart-item/{rowId}', [SubscriptionController::class, 'removeCartItem'])->name('removeCartItem');
    Route::post('apply-coupon', [SubscriptionController::class, 'applyCoupon'])->name('applyCoupon');
    Route::post('proceed-payment', [SubscriptionController::class, 'proceedPayment'])->name('proceedPayment');
    Route::get('offline-payment-guide', [SubscriptionController::class, 'offlinePayment'])->name('offlinePayment');

    Route::get('/check-package', [SubscriptionController::class, 'checkPackageAvailability'])->name('checkPackageAvailability');
});

Route::group(['prefix' => 'profile/business', 'as' => 'profile.business.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/', [BusinessDetailController::class, 'create'])->name('home');
    Route::post('store', [BusinessDetailController::class, 'store'])->name('store');


    Route::get('additional-details', [AdditionalBusinessDetailController::class, 'create'])->name('additional-details');
    Route::post('additional-details/store', [AdditionalBusinessDetailController::class, 'store'])->name('additional.store');
    Route::post('add-contact-details/store', [BusinessContactDetailController::class, 'store'])->name('contact.store');
    Route::get('edit-contact-details/{business_contact_detail}', [BusinessContactDetailController::class, 'edit'])->name('contact.edit');
    Route::delete('delete-contact-details/{business_contact_detail}', [BusinessContactDetailController::class, 'destroy'])->name('contact.delete');

    Route::get('certifications-awards', [BusinessCertificationController::class, 'index'])->name('certifications-awards');
    Route::post('certification/store', [BusinessCertificationController::class, 'store'])->name('certification.store');
    Route::get('certification/edit/{business_certification}', [BusinessCertificationController::class, 'edit'])->name('certification.edit');
    Route::delete('delete-certification-details/{business_certification}', [BusinessCertificationController::class, 'destroy'])->name('certification.delete');

    Route::post('award/store', [BusinessAwardController::class, 'store'])->name('award.store');
    Route::get('award/edit/{business_award}', [BusinessAwardController::class, 'edit'])->name('award.edit');
    Route::delete('delete-award-details/{business_award}', [BusinessAwardController::class, 'destroy'])->name('award.delete');

    Route::get('director-profile', [BusinessDirectorController::class, 'create'])->name('create.director.profile');
    Route::post('director-profile', [BusinessDirectorController::class, 'store'])->name('store.director.profile');
    Route::put('director-profile/{business_director}', [BusinessDirectorController::class, 'update'])->name('update.director.profile');
    Route::delete('director-profile/{business_director}', [BusinessDirectorController::class, 'destroy'])->name('delete.director.profile');

    Route::get('company-settings', [CompanyPageController::class, 'index'])->name('company.page');
    Route::post('company-settings/banner', [CompanyPageBannerController::class, 'store'])->name('company.page.banner.store');
    Route::post('company-settings/products', [CompanyPageProductController::class, 'store'])->name('company.page.products.store');
});

Route::group(['prefix' => 'catalog', 'as' => 'catalog.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    Route::get('/', [CatalogController::class, 'index'])->name('home');
    Route::get('create', [CatalogController::class, 'create'])->name('create');
    Route::post('store', [CatalogController::class, 'store'])->name('store');
    Route::get('delete/{catalog}', [CatalogController::class, 'destroy'])->name('destroy');
});


Route::group(['prefix' => 'product-buy-requirement', 'as' => 'product-buy-requirement.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/', [ProductBuyRequirementController::class, 'index'])->name('home');
    Route::get('/{product_buy_requirement}', [ProductBuyRequirementController::class, 'show'])->name('get-quotation-request');
    Route::get('add-remove-lead-fav', [ProductBuyRequirementController::class, 'addRemoveshortlistLead'])->name('lead-fav');
});

Route::group(['prefix' => 'chat', 'as' => 'chat.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/contact_buyer/{product_buy_requirement}', [ChatController::class, 'createConversation'])->name('create');
    Route::get('/messages/{conversation_id?}', [ChatController::class, 'getConversations'])->name('messages');
    Route::post('/send_message/{conversation_id}', [ChatController::class, 'sendMessage'])->name('send-message');
    Route::get('/get_messages/{conversation_id}', [ChatController::class, 'getMessages'])->name('get-messages');
    Route::post('/save_notes/{conversation_id}', [ChatController::class, 'saveNotes'])->name('save-notes');

    Route::post('/send_catalog/{conversation_id}', [CatalogController::class, 'sendCatalog'])->name('send-catalog');

    Route::post('/save_labels/{conversation_id}', [ChatLabelController::class, 'saveLabels'])->name('save-labels');
    Route::get('/get_labels/{conversation_id}', [ChatLabelController::class, 'getLabels'])->name('get-labels');

    Route::get('/get_reminders/{conversation_id}', [ChatReminderController::class, 'getReminders'])->name('get-reminders');
    Route::post('/set_reminder/{conversation_id}', [ChatReminderController::class, 'setReminder'])->name('set-reminder');
    Route::post('/reminder_done/{chat_reminder}', [ChatReminderController::class, 'markReminderAsDone'])->name('reminder-done');

    Route::get('/get_conversations', [ChatController::class, 'getConversationIds'])->name('get-conversations-ids');
});


Route::group(['prefix' => 'lead-quotation', 'as' => 'lead-quotation.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/lead_quotation/{product_buy_requirement}', [LeadQuotationController::class, 'create'])->name('create');
    Route::post('/lead_quotation/{product_buy_requirement}', [LeadQuotationController::class, 'store'])->name('store');
});

Route::group(['prefix' => 'quotation', 'as' => 'quotation.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/', [QuotationController::class, 'index'])->name('index');
    Route::post('/', [QuotationController::class, 'createPdf'])->name('create-pdf');
    Route::post('/send', [QuotationController::class, 'storeAndSend'])->name('store');

    Route::post('/request', [QuotationRequestController::class, 'store'])->name('send-quotation-request');
    Route::get('/request/{quotation_request}', [QuotationRequestController::class, 'show'])->name('get-quotation-request');
});


Route::get('get_locations',[LocationController::class,'getLocations'])->name('suggested-locations');
Route::get('get_company', [BusinessDetailController::class, 'getCompany'])->name('get.company');

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('become-a-seller',[UserController::class,'create'])->name('become.a.seller');
  Route::get('become-a-buyer',[UserController::class,'create'])->name('become.a.buyer');
  Route::post('become-a-seller',[UserController::class,'store'])->name('become.a.seller.store');
});

Route::group(['prefix' => 'matter-sheet', 'as' => 'matter-sheet.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/', [MatterSheetController::class, 'home'])->name('home');
    Route::get('/additional-details', [MatterSheetController::class, 'additionalDetails'])->name('additionalDetails');
    Route::get('/director-profile', [MatterSheetController::class, 'directorProfile'])->name('directorProfile');
    Route::get('/certificate-and-awards', [MatterSheetController::class, 'certificateAndAwards'])->name('certificateAndAwards');
    Route::get('/upload-products', [MatterSheetController::class, 'productUpload'])->name('productUpload');
    Route::post('/store-matter-sheet-products', [MatterSheetController::class, 'storeProduct'])->name('matter_sheet_product.store');
    Route::post('/store-matter-sheet', [MatterSheetController::class, 'storeProductFile'])->name('matter_sheet_product.store_file');
    Route::get('/listings', [MatterSheetController::class, 'mattersheetListing'])->name('matter_sheet.listing');
    Route::get('/matter-sheet-approve/{matter_sheet}', [MatterSheetController::class, 'matterSheetApproval'])->name('matter_sheet.approve');
    Route::get('/delete/{matter_sheet}', [MatterSheetController::class, 'destroyMatterSheet'])->name('matter_sheet.destroy');

    Route::get('{matter_sheet_id}/products', [MatterSheetProductsController::class, 'index'])->name('matter_sheet_product.listing');
    Route::get('/product/{matter_sheet_product}', [MatterSheetProductsController::class, 'edit'])->name('matter_sheet_product.edit');
    Route::put('/product/{matter_sheet_product}', [MatterSheetProductsController::class, 'update'])->name('matter_sheet_product.update');

    Route::get('/company-settings', [MatterSheetController::class, 'companyPageSettings'])->name('company.setting.page');
});

Route::group(['prefix' => 'notification-subscriptions', 'as' => 'notification-subscriptions.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::get('/', [NotificationSubscriptionController::class, 'index'])->name('notifications.subscription.index');
    Route::post('/subscribe/{notification_type_id}', [NotificationSubscriptionController::class, 'subscribeNotification'])->name('notifications.subscription.subscribe');
});

Route::group(['prefix' => 'promotions', 'as' => 'promotions.', 'middleware' => ['auth:sanctum', 'verified', 'check-user-type']], function () {
    Route::post('/', [PromotionalProductController::class, 'store'])->name('product.store');
    Route::get('/{promotional_product}', [PromotionalProductController::class, 'show'])->name('product.show');
    Route::put('/{promotional_product}', [PromotionalProductController::class, 'update'])->name('product.update');
});
Route::get('categories/get-categories',[CategoryController::class,'getCategories'])->name('suggested-categories');









// kashif routes
Route::namespace('Common')->group(function() {

    Route::prefix('user-profile')->name('user.profile.')->group(function() {
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('update', [ProfileController::class, 'update'])->name('update');
    });

    // Common Ajax Routes
    Route::prefix('user')->name('ajax.user.')->group(function() {
        Route::get('get-sub-categories/{id?}', [AjaxController::class, 'getSubCategories'])->name('get.sub.categories');
    });

});
