<?php

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DetailDokter;
use App\Models\DetailUser;
use App\Models\History;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\ResepObat;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

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

Route::get('/primary_detail', function(){
    $users = DetailUser::all();
    $dokter = DetailDokter::all();

    foreach ($users as $key => $u) {
        $u->id = $u['code_uniq_users'];
        $u->save();
    }
    
    foreach ($dokter as $key => $d) {
        $d->id = $d['code_uniq_users'];
        $d->save();
    }

    dd('generated custom primary details');
});

Route::get('/history_assign', function(){
    $history = History::all();

    foreach($history as $h){
        $h->resep_id = $h['code_uniq_resep'];
        $h->save();
    }

    dd('history assigned successfully');
});

Route::get('/primary_obat', function(){
    $obat = Obat::all();
    $resep = Resep::all();

    foreach ($obat as $key => $o) {
        $o->id = $o['code_uniq'];
        $o->save();
    }
    
    foreach ($resep as $key => $r) {
        $r->id = $r['code_uniq'];
        $r->save();
    }

    dd('id primary obat dan resep was changed');
});

Route::get('assign_admin', function(){
    $user = User::where('name', 'admin')->first();
    // $role = Role::create(['name' => 'admin']);
    $user->assignRole('admin');
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
        $d['code_uniq_users'] = $d['user']['code_uniq'] ?? '-';
        $d->save();
    }
    
    $pasien = DetailUser::with('user')->get();

    foreach($pasien as $p){
        $p['code_uniq_users'] = $p['user']['code_uniq'] ?? '-';
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
        $r['code_uniq_dokter']  = $r['dokter']['code_uniq'] ?? 'UD000';
        $r['code_uniq_user']  = $r['user']['code_uniq'] ?? 'UP000';
        $r->save();
    }

    $resep_obat = ResepObat::get();

    foreach($resep_obat as $ro){
        $ro['code_uniq_resep'] = $ro['resep']['code_uniq'] ?? 'RB000';
        $ro['code_uniq_obat'] = $ro['obat']['code_uniq'] ?? 'OB000';
        $ro->save();
    }

    $histories = History::get();

    foreach($histories as $h){
        $h['code_uniq_resep'] = $h['resep']['code_uniq'] ?? 'RB000';
        $h->save();
    }


    return 'code generated successfully!';
});

Route::get('login', function(){
    return redirect()->route('filament.auth.login');
})->name('login');
