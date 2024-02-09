<?php

class Translation
{
    protected $translation;

    public function __construct($lang)
    {
        $path = resource_path("ln/{$lang}.json");
        $this->translation = json_decode(file_get_contents($path), true);
    }

   public function get($key)
   {
        return $this->translation[$key] ?? $key;
   }
}
