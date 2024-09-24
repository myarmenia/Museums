<?php
namespace App\Traits;

use App\Models\Product;
use App\Models\Purchase;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

 trait getPurchaseUniqueTokenTraite{

      public function getToken(){

          do {
            return $token = generateToken();
          } while (Purchase::where("token", $token)->first() instanceof Purchase);
      }


 }
