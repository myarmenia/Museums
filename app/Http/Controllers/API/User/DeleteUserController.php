<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth('api')->user();
        $user->forceDelete();
        auth('api')->logout();
        return response()->json(['success' => true]);

    }
}
