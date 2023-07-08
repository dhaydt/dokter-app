<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function changePassword(Request $request){
        $user = auth()->user();
        if($user){
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:8',
                'c_password' => 'required|same:password'
            ], [
                'password.required' => 'Masukan password baru!',
                'password.min' => 'Minimal 8 karakter!',
                'c_password.required' => 'Masukan konfirmasi password!',
                'c_password.same' => 'Password konfirmasi tidak sama!',
            ]);
            if ($validator->fails()) {
                $messages = [];
                foreach ($validator->errors()->getMessages() as $index => $error) {
                    array_push($messages, ['code' => $index, 'message' => $error[0]]);
                }
    
                foreach($messages as $m){
                    Notification::make()
                    ->title($m['message'])
                    ->icon('heroicon-o-check-circle')
                    ->iconColor('danger')
                    ->send();
                }
                return redirect()->back();
            }

            $user = User::find(auth()->id());
            $user->password = Hash::make($request['password']);
            $user->save();

            Notification::make()
                ->title('Password berhasil diganti!')
                ->icon('heroicon-o-check-circle')
                ->iconColor('success')
                ->send();
            return redirect()->back();
        }

        Notification::make()
            ->title('Anda tidak terautentikasi!')
            ->icon('heroicon-o-check-circle')
            ->iconColor('danger')
            ->send();
        return redirect()->back();
    }
}
