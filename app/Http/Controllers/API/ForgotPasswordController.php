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
       $this->forgotPasswordService->sendResetLink($request->get('email'));

       return response()->json(['success' => true, 'message' => translateMessageApi('password-reset-link-sent')], 200);

    }

    public function checkForgotToken(ForgotPasswordRequest $request)
    {
        $haveOrNot = $this->forgotPasswordService->checkForgotToken($request->all());

        if($haveOrNot){
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('wrong-code')]);
    }

    public function sendNewPassword(ChangeNewPasswordRequest $request)
    {
        $changed = $this->forgotPasswordService->sendNewPassword($request->all());

        if($changed){
            return response()->json(['success' => true, 'message' => translateMessageApi('password-changed-successfully')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')], 500);
    }

    public function resendForgot(Request $request)
    {
        $send = $this->forgotPasswordService->resendForgot($request->all());

        if($send){
            return response()->json(['success' => true, 'message' => translateMessageApi('password-reset-link-sent'), 200]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong'), 500]);
        
    }
}
