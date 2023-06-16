<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dokter_list(){
        $user = User::with('detailDokter')->where('user_is', 'dokter')->get();

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }
}
