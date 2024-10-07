<?php

namespace App\Providers;


use App\Interfaces\MuseumBranches\MuseumBranchesRepositoryInterface;
use App\Models\OtherService;
use App\Models\PurchasedItem;
use App\Models\TicketQr;
use App\Observers\PurchasedItemObserver;
use App\Observers\TicketQrObserver;
use App\Repositories\MuseumBranches\MuseumBranchRepository;
use App\Repositories\OtherService\OtherServiceRepository;
use App\Services\API\OtherService\OtherServService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {

    $this->app->bind(MuseumBranchesRepositoryInterface::class, MuseumBranchRepository::class);
    $this->app->bind(OtherServService::class, function ($app): OtherServService {
      return   new OtherServService($app->make(OtherServiceRepository::class));
  });

  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();
    PurchasedItem::observe(PurchasedItemObserver::class);
    TicketQr::observe(classes: TicketQrObserver::class);

  }
}
