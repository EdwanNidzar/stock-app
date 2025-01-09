<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
Route::get('/home/usage', [App\Http\Controllers\DashboardController::class, 'usage'])->name('home.usage');

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{id}/updateRole', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('users/{id}', [\App\Http\Controllers\UserController::class, 'deleteAccount'])->name('users.delete');
    Route::get('users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
});

Route::middleware('auth')->group(function () {

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::resource('suppliers', \App\Http\Controllers\SupplierController::class)->middleware('auth');
Route::get('suppliers/export/pdf', [\App\Http\Controllers\SupplierController::class, 'exportPDF'])
    ->name('suppliers.exportPDF')
    ->middleware('auth');
    
Route::resource('categories', \App\Http\Controllers\CategoryProductController::class)->middleware('auth');
Route::get('categories/export/pdf', [\App\Http\Controllers\CategoryProductController::class, 'exportPDF'])
    ->name('categories.exportPDF')
    ->middleware('auth');

Route::resource('products', \App\Http\Controllers\ProductController::class)->middleware('auth');
Route::post('products/{product}/updateStock', [\App\Http\Controllers\ProductController::class, 'updateStock'])
    ->name('products.updateStock')
    ->middleware('auth');
Route::get('products/export/pdf', [\App\Http\Controllers\ProductController::class, 'exportPDF'])
    ->name('products.exportPDF')
    ->middleware('auth');

Route::get('history/{tableName}', [\App\Http\Controllers\HistoryController::class, 'tableName'])
    ->name('history.tableName')
    ->middleware('auth');

// route for cek stock
Route::get('/check-stock', [\App\Http\Controllers\ProductController::class, 'showCheckStockForm'])->name('check-stock');
Route::post('/check-product-stock', [\App\Http\Controllers\ProductController::class, 'checkProductStock'])->name('check-product-stock');
Route::post('/export-stock-report', [\App\Http\Controllers\ProductController::class, 'exportStockReport'])->name('export-stock-report');
Route::get('/product/usage', [\App\Http\Controllers\ProductController::class, 'showUsageForm'])->name('product.usage.form');
Route::get('/product/usage/results', [\App\Http\Controllers\ProductController::class, 'showUsage'])->name('product.usage');
Route::get('/products/usage/pdf', [\App\Http\Controllers\ProductController::class, 'downloadUsageReport'])->name('product.usage.pdf');