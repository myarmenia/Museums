<?php
namespace App\Traits\Turnstile;

use App\Models\Turnstile;

trait ActiveQrs
{
  public function get($mac, $local_ip)
  {
    // $turnstile = Turnstile::museum($mac)->first();

    // if ($turnstile != null) {
    //   return $turnstile->update(['local_ip' => $local_ip]);

    // }

    // return false;
  }


}
