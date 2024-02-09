<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\ClientProjectEmail;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use Storage;

use Mail;

class SendClientProjectDetController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $data = $request->all();
        $path = "";
        $dataElement = json_decode($data['data'], true);
        $readyArray = array_merge($dataElement, $data);
        unset($readyArray['data']);
        if($readyArray['file']){
            $path = FileUploadService::upload($readyArray['file'], 'order');
            $readyArray['file'] = $path;
        }

        $result = Mail::send(new ClientProjectEmail($readyArray));

        if(file_exists(storage_path($path))){
            Storage::delete($path);
        }
        if($result){
            return response()->json(['success' => true, 'message' => translateMessageApi('email-send')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);

    }
}
