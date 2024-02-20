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

    
}