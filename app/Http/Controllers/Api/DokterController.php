<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
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
}
