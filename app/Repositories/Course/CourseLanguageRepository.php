<?php
namespace App\Repositories\Course;

use App\Interfaces\Course\CourseLanguageInterface;
use App\Traits\Course\CourseLanguageTrait;

class CourseLanguageRepository implements CourseLanguageInterface
{
  use CourseLanguageTrait;
}
