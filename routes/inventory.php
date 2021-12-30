<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\Inventory\InventoryController as InventoryController;
use App\Http\Controllers\Inventory\ProductDefinitionController;
use App\Http\Controllers\Inventory\ProductPricingController;
use App\Http\Controllers\Inventory\ProductsListController;
use App\Http\Controllers\Inventory\SalesController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\StockReportsController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RequestPurchaseReturnController;

Route::group(['prefix' => 'khata', 'as' => 'khata.', 'middleware' => ['auth:sanctum', 'check-user-type']], function () {
    // Route::get('/', [KhataController::class, 'index'])->name('dashboard');
    Route::get('client', [ClientController::class, 'index'])->name('client.home');
    Route::post('client', [ClientController::class, 'store'])->name('client.store');
    Route::delete('client/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::get('client/{client}/business', [ClientController::class, 'getClientBusinessDetails'])->name('client.business');

    Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.home');
    Route::get('invoice/create/{client?}', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('invoice/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoice.download');
    Route::get('invoice/{invoice}/send', [InvoiceController::class, 'sendPdf'])->name('invoice.send');
    Route::post('invoice', [InvoiceController::class, 'store'])->name('invoice.store');

    Route::get('purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase-order.home');
    Route::get('purchase-order/create', [PurchaseOrderController::class, 'create'])->name('purchase-order.create');
    Route::get('purchase-order/{purchase_order}/download', [PurchaseOrderController::class, 'downloadPdf'])->name('purchase-order.download');
    Route::post('purchase-order', [PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::get('purchase-order/{purchase_order}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-order.edit');
    Route::put('purchase-order/{purchase_order}', [PurchaseOrderController::class, 'update'])->name('purchase-order.update');
    Route::delete('purchase-order/{purchase_order}', [PurchaseOrderController::class, 'destroy'])->name('purchase-order.destroy');

    Route::get('challan', [ChallanController::class, 'index'])->name('challan.home');
    Route::get('challan/create', [ChallanController::class, 'create'])->name('challan.create');
    Route::post('challan', [ChallanController::class, 'store'])->name('challan.store');
    Route::get('challan/{challan}', [ChallanController::class, 'edit'])->name('challan.edit');
    Route::put('challan/{challan}', [ChallanController::class, 'update'])->name('challan.update');
    Route::get('challan/{challan}/download', [ChallanController::class, 'downloadPdf'])->name('challan.download');
    Route::delete('challan/{challan}', [ChallanController::class, 'destroy'])->name('challan.destroy');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.home');
    Route::get('inventory/definitions', [ProductDefinitionController::class, 'index'])->name('inventory.product.definitions.list');
    Route::get('inventory/product-definition', [ProductDefinitionController::class, 'create'])->name('inventory.product.definition');
    Route::post('inventory/product-definition', [ProductDefinitionController::class, 'store'])->name('inventory.product.definition.store');
    Route::get('inventory/product-definition/{product_definition}', [ProductDefinitionController::class, 'edit'])->name('inventory.product.definition.edit');
    Route::put('inventory/product-definition/{product_definition}', [ProductDefinitionController::class, 'update'])->name('inventory.product.definition.update');
    Route::delete('inventory/product-definition/{product_definition}', [ProductDefinitionController::class, 'destroy'])->name('inventory.product.definition.destroy');

    Route::get('inventory/pricing', [ProductPricingController::class, 'index'])->name('inventory.product.pricing.list');
    Route::get('inventory/product-pricing', [ProductPricingController::class, 'create'])->name('inventory.product.pricing.create');
    Route::get('inventory/generate_barcode/{product}', [ProductPricingController::class, 'generateBarCode'])->name('inventory.generate.barcode');
    Route::post('inventory/product-pricing', [ProductPricingController::class, 'store'])->name('inventory.product.pricing.store');
    Route::get('inventory/product-pricing/{product_pricing}', [ProductPricingController::class, 'edit'])->name('inventory.product.pricing.edit');
    Route::put('inventory/product-pricing/{product_pricing}', [ProductPricingController::class, 'update'])->name('inventory.product.pricing.update');
    Route::delete('inventory/product-pricing/{product_pricing}', [ProductPricingController::class, 'destroy'])->name('inventory.product.pricing.destroy');

    Route::get('inventory/stock', [StockController::class, 'index'])->name('inventory.product.stock');
    Route::post('inventory/stock', [StockController::class, 'store'])->name('inventory.product.stock.store');
    Route::get('inventory/stock/level-reports', [StockController::class, 'stockLevelReport'])->name('inventory.product.stock.level.reports');
    Route::get('inventory/stock/sales-records', [StockReportsController::class, 'salesRecords'])->name('inventory.product.stock.sales.records');
    Route::get('inventory/stock/stock-reports', [StockReportsController::class, 'stockReports'])->name('inventory.product.stock.stock.reports');
    Route::get('inventory/stock/sales-records', [StockController::class, 'salesRecords'])->name('inventory.product.stock.sales.records');

    Route::get('inventory/list', [ProductsListController::class, 'index'])->name('inventory.products.list');
    Route::get('inventory/sales', [SalesController::class, 'index'])->name('inventory.product.sales');

    Route::post('purchase-return', [RequestPurchaseReturnController::class, 'store'])->name('purchase_return.store');
    Route::get('purchase-return/{purchase_return}/{status}', [RequestPurchaseReturnController::class, 'changeStatus'])->name('purchase_return.change-status');
    Route::get('/buyer/purchase-return', [RequestPurchaseReturnController::class, 'view'])->name('purchase_return.view');

});
