<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function check(Request $request){
        $validator = Validator::make($request->all(), [
            'rfid' => 'required'
        ], [
            'rfid.required'    => 'Mohon masukan ID RFID'
        ]);

        if ($validator->fails()) {
            return response()->json(Helpers::error_processor($validator, 403, false, 'error', null), 403);
        }

        $check = User::where('email', $request->rfid)->first();

        if(!$check){
            return response()
                ->json(['status' => 'error', 'message' => 'RFID tidak ditemukan!'], 401);
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
