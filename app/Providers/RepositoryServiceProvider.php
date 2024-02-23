<?php

namespace App\Providers;

use App\Interfaces\MuseumBranches\MuseumBranchesRepositoryInterface;
use App\Interfaces\User\UserInterface;
use App\Repositories\Branches\BranchRepository;
use App\Repositories\MuseumBranches\MuseumBranchRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\StudentInfoRepository;



use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {

    $this->app->bind(UserInterface::class, UserRepository::class);
    // $this->app->bind(MuseumBranchesRepositoryInterface::class, MuseumBranchRepository::class);





  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
