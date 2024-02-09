<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\SendFeedbackEmail;
use Illuminate\Http\Request;
use Mail;

class SendFeedbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();
        
        $result = Mail::send(new SendFeedbackEmail($data));

        if($result){
            return response()->json(['success' => true, 'message' => translateMessageApi('email-send')]);
        }

        return response()->json(['message' => translateMessageApi('something-went-wrong')], 500);
    }
}
