<?php
namespace App\Repositories\Course;

use App\Interfaces\Course\LanguagesInterface;
use App\Traits\Course\CourseLanguagesTrait;

class LanguagesRepository implements LanguagesInterface
{
  use CourseLanguagesTrait;
}
