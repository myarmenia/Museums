<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {

    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);

    $verticalMenuMuseumJson = file_get_contents(base_path('resources/menu/verticalMenuMuseum.json'));
    $verticalMenuMuseumData = json_decode($verticalMenuMuseumJson);

    // Share all menuData to all the views
    \View::share('menuData', ['v_menu' => $verticalMenuData, 'v_menu_museum' => $verticalMenuMuseumData]);
    // \View::share('menuData', [$verticalMenuData]);

  }
}
