<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    public function profile(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $user = User::with('detailDokter')->find($user['id']);
            return response()->json(Helpers::response_format(200, true, "success", $user));
        }
        return response()->json(Helpers::response_format(200, false, "not authorized user", null));
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6',
                'c_password' => 'required|same:password'
            ], [
                'password.required' => 'Masukan password baru!',
                'password.min' => 'Minimal 6 karakter!',
                'c_password.required' => 'Masukan konfirmasi password!',
                'c_password.same' => 'Password konfirmasi tidak sama!',
            ]);

            if ($validator->fails()) {
                return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
            }
            $user = User::find($user['id']);
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(Helpers::response_format(200, true, "password berhasil diganti", $user));
        }
        return response()->json(Helpers::response_format(200, false, "not authorized user", null));
    }
    
    public function pasien(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $user = User::with('detailDokter')->find($user['id']);

            $resep = Resep::with('user')->where('dokter_id', $user['id'])->orderBy('created_at', 'desc')->get();
            $formatResep = [];

            foreach($resep as $r){
                if($r['user']){
                    $data = [
                        'id' => $r['id'],
                        'nama_pasien' => $r['user']['name'],
                    ];
                }

                array_push($formatResep, $data);
            }
            return response()->json(Helpers::response_format(200, true, "success", $formatResep));
        }
        return response()->json(Helpers::response_format(200, false, "not authorized user", null));
    }
    
    public function history(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'dokter') {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => 'Masukan ID pasien!',
            ]);
    
            if ($validator->fails()) {
                return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
            }
            $user = User::with('detailDokter')->find($user['id']);

            $resep = Resep::with('user', 'history')->whereHas('user', function($q){
                $q->with('detailUser');
            })->find($request->id);

            $formatHistory = [];
            
            if($resep){
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
                    'id' => $resep['id'] ?? '-',
                    'nama_pasien' => $resep['user']['name'] ?? '-',
                    'nik' => $resep['user']['detailUser']['nik'] ?? '-',
                    'lahir' => $resep['user']['detailUser']['ttl'] ?? '-',
                    'umur' => $resep['user']['detailUser']['umur'] ?? '-',
                    'kelamin' => $resep['user']['detailUser']['kelamin'] ?? '-',
                    'berat' => $resep['user']['detailUser']['berat'] ?? '-',
                    'tinggi' => $resep['user']['detailUser']['tinggi'] ?? '-',
                    'phone' => $resep['user']['detailUser']['phone'] ?? '-',
                    'alergi' => $resep['user']['detailUser']['alergi'] ?? '-',
                    'tgl_mulai' => $resep['tgl_mulai'] ?? '-',
                    'tgl_selesai' => $resep['tgl_selesai'] ?? '-',
                ];
            }else{
                $formatResep = [];
            }


            return response()->json(Helpers::response_format(200, true, "success", ['rekam_medis' => $formatResep, 'history' => $formatHistory]));
        }
        return response()->json(Helpers::response_format(200, false, "not authorized user", null));
    }
}
