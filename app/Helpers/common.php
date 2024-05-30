<?php

use App\Models\Chat;
use App\Models\Country;
use App\Models\EducationalProgram;
use App\Models\Museum;
use App\Models\MuseumStaff;
use App\Models\TicketType;
use App\Models\TicketUnitedSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

function translateMessageApi($message, $lang=null)
{
    $lang = $lang ? $lang : session('languages', 'am');
    $translation = new Translation($lang);

    return $translation->get($message);
}

function getProjectDescription($translation)
{
    $lang = session('languages');
    $descriptoin = $translation->where('lang', $lang)->first()->description;

    return $descriptoin;
}

function getCourseLanguagesDescription($translation)
{
    $lang = session('languages');
    $descriptoin = $translation->where('lang', $lang)->first()->description;

    return $descriptoin;
}

function getProjectDescriptionForAdmin($translation, $lang)
{
    $descriptoin = $translation->where('lang', $lang)->first()->description;

    return $descriptoin;
}


function roles_intersect($data){

  $auth_roles = Auth::user()->roleNames();
  array_push($auth_roles, 'all');

  return array_intersect($auth_roles, $data);
}

if(!function_exists('languages')){
  function languages(){

      return [
        'am','ru','en'
      ];

  }

}

if(!function_exists('languagesName')){
    function languagesName($key){
        $arr = [
            'am' => 'հայերեն',
            'ru' => 'ռուսերեն',
            'en' => 'անգլերեն',
        ];

        return $arr[$key];

    }
}

if(!function_exists('getLinkType')){
    function getLinkType(){

        return [
            'facebook', 'instagram', 'web_site', 'virtual_tour',
        ];

    }
}

if(!function_exists('getLinkNames')){
    function getLinkNames($key){

        $arr = [
            'facebook' => 'Ֆեյսբուք',
            'instagram' => 'Ինստագրամ',
            'virtual_tour' => 'Վիրտուալ էքսկուրսիա',
            'web_site' => 'Վեբ-սայթ',
        ];

        return $arr[$key];

    }
}

if(!function_exists('museumPhoneCount')){
    function museumPhoneCount(){
        return [
            'phone1', 'phone2', 'phone3',
        ];
    }
}

if(!function_exists('haveMuseumAdmin')){
    function haveMuseumAdmin()
    {
        if(auth()->user()->roles()->get()->where('name', 'museum_admin')->count()) {
            return true;
        };

        return false;
    }
}

if(!function_exists('isSuperAdmin')){
    function isSuperAdmin()
    {
        if(auth()->user()->roles()->get()->where('name', 'super_admin')->count()) {
            return true;
        };

        return false;
    }
}

if(!function_exists('haveMuseum')){
    function haveMuseum()
    {
        if($museum = Museum::where('user_id', auth()->id())->first()) {
            return $museum->id;
        };

        return false;
    }
}

if(!function_exists('museumAccessId')){
  function museumAccessId()
  {

      return Auth::user()->museum_staff_user ? Auth::user()->museum_staff_user->museum_id : false;
  }
}


if(!function_exists('getAuthMuseumId')){
    function getAuthMuseumId()
    {
        $authId = auth()->id();

        if($museum = MuseumStaff::where('user_id', $authId)->first()) {
            return $museum->museum_id;
        };

        return false;
    }
}

if (!function_exists('allRoles')) {
  function allRoleNames()
  {
    return Role::all()->pluck('name', 'name')->toArray();
  }

}

if (!function_exists('museumEducationalPrograms')) {
  function museumEducationalPrograms()
  {

    return museumAccessId() ? EducationalProgram::where('museum_id', museumAccessId())->get() : [];
  }

}

if (!function_exists('ticketType')) {
  function ticketType($type)
  {
   return TicketType::where('name', $type)->first();
  }

}

if (!function_exists('unitedTicketSettings')) {
  function unitedTicketSettings()
  {
    return TicketUnitedSetting::first();
  }

}
if (!function_exists('notifications')) {
  function notifications()
  {
    $user = auth('api')->user();
    $notification = $user->unreadNotifications;
    return $notification;
  }

}

function generateToken()
{
  return md5(rand(1, 8) . microtime());
}

if (!function_exists('getCountry')) {
    function getCountry($key)
    {
      return Country::where('key', $key)->first();
    }

}

if (!function_exists('getAllCountries')) {
  function getAllCountries()
  {
      $countries = Country::all();

      $key = $countries->where('key', 'am')->first()->id;
      $newPosition = 0;
      $countries = $countries->sortBy(function ($country) use ($key, $newPosition) {
          return $country->getKey() === $key ? $newPosition : $country->getKey();
      });

      return $countries;
  }

}


if (!function_exists('getAge')) {
    function getAge($birthdate)
    {
        $birthdate = Carbon::parse($birthdate);
        $currentDate = Carbon::now();
        $age = $birthdate->diffInYears($currentDate);

        return $age;
    }

}

