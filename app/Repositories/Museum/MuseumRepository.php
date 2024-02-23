<?php
namespace App\Repositories\Museum;
use App\Interfaces\Museum\MuseumRepositoryInterface;
use App\Models\Museum;
use App\Models\MuseumTranslation;



class MuseumRepository implements MuseumRepositoryInterface{
    public function getProject()
    {
        return Museum::get();
    }

    public function createMuseum($data)
    {
        return Museum::create($data);
    }

    public function createMuseumTranslations($data)
    {
        return MuseumTranslation::insert($data);
    }

    public function getMuseumByUd($id)
    {
        return Museum::with(['user','translations', 'phones', 'images', 'links', 'region'])->find($id);
    }

    public function updateMuseum($data, $id)
    {
        return Museum::find($id)->update($data);
    }

    public function updateMuseumTranslations($data, $lang, $id)
    {
        return MuseumTranslation::where(['lang' => $lang, 'museum_id' => $id])->update($data);
    }
    
}