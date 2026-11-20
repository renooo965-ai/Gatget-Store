<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->only(['index']);
    Route::resource('categories', CategoryController::class)->only(['index']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {

     Route::resource('products', ProductController::class)->except(['index']);
    Route::resource('categories', CategoryController::class)->except(['index']);
    Route::resource('employees', EmployeeController::class)->except(['index', 'show']);
    Route::get('/transactions/sale/create', [TransactionController::class, 'createSale'])->name('transactions.create-sale');
    Route::post('/transactions/sale', [TransactionController::class, 'storeSale'])->name('transactions.store-sale');
    Route::get('/transactions/purchase/create', [TransactionController::class, 'createPurchase'])->name('transactions.create-purchase');
    Route::post('/transactions/purchase', [TransactionController::class, 'storePurchase'])->name('transactions.store-purchase');
    Route::delete('/transactions/sale/{sale}', [TransactionController::class, 'destroySale'])->name('transactions.destroy-sale');
    Route::delete('/transactions/purchase/{purchase}', [TransactionController::class, 'destroyPurchase'])->name('transactions.destroy-purchase');
    
});


