<?php

namespace App\Interfaces\News;

interface NewsCategoryInterface
{
  public function createNewsCategory($data);

  public function addTranslation($id, $translate);
  
  public function getCategoryListAdmin();
  
}
