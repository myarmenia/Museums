<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendOrderEmail;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mail;
use Illuminate\Support\Facades\Storage;


class SendOrderController extends Controller
{
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
        $readyArray['projectType'] = Arr::join($readyArray['projectType'], ', ', ' и ');
        $result = Mail::send(new SendOrderEmail($readyArray));
        if(file_exists(storage_path($path))){
            Storage::delete($path);
        }
        if($result){
            return response()->json(['success' => true, 'message' => translateMessageApi('email-send')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);

    }
}
