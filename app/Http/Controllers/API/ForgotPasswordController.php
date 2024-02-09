<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeNewPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\API\ForgotPasswordService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    protected $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function sendResetLink(Request $request)
    {
       $message = $this->forgotPasswordService->sendResetLink($request->get('email'));

       return response()->json(['message' => $message]);
    }

    public function checkForgotToken(ForgotPasswordRequest $request)
    {
        $haveOrNot = $this->forgotPasswordService->checkForgotToken($request->all());

        if($haveOrNot){
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function sendNewPassword(ChangeNewPasswordRequest $request)
    {
        $changed = $this->forgotPasswordService->sendNewPassword($request->all());

        if($changed){
            return response()->json(['success' => true, 'message' => translateMessageApi('password-changed-successfully')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')], 500);
    }
}
