<?php

namespace App\Services\API;

use App\Mail\SendForgotToken;
use App\Models\API\PasswordReset;
use App\Models\User;
use Illuminate\Support\Str;
use Mail;


class ForgotPasswordService
{
  public function sendResetLink($email)
  {
    $user = User::where('email', $email)->first();
    if ($user) {
      $token = mt_rand(10000, 99999);
      $email = $user->email;
      PasswordReset::updateOrCreate(
        ["email" => $email],
        ["token" => $token]
      );

      Mail::send(new SendForgotToken($email, $token));

      return response()->json(['success' => true, 'message' => translateMessageApi('password-reset-link-sent')], 200);
    } else {
      return response()->json(['error' => translateMessageApi('password-reset-link-sent')], 500);
      // return response()->json(['error' => translateMessageApi('user-email-not-found')], 500);
    }

  }

  public function checkForgotToken($data)
  {
    $haveOrNot = PasswordReset::where('email', $data['email'])->where('token', $data['token'])->first();

    if ($haveOrNot) {
      return true;
    }

    return false;
  }

  public function sendNewPassword($data)
  {
    $updated = User::where('email', $data['email'])->update([
      'password' => bcrypt($data['password']),
    ]);

    if ($updated) {
      PasswordReset::where('email', $data['email'])->delete();
      return true;
    }

    return false;
  }
}
