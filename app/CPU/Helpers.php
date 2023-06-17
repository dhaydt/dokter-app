<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Helpers
{
  public static function error_processor($validator)
  {
    $err_keeper = [];
    foreach ($validator->errors()->getMessages() as $index => $error) {
      array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
    }

    return $err_keeper;
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

    return $dir.$imageName;
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
