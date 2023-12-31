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
Route::post('/login_auto', [AuthController::class, 'newLogin']);
Route::post('/check_rfid', [AuthController::class, 'check']);
Route::post('/login_dokter', [AuthController::class, 'loginDokter']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/new_user', [AuthController::class, 'new_user']);
Route::get('/delete_log', [AuthController::class, 'delete_log']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('profile_pasien', [UserController::class, 'profile']);

    Route::get('rekam_medis', [UserController::class, 'rekam_medis']);

    Route::get('informasi_obat', [UserController::class, 'obat']);

    Route::get('laporan', [UserController::class, 'laporan']);

    Route::post('submit_lapor', [UserController::class, 'submit_lapor']);

    Route::get('dokter_list', [ApiController::class, 'dokter_list']);

    Route::post('change_password_pasien', [UserController::class, 'changePassword']);

    Route::prefix('dokter')->group(function(){
        Route::get('profile_dokter', [DokterController::class, 'profile']);
        Route::get('list_pasien', [DokterController::class, 'pasien']);
        Route::post('pasien_history', [DokterController::class, 'history']);
        Route::post('change_password_dokter', [DokterController::class, 'changePassword']);
    });
    
});
