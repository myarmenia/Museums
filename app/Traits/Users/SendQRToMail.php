<?php
namespace App\Traits\Users;

use App\Mail\SendQRTiketsToUsersEmail;
use App\Mail\SendSingleQRToMail;
use App\Models\TicketQr;
use App\Models\User;
use Mail;

trait SendQRToMail
{
    public function sendQR($id)
    {

      $user = auth('api')->user();
      $list_qr_ids = $this->getList()->pluck('id')->toArray();

      if(in_array($id, $list_qr_ids)){

          $qr = TicketQr::find($id);
          $email = $user->email;
          try {
              mail::send(new SendSingleQRToMail($qr, $email));

              return true;
          } catch (\Throwable $th) {
              return false;

          }
      }

      else{
        return false;
      }


    }
}
