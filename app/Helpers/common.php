<?php

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
            'virtual_tour' => 'Վեբ-սայթ',
            'web_site' => 'Վիրտուալ էքսկուրսիա',
        ];

        return $arr[$key];

    }
}
