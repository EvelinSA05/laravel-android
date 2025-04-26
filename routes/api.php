<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Models\Booking;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Service;
use App\Models\Transaksi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/loginl', App\Http\Controllers\Api\LoginController::class)->name('login');
// Route::post('/loginl', [LoginController::class, 'login']);
// Route::post('/loginl', [LoginController::class, 'login']);



/**
 * route "/user"
 * @method "GET"
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth.api'])->group(function () {


    Route::get('/user', [UserController::class, 'index']);

    Route::get('/service', [ServiceController::class, 'index']);
    Route::post('/service', [ServiceController::class, 'store']);
    Route::get('/service/{id}', [ServiceController::class, 'show']);
    Route::put('/service/{id}', [ServiceController::class, 'update']);
    Route::delete('/service/{id}', [ServiceController::class, 'destroy']);

    Route::get('/detail', [DetailController::class, 'index']);


    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::post('/pelanggan', [PelangganController::class, 'store']);
    Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

    Route::get('/kategori', [KategoriController::class, 'index']);

    Route::get('/kategori/{id}', [KategoriController::class, 'show']);
    Route::put('/kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

    Route::get('/posts', [TransaksiController::class, 'index2'])->name('posts');

    Route::get('/transaksiView', [ViewController::class, 'getDataFromView']);
    Route::get('/transaksiView2', [ViewController::class, 'getDataFromView2']);
    Route::get('/transaksiView3', [ViewController::class, 'getDataFromView3']);
    Route::get('/transaksiViewService', [ViewController::class, 'getDataFromViewService']);

    Route::get('/total-pendapatan-kasir', [TransaksiController::class, 'totalPendapatanPerHari']);

    Route::get('/transaksi-booking/{bookingValue}', [TransaksiController::class, 'transaksiBooking']);

    Route::post('/kategori', [KategoriController::class, 'store']);


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
