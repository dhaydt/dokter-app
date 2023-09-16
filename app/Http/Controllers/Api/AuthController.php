<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\TapLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function delete_log(){
        $data = TapLogs::get();
        foreach($data as $d){
            $d->delete();
        }

        return response()->json(['status' => 'success', 'data' => [],'message' => 'data log deleted successfully'], 200);
    }
    public function new_user(Request $request){
        $data = User::orderBy('created_at', 'desc')->get();
        $tap = TapLogs::orderBy('created_at', 'desc')->get();
        $format = [];

        foreach($data as $d){
            $item = [
                'name' => $d['name'],
                'rfid' => $d['email']
            ];

            array_push($format, $item);
        }

        return response()->json(['status' => 'success', 'data' => $format, 'data_log' => $tap], 200);

    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'name' => 'required',
            'accesskey' => 'required|min:6',
        ], [
            'rfid.required'    => 'Mohon masukan ID RFID',
            'name.required'    => 'Mohon masukan nama pasien / dokter',
            'accesskey.required'    => 'Mohon masukan kode akses',
            'accesskey.min'    => 'Minimal 6 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }

        $check = User::where('email', $request->rfid)->first();

        if($check){
            return response()
                ->json(['status' => 'error', 'message' => 'RFID sudah terdaftar'], 401);
        }else{
            // return $request;
            $user = new User();
            $user->name = $request->name;
            $user->password = Hash::make($request->accesskey);
            $user->email = $request->rfid;
            $user->user_is = '-';
            $user->save();

            return response()->json(Helpers::response_format(200, true, "success", ["user_is" => '-', "user" => $user]));
        }
    }
    public function check(Request $request){
        $validator = Validator::make($request->all(), [
            'rfid' => 'required'
        ], [
            'rfid.required'    => 'Mohon masukan ID RFID'
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }

        $newTap = new TapLogs();
        $newTap->rfid = $request->rfid;
        $newTap->save();

        $check = User::where('email', $request->rfid)->first();

        if(!$check){
            $user = new User();
            $user->name = 'user baru';
            $user->password = Hash::make('123456');
            $user->email = $request->rfid;
            $user->user_is = '-';
            $user->save();

            return response()
                ->json(['status' => false, 'message' => 'RFID tidak ditemukan, dan sudah didaftarkan', 'rfid' => $request->rfid], 401);
        }else{
            $user_is = $check['user_is'] == 'user' ? 'pasien' : 'dokter';
            $format = [
                "name" => $check['name'],
                "rfid" => $check['email'],
                "user_is" => $user_is,
            ];

            return response()->json(Helpers::response_format(200, true, "success", ["user_is" => $user_is, "user" => $format]));
        }
    }

    public function newLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'accesskey' => 'required|min:6',
        ], [
            'rfid.required' => 'Mohon masukan RFID'
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }

        $user = User::where('email', $request['rfid'])->first();

        if(!$user){
            return response()
                ->json(['status' => 'error', 'message' => 'User tidak ditemukan!'], 401);
        }

        if (!Auth::attempt(['email' => $request->rfid, 'password' => $request->accesskey])) {
            return response()
                ->json(['status' => 'error', 'message' => 'Kode Akses Salah!'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if($user['user_is'] == 'user'){

            return response()->json(Helpers::response_format(200, true, "success", ["access_token" => $token, "user_is" => 'pasien']));

        }elseif($user['user_is'] == 'dokter'){

            return response()->json(Helpers::response_format(200, true, "success", ["access_token" => $token, "user_is" => 'dokter']));
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'accesskey' => 'required|min:6'
        ], [
            'rfid.required' => 'Mohon masukan RFID!',
            'accesskey.required' => 'Masukan Kode Akses!',
            'accesskey.min' => 'Kode Akses Minimal 6 angka!',
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }
        $check = User::where(['email' => $request->rfid, 'user_is' => 'user'])->first();
        if(!$check){
            return response()
                ->json(['status' => 'error', 'message' => 'RFID tidak ditemukan!'], 401);
        }
        if (!Auth::attempt(['email' => $request->rfid, 'password' => $request->accesskey])) {
            return response()
                ->json(['status' => 'error', 'message' => 'Kode Akses Salah!'], 401);
        }

        $user = User::where('email', $request['rfid'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        if($user['user_is'] == 'user'){
            return response()->json(Helpers::response_format(200, true, "success", ["access_token" => $token, "user_is" => 'pasien']));
        }
        return response()->json(Helpers::response_format(403, false, "Terjadi kesalahan, Hubungi admin", null));
    }
    
    public function loginDokter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'accesskey' => 'required|min:6'
        ], [
            'rfid.required' => 'Mohon masukan RFID!',
            'accesskey.required' => 'Masukan Kode Akses!',
            'accesskey.min' => 'Kode Akses Minimal 6 angka!',
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }
        $check = User::where(['email' => $request->rfid, 'user_is' => 'dokter'])->first();
        if(!$check){
            return response()
                ->json(['status' => 'error', 'message' => 'RFID tidak ditemukan!'], 401);
        }
        if (!Auth::attempt(['email' => $request->rfid, 'password' => $request->accesskey])) {
            return response()
                ->json(['status' => 'error', 'message' => 'Kode Akses Salah!'], 401);
        }

        $user = User::where('email', $request['rfid'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        if($user['user_is'] == 'dokter'){
            return response()->json(Helpers::response_format(200, true, "success", ["access_token" => $token, "user_is" => 'dokter']));
        }
        return response()->json(Helpers::response_format(403, false, "Terjadi kesalahan, Hubungi admin", null));
    }
}
