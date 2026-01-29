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
