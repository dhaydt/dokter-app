<?php

use App\Http\Controllers\Controller;
use App\Models\DetailDokter;
use App\Models\DetailUser;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    dd('Storage linked!');
});
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    dd('config cleared!');
});

Route::get('/migrate', function () {
    Artisan::call('migrate', [
        '--force' => true,
    ]);
    dd('migrated!');
})->name('migrate');
Route::get('/seed', function () {
    Artisan::call('db:seed', [
        '--force' => true,
    ]);
    dd('seeded!');
});
Route::get('/', function () {
    return redirect()->route('filament.auth.login');
});

Route::post('change-password', [Controller::class, 'changePassword'])->name('password');

Route::get('generate_code', function(){
    $users = User::get();

    foreach($users as $u){
        if($u['user_is'] == 'dokter'){
            $u['code_uniq']  = 'UD'. str_pad( $u['id'], 3, "0", STR_PAD_LEFT );
            $u->save();
        }elseif($u['user_is'] == 'admin'){
            $u['code_uniq']  = 'UA'. str_pad( $u['id'], 3, "0", STR_PAD_LEFT );
            $u->save();
        }elseif($u['user_is'] == 'user'){
            $u['code_uniq']  = 'UP'. str_pad( $u['id'], 3, "0", STR_PAD_LEFT );
            $u->save();
        }else{
            $u['code_uniq']  = 'UP'. str_pad( $u['id'], 3, "0", STR_PAD_LEFT );
            $u->save();
        }
    }

    $dokter = DetailDokter::with('user')->get();

    foreach($dokter as $d){
        $d['code_uniq_users'] = $d['user']['code_uniq'];
        $d->save();
    }
    
    $pasien = DetailUser::with('user')->get();

    foreach($pasien as $p){
        $p['code_uniq_users'] = $p['user']['code_uniq'];
        $p->save();
    }

    $obat = Obat::get();

    foreach($obat as $o){
        $o['code_uniq']  = 'OB'. str_pad( $o['id'], 3, "0", STR_PAD_LEFT );
        $o->save();
    }
    
    $resep = Resep::get();

    foreach($resep as $r){
        $r['code_uniq']  = 'RB'. str_pad( $r['id'], 3, "0", STR_PAD_LEFT );
        
        $drug = json_decode($r['obat_id']);
        $code_obat = [];
        foreach($drug as $d){
            $obt = Obat::find($d);
            if($obt){
                array_push($code_obat, $obt['code_uniq']);
            }
        }
        $r['code_uniq_obat'] = json_encode($code_obat);
        $r->save();
    }

    return 'code generated successfully!';
});

Route::get('login', function(){
    return redirect()->route('filament.auth.login');
})->name('login');
