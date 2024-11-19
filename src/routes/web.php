<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;


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

Route::get('/welcome', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ShopController::class, 'index']);
    Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');;
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::delete('/reservation', [ReservationController::class, 'destroy']);
    Route::match(['get', 'post'], '/search', [ShopController::class, 'search']);
    Route::post('/favorite', [FavoriteController::class, 'store']);
    Route::delete('/favorite', [FavoriteController::class, 'destroy']);
    Route::get('/mypage', [AuthController::class, 'index']);
    // 予約編集のルート
    Route::get('/reservation/edit/{id}', [ReservationController::class, 'edit'])->name('reservation.edit');
    // 予約更新のルート
    Route::patch('/reservation/update', [ReservationController::class, 'update'])->name('reservation.update');

    Route::get('/reservation/scan', [ReservationController::class, 'scan'])->name('reservation.scan');
    Route::get('/reservation/verify/{id?}', [ReservationController::class, 'verify'])->name('reservation.verify');
    Route::post('/reservation/verify/{id}', [ReservationController::class, 'updateIsVisited'])->name('reservation.updateIsVisited');

    Route::post('/review', [ReviewController::class, 'store']);
    Route::delete('/review/delete', [ReviewController::class, 'destroy']);
    Route::get('/review/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/update/{review}', [ReviewController::class, 'update'])->name('review.update');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');


    Route::post('/reservation/process', [ReservationController::class, 'process'])->name('reservation.process');
});
