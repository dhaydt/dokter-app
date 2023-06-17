<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function profile(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $user = User::with('detailDokter')->find($user['id']);
            return response()->json(['status' => 'success', 'data' => $user], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
    
    public function pasien(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $user = User::with('detailDokter')->find($user['id']);

            $resep = Resep::with('user')->where('dokter_id', $user['id'])->orderBy('created_at', 'desc')->get();
            $formatResep = [];

            foreach($resep as $r){
                $data = [
                    'id' => $r['id'],
                    'nama_pasien' => $r['user']['name'],
                ];

                array_push($formatResep, $data);
            }

            return response()->json(['status' => 'success', 'data' => $formatResep], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
    
    public function history(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $user = User::with('detailDokter')->find($user['id']);

            $resep = Resep::with('user', 'history')->whereHas('user', function($q){
                $q->with('detailUser');
            })->find($request->id);

            $formatHistory = [];

            foreach($resep['history'] as $h){
                $data = [
                    'id' => $h['id'],
                    'hari_ke' => $h['hari_ke'],
                    'waktu_minum' => $h['waktu_minum'],
                    'img' => $h['img'] ? getenv('APP_URL').'/storage/'.$h['img'] : null,
                    'status' => $h['status'],
                    'expire' => $h['tanggal'],
                ];

                array_push($formatHistory, $data);
            }

            $formatResep = [
                'id' => $resep['id'],
                'nama_pasien' => $resep['user']['name'],
                'nik' => $resep['user']['detailUser']['nik'],
                'lahir' => $resep['user']['detailUser']['ttl'],
                'umur' => $resep['user']['detailUser']['umur'],
                'kelamin' => $resep['user']['detailUser']['kelamin'],
                'berat' => $resep['user']['detailUser']['berat'],
                'tinggi' => $resep['user']['detailUser']['tinggi'],
                'phone' => $resep['user']['detailUser']['phone'],
                'alergi' => $resep['user']['detailUser']['alergi'],
                'tgl_mulai' => $resep['tgl_mulai'],
                'tgl_selesai' => $resep['tgl_selesai'],
            ];


            return response()->json(['status' => 'success', 'rekam_medis' => $formatResep, 'history' => $formatHistory], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
}
