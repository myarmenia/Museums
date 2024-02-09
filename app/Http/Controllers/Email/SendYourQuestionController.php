<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendYourQuestionRequest;
use App\Mail\SendQuestionEmail;
use Illuminate\Http\Request;
use Mail;

class SendYourQuestionController extends Controller
{
    public function __invoke(SendYourQuestionRequest $request)
    {
        $data = $request->all();

        $result = Mail::send(new SendQuestionEmail($data));
   
        if($result){
            return response()->json(['success' => true, 'message' => translateMessageApi('email-send')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')],500);

    }
}
