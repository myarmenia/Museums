<?php

namespace App\Providers;


use App\Interfaces\Course\LanguagesInterface;
use App\Interfaces\Lesson\LessonRepositoryInterface as LessonLessonRepositoryInterface;
use App\Interfaces\User\UserInterface;
use App\Interfaces\User\StudentInfoInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\StudentInfoRepository;
use App\Repositories\User\UserCourseLanguagesRepository;


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {

    $this->app->bind(UserInterface::class, UserRepository::class);
    $this->app->bind(LanguagesInterface::class, UserCourseLanguagesRepository::class);
    $this->app->bind(StudentInfoInterface::class, StudentInfoRepository::class);

  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
