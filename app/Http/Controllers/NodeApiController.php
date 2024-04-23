<?php

namespace App\Http\Controllers;

use App\Traits\NodeApi\QrTokenTrait;
use Illuminate\Http\Request;

class NodeApiController extends Controller
{
    use QrTokenTrait;
    public function test()
    {
        $resp = $this->getTokenQr(5);
        dd($resp);

    }
}
