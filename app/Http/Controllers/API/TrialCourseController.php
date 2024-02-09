<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailTrialCourse;
use App\Services\API\TrialCourseService;
use Illuminate\Http\Request;
use Mail;


class TrialCourseController extends Controller
{
    protected $trialCourseService;

    public function __construct(TrialCourseService $trialCourseService)
    {
        $this->trialCourseService = $trialCourseService;
    }

    public function trialCourse(Request $request)
    {
        Mail::send(new SendEmailTrialCourse($request->all()));

       return response()->json(['message' => translateMessageApi('email-send')]);
    }
}
