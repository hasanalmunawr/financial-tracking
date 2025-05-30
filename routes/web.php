<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {


    Route::prefix('/category')->group(function () {
       Route::get('/', [CategoryController::class, 'index'])->name('category.index');
       Route::post('/', [CategoryController::class, 'store'])->name('category.store');
       Route::get('/my-category', [CategoryController::class, 'getMyCategory'])->name('category.get-my-Category');
    });

    Route::prefix('/transaction')->group(function () {
       Route::get('/', [TransactionController::class, 'index'])->name('transaction.index');
       Route::post('/', [TransactionController::class, 'store'])->name('transaction.store');
       Route::get('/my-transaction', [TransactionController::class, 'getMyTransactions'])->name('transaction.get-my-Transaction');
    });

    Route::prefix('/rekening')->group(function () {
       Route::get('/', [RekeningController::class, 'index'])->name('rekening.index');
       Route::post('/', [RekeningController::class, 'store'])->name('rekening.store');
       Route::get('/my-rekening', [RekeningController::class, 'getMyRekening'])->name('rekening.get-my-rekening');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
