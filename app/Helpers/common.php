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