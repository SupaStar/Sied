<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    // get all data from menu.json file
    $verticalMenuJson = file_get_contents(base_path('resources/json/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);
    $verticalbMenuJson = file_get_contents(base_path('resources/json/verticalMenuB.json'));
    $verticalbMenuData = json_decode($verticalbMenuJson);
    $horizontalMenuJson = file_get_contents(base_path('resources/json/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson);
    $verticalMenuJson = file_get_contents(base_path('resources/json/verticalMenuAdmin.json'));
    $verticalAdminMenuData = json_decode($verticalMenuJson);

    // Share all menuData to all the views
    \View::share('menuData', [$verticalMenuData, $horizontalMenuData, $verticalbMenuData,$verticalAdminMenuData]);
  }
}
