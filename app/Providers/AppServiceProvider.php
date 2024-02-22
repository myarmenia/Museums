<?php

namespace App\Providers;


use App\Interfaces\MuseumBranches\MuseumBranchesRepositoryInterface;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Repositories\MuseumBranches\MuseumBranchRepository;

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
    $this->app->bind(MuseumBranchesRepositoryInterface::class, MuseumBranchRepository::class);

  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();
  }
}
