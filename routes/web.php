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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::patch('users/{id}/updateRole', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('users/{id}', [\App\Http\Controllers\UserController::class, 'deleteAccount'])->name('users.delete');
    Route::get('users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
});

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

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
