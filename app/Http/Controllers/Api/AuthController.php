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
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $check = User::where(['email' => $request->rfid, 'user_is' => 'user']);
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
            // $type = 'customer';
            // if($user['birthday'] == null || $user['gender'] == null || $user['occupation'] == null || $user['province_id'] == null || $user['city_id'] == null || $user['address'] == null){
            //     // dd($data);
            //     if($user['fcm'] && $user['is_notify'] == 1){
            //         $data = [
            //             'title' => 'Information your profile!',
            //             'description' => 'Please complete your profile to get a promo from us!'
            //         ];
            //         $notif = new Notifications();
            //         $notif->title = $data['title'];
            //         $notif->description = $data['description'];
            //         $notif->save();

            //         $id = $notif->id ?? $notif['id'];

            //         $notifSave = new NotifReceiver();
            //         $notifSave->notification_id = $id;
            //         $notifSave->user_id = $user['id'];
            //         $notifSave->is_read = 0;
            //         $notifSave->save();

            //         Helpers::send_push_notif_to_device($user['fcm'], $data,null);

            //     }

            // }
            return response()
            ->json(['message' => 'Hi ' . $user->name . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer']);
        }
        return response()
            ->json(['message' => 'Terjadi kesalahan, hubungi admin!']);
    }
}
