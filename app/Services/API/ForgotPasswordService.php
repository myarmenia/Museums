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

      return true;
    } else {
      return false;
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

  public function resendForgot($data)
    {
        $email = $data['email'];

        if($forgot = PasswordReset::where('email', $email)->first()){
            $token = mt_rand(10000, 99999);
            $forgot->update([
                'token' => $token
            ]);

            Mail::send(new SendForgotToken($email, $token));

            return true;
        }

        return false;
        
    }
  
}
