<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'user') {
            $user = User::with('detailUser')->find($user['id']);
            return response()->json(['status' => 'success', 'data' => $user], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
    
    public function rekam_medis(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'user') {
            $user = User::with('detailUser')->find($user['id']);
            $data = Resep::where('user_id', $user['id'])->get();
            $formatUser = [
                'name' => $user['name'],
                'nik' => $user['detailUser']['nik'],
                'lahir' => $user['detailUser']['ttl'],
                'umur' => $user['detailUser']['umur'],
                'kelamin' => $user['detailUser']['kelamin'],
                'berat' => $user['detailUser']['berat'],
                'tinggi' => $user['detailUser']['tinggi'],
                'phone' => $user['detailUser']['phone'],
                'alamat' => $user['detailUser']['alamat'],
                'alergi' => $user['detailUser']['alergi'] ?? 'Tidak ada',
            ];
            $formatData = [];
            foreach($data as $d){
                $dat = [
                    'dokter' => User::find($d['dokter_id'])['name'],
                    'tanggal_mulai' => $d['tgl_mulai'],
                    'tanggal_selesai' => $d['tgl_selesai'],
                    'dosis' => $d['dosis'].' X '.$d['perhari'].' hari',
                ];
                array_push($formatData, $dat);
            }
            return response()->json(['status' => 'success', 'pasien' => $formatUser, 'rekam_medis' => $formatData], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
}
