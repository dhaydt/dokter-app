<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DokterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login_dokter', [AuthController::class, 'loginDokter']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('profile_pasien', [UserController::class, 'profile']);
    Route::get('dokter_list', [ApiController::class, 'dokter_list']);

    Route::prefix('dokter')->group(function(){
        Route::get('profile_dokter', [DokterController::class, 'profile']);
    });
    
});
