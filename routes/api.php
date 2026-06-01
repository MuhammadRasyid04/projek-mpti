<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\HunianController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SewaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

//semua user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    //auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    //hunian
    Route::get('/hunian', [HunianController::class, 'index']);
    Route::get('/hunian/{id}', [HunianController::class, 'show']);
    Route::get('/hunian/{id}/biaya', [BiayaController::class, 'show']);

    //sewa
    Route::get('/sewa/me', [SewaController::class, 'me']);
    Route::post('/sewa', [SewaController::class, 'store']);

    //tagihan
    Route::get('/tagihan/me', [TagihanController::class, 'me']);

    //transaksi
    Route::get('/transaksi/me', [TransaksiController::class, 'me']);
    Route::get('/transaksi/{invoice}', [TransaksiController::class, 'show']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);

    //pembayaran
    Route::get('/pembayaran/me', [PembayaranController::class, 'me']);
    Route::get('/pembayaran/{invoice}', [PembayaranController::class, 'show']);
    Route::post('/pembayaran/upload', [PembayaranController::class, 'upload']);

    //user
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/foto', [UserController::class, 'updateFoto']);
});
    

//admin
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    //hunian
    Route::post('/hunian', [HunianController::class, 'store']);
    Route::put('/hunian/{id}', [HunianController::class, 'update']);
    Route::delete('/hunian/{id}', [HunianController::class, 'destroy']);

    //biaya
    Route::post('/hunian/{id}/biaya', [BiayaController::class, 'store']);
    Route::put('/hunian/{id}/biaya', [BiayaController::class, 'update']);

    //sewa
    Route::get('/sewa', [SewaController::class, 'index']);
    Route::get('/sewa/{id}', [SewaController::class, 'show']);
    Route::put('/sewa/{id}', [SewaController::class, 'update']);
    Route::delete('/sewa/{id}', [SewaController::class, 'destroy']);

    //tagihan
    Route::get('/tagihan', [TagihanController::class, 'index']);
    Route::get('/tagihan/{id}', [TagihanController::class, 'show']);
    Route::post('/tagihan/generate', [TagihanController::class, 'generate']);
    Route::put('/tagihan/{id}', [TagihanController::class, 'update']);

    //transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index']);

    //pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index']);
    Route::put('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi']);

    //users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});