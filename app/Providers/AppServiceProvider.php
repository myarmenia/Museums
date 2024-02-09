<?php

namespace App\Providers;

use App\Interfaces\News\NewsCategoryInterface;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Repositories\News\NewsCategoryRepository;
use App\Repositories\Project\ProjectRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
    $this->app->bind(NewsCategoryInterface::class, NewsCategoryRepository::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();
  }
}