if (!function_exists('getReportTimes')) {
  function getReportTimes()
  {
    $currentDate = Carbon::now();

    return [
          "first_trimester" => ['start_date' => $currentDate->startOfYear()->format('Y-m-d'), 'end_date' => $currentDate->startOfYear()->addMonths(3)->subDay()->format('Y-m-d')],
          "second_trimester" => ['start_date' => $currentDate->startOfYear()->addMonths(3)->format('Y-m-d'), 'end_date' => $currentDate->startOfYear()->addMonths(6)->subDay()->format('Y-m-d')],
          "third_trimester" => ['start_date' => $currentDate->startOfYear()->addMonths(6)->format('Y-m-d'), 'end_date' => $currentDate->startOfYear()->addMonths(9)->subDay()->format('Y-m-d')],
          "fourth_trimester" => ['start_date' => $currentDate->startOfYear()->addMonths(9)->format('Y-m-d'), 'end_date' => $currentDate->endOfYear()->format('Y-m-d')],
          "first_semester" => ['start_date' => $currentDate->startOfYear()->format('Y-m-d'), 'end_date' => $currentDate->startOfYear()->addMonths(6)->subDay()->format('Y-m-d')],
          "second_semester" => ['start_date' => $currentDate->startOfYear()->addMonths(6)->format('Y-m-d'), 'end_date' => $currentDate->endOfYear()->format('Y-m-d')],
          "per_year" => ['start_date' => $currentDate->startOfYear()->format('Y-m-d'), 'end_date' => $currentDate->endOfYear()->format('Y-m-d')],
    ];
  }

}

if (!function_exists('getReportTimesForAdmin')) {
  function getReportTimesForAdmin()
  {

    return [
        "first_trimester" => '1 եռամսյակ',
        "second_trimester" => '2 եռամսյակ',
        "third_trimester" => '3 եռամսյակ',
        "fourth_trimester" => '4 եռամսյակ',
        "first_semester" => '1 կիսամյակ',
        "second_semester" => '2 կիսամյակ',
        "per_year" => 'տարեկան',
      ];
  }

}

if (!function_exists('getAgeRanges')) {
  function getAgeRanges()
  {

    return [
      "junior" => ['start_age' => 0, 'end_age' => 18],
      "young" => ['start_age' => 19, 'end_age' => 60],
      "old" => ['start_age' => 61, 'end_age' => 100],

    ];
  }

}

if (!function_exists('getMuseum')) {
  function getMuseum($id)
  {
    return Museum::find($id);
  }

}


if (!function_exists('reportResult')) {
  function reportResult($data)
  {

      $keys = ['standart', 'discount', 'free', 'united', 'subscription', 'event', 'corporative', 'educational', 'guide', 'canceled', 'product'];
      $sums = [];

      foreach ($data as $array) {
          foreach ($keys as $key) {
              if (isset($array[$key]) && is_array($array[$key])) {
                  foreach ($array[$key] as $subKey => $value) {
                    if (is_numeric($value)) {
                        $sums[$key][$subKey] = isset($sums[$key][$subKey]) ? $sums[$key][$subKey] + intval($value) : intval($value);
                    }
                  }
              }
          }
      }

      return $sums;
  }

}


if (!function_exists('reportTypes')) {
  function reportTypes()
  {
    return ['standart', 'discount', 'free', 'united', 'subscription', 'event', 'corporative', 'educational', 'guide', 'canceled', 'product'];
  }
}

if (!function_exists('ticketColors')) {
  function ticketColors()
  {
      return [
        'standart' => '#fff',
        'discount' => '#E8D71E',
        'free' => '#16EA91',
        'united' => 'linear-gradient(to bottom right, #9c78b1, #16acea7d)',
        'subscription' => '#16ACEA',
        'event' => '#9C78B1'

      ];
  }
}

if (!function_exists('ticketTitles')) {
  function ticketTitles()
  {
      return [
        'standart' => 'ստանդարտ տոմս стандартный билет standart ticket',
        'discount' => 'զեղչված տոմս билет со скидкой discounted ticket',
        'free' => 'անվճար տոմս бесплатный билет free ticket',
        'united' => 'միջոցառման անվանումը  название события event name',
        'subscription' => 'աբոնեմենտ  абонемент abonement',
        'event' => 'միջոցառման անվանումը  название события event name'
      ];
  }
}

if (!function_exists('getTranslateTicketTitl')) {
  function getTranslateTicketTitl($title)
  {
      $titles = [
        'standart' => 'Ստանդարտ',
        'discount' => 'Զեղչված',
        'free' => 'Անվճար',
        'subscription' => 'Աբոնեմենտ',
        'united' => 'Միասնական',
        'educational' => 'Կրթական',
        'event' => 'Միջոցառում',
        'corporative' => 'Կորպորատիվ',
        'guide' => 'Էքսկուրսիա',
        'product' => 'Ապրանք'

      ];

      //check have $title in $titles
      if (isset($titles[$title])) {
          return $titles[$title];
      } else {
        return $title;
      }
  }

  if (!function_exists('getMonths')) {
    function getMonths()
    {
      return ['Հունվար', 'Փետրվար', 'Մարտ', 'Ապրիլ', 'Մայիս', 'Հունիս', 'Հուլիս', 'Օգօստոս', 'Սեպտեմբեր', 'Հոկտեմբեր', 'Նոյեմբեր','Դեկտեմբեր'];
    }

  }

}

if (!function_exists('getAuthUserRoleInterface')) {
  function getAuthUserRoleInterface()
  {

    if (auth()->user()->roles()->get()->where('interface', 'admin')->count()) {
      return true;
    };

    return false;
  }

}

if (!function_exists('museumHaveUnreadMessage')) {
  function museumHaveUnreadMessage()
  {

    $museumId = getAuthMuseumId() ? getAuthMuseumId() : NULL;

    $unreadMessage = Chat::where('museum_id', $museumId)->where('read', 0)->first();

    if($unreadMessage) {
      return true;
    }

    return false;
  }

}






