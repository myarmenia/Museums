<?php

namespace App\Interfaces\Museum;

interface MuseumRepositoryInterface
{
  public function getProject();

  public function createMuseum($data);

  public function createMuseumTranslations($data);

  public function getMuseumByUd($id);
  
}
