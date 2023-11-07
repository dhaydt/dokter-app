<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Helpers
{
  public static function generateUniq($initial, $id){
    $code = $initial. str_pad( $id, 3, "0", STR_PAD_LEFT );

    return $code;
  }
  public static function response_format($code, $status, $message, $data)
  {
    $data = [
      "code" => $code,
      "status" => $status,
      "message" => $message,
      "data" => $data
    ];
    return $data;
  }

  public static function randomString($length  = 8)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function error_processor($validator, $code, $status, $message, $data)
  {
    $err_keeper = [];
    foreach ($validator->errors()->getMessages() as $index => $error) {
      array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
    }

    $data = [
      "code" => $code,
      "status" => $status,
      "message" => $message,
      "data" => $err_keeper
    ];

    return $data;
  }
  public static function checkRole($user)
  {
    return $user['user_is'];
  }

  public static function upload(string $dir, string $format, $image = null)
  {
    if ($image != null) {
      $imageName = Carbon::now()->toDateString() . '-' . uniqid() . '.' . $format;
      if (!Storage::disk('public')->exists($dir)) {
        Storage::disk('public')->makeDirectory($dir);
      }
      Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
    } else {
      $imageName = null;
    }

    return $dir . $imageName;
  }

  public static function update(string $dir, $old_image, string $format, $image = null)
  {
    // dd($dir.$old_image);
    if (Storage::disk('public')->exists($dir . $old_image)) {
      Storage::disk('public')->delete($dir . $old_image);
    }
    $imageName = Helpers::upload($dir, $format, $image);

    return $imageName;
  }
}
