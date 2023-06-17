<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\Resep;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function submit_lapor(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'user') {
            $validator = Validator::make($request->all(), [
                'img' => 'required',
                'waktu_minum' => 'required',
                'history_id' => 'required',
            ], [
                'img.required' => 'Kirim foto minum obat!',
                'waktu_minum' => 'Masukan waktu minum obat!',
                'history_id' => 'Masukan id laporan!'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }

            if(!$request->file('img')){
                return response()->json(['errors' => ['code' => 'img', 'message' => 'kirim foto minum obat']]);
            }

            $status = 'done';
            
            $user = History::find($request->history_id);

            // $expired = $user['tanggal'];
            $expired = $user['tanggal'];
            $minum = Carbon::parse($request->waktu_minum)->toDateString();

            if($minum > $expired){
                $status = 'fail';
            }

            $user->img = Helpers::upload('laporan/', 'png', $request->file('img'));
            $user->waktu_minum = $request->waktu_minum;
            $user->status = $status;
            $user->save();

            $user = User::with('detailUser')->find($user['id']);
            return response()->json(['status' => 'success', 'message' => 'Status minum obat anda '.$status], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
    
    public function laporan(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'user') {
            $data = History::with('resep')->whereHas('resep', function($q) use($user){
                $q->where('user_id', $user['id']);
            })->get();

            $formatData = [];
            foreach($data as $d){
                $dat = [
                    'id' => $d['id'],
                    'hari_ke' => $d['hari_ke'],
                    'waktu_minum' => $d['waktu_minum'],
                    'img' => getenv('APP_URL').'/storage/'.$d['img'],
                    'status' => $d['status'],
                    'expire' => $d['tanggal'],
                ];
                array_push($formatData, $dat);
            }
            return response()->json(['status' => 'success', 'data' => $formatData], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
    
    
    public function rekam_medis(Request $request){
        $user = $request->user();

        $role = Helpers::checkRole($user);
        if ($role == 'user') {
            $user = User::with('detailUser')->find($user['id']);
            $data = Resep::with('history')->where('user_id', $user['id'])->get();
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
                    'id' => $d['id'],
                    'dokter' => User::find($d['dokter_id'])['name'],
                    'tanggal_mulai' => $d['tgl_mulai'],
                    'tanggal_selesai' => $d['tgl_selesai'],
                    'dosis' => $d['dosis'].' X '.$d['perhari'].' hari',
                    'history' => $d['history']
                ];
                array_push($formatData, $dat);
            }
            return response()->json(['status' => 'success', 'pasien' => $formatUser, 'rekam_medis' => $formatData], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'not authorized'], 200);
    }
}
