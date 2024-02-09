<?php

namespace App\Providers;

use App\Inretfaces\Lesson\LessonRepositoryInterface;
use App\Interfaces\Course\CourseLanguageInterface;
use App\Interfaces\Course\GetStudentCourseInterface;
use App\Interfaces\Course\LanguagesInterface;
use App\Interfaces\Lesson\LessonRepositoryInterface as LessonLessonRepositoryInterface;
use App\Interfaces\Task\TaskRepositoryInterface;
use App\Interfaces\User\UserCourseLanguagesInterface;
use App\Interfaces\User\UserCurrentLessonInterface;
use App\Interfaces\User\UserInterface;
use App\Interfaces\User\StudentInfoInterface;
use App\Repositories\Course\CourseLanguageRepository;
use App\Repositories\Course\GetStudentCourseRepository;
use App\Repositories\Lesson\LessonRepository;
use App\Repositories\User\UserCurrentLessonRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\StudentInfoRepository;
use App\Repositories\User\UserCourseLanguagesRepository;
use App\Repositories\Task\TaskRepository;

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
    $this->app->bind(UserCourseLanguagesInterface::class, UserCourseLanguagesRepository::class);
    $this->app->bind(UserCurrentLessonInterface::class, UserCurrentLessonRepository::class);
    $this->app->bind(StudentInfoInterface::class, StudentInfoRepository::class);
    $this->app->bind(GetStudentCourseInterface::class, GetStudentCourseRepository::class);
    $this->app->bind(CourseLanguageInterface::class, CourseLanguageRepository::class);
    $this->app->bind(LessonLessonRepositoryInterface::class,LessonRepository::class);
    $this->app->bind(TaskRepositoryInterface::class,TaskRepository::class);

  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
