<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StockEntryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\POS\PosController;
use App\Http\Controllers\POS\PosInvoiceController;


require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {

    //admin
    Route::resource('users', UserController::class);

    //customer
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy'); // Delete customer

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::get('products/barcodes/print', [\App\Http\Controllers\Admin\ProductController::class, 'printBarcodes'])->name('products.barcodes.print');

    Route::resource('stock-entries', StockEntryController::class)->except(['edit', 'update', 'destroy']);

    Route::resource('suppliers', SupplierController::class);
    Route::get('/stock-entries/{stock_entry}', [StockEntryController::class, 'show'])
        ->name('stock-entries.show');

    // Stock Adjustments
    Route::get('stock-adjustments/create', [StockAdjustmentController::class, 'create'])
        ->name('stock-adjustments.create');

    Route::post('stock-adjustments', [StockAdjustmentController::class, 'store'])
        ->name('stock-adjustments.store');

    Route::get('stock-movements', [StockMovementController::class, 'index'])
        ->name('stock-movements.index');

    Route::get('reports/low-stock', [ReportController::class, 'lowStock'])
        ->name('reports.low-stock');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales-daily', [ReportController::class, 'dailySales'])->name('sales.daily');
        Route::get('/sales-summary', [ReportController::class, 'salesSummary'])->name('sales.summary'); // weekly/monthly
        Route::get('/sales-by-cashier', [ReportController::class, 'salesByCashier'])->name('sales.byCashier');
        Route::get('/best-selling-products', [ReportController::class, 'bestSellingProducts'])->name('sales.bestProducts');
    });

    Route::get('/profit-report', [\App\Http\Controllers\Admin\ReportController::class, 'profitReport'])
        ->name('reports.profit');

    Route::get('/stock-summary', [ReportController::class, 'stockSummary'])->name('stock-summary');
    Route::get('/out-of-stock', [ReportController::class, 'out-of-stock'])->name('out-of-stock');
    Route::get('/stock-movements', [ReportController::class, 'stockMovements'])->name('stock-movements');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/product-by-barcode', [PosController::class, 'productByBarcode'])->name('pos.productByBarcode');
    Route::get('/pos/search-products', [PosController::class, 'searchProducts'])->name('pos.searchProducts');
    Route::post('/pos/complete', [PosController::class, 'storeSale'])->name('pos.storeSale');


    Route::get('/pos/invoice/{sale}', [PosInvoiceController::class, 'show'])->name('pos.invoice.show');         // A4 HTML
    Route::get('/pos/invoice/{sale}/thermal', [PosInvoiceController::class, 'thermal'])->name('pos.invoice.thermal'); // 80mm
    Route::get('/pos/invoice/{sale}/pdf', [PosInvoiceController::class, 'pdf'])->name('pos.invoice.pdf');      // A4 PDF
});

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});


Route::get('/login', function () {
    return view('auth.signin');
})->name('login');

// Login action
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index'); // create resources/views/dashboard.blade.php
    });
});
