<?php

namespace Pushmotion\PHPReports;

use Illuminate\Support\ServiceProvider;

class PHPReportsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->publishes([__DIR__.'/assets' => public_path('vendor/phpreports'),], 'public');
      $this->publishes([__DIR__.'/templates' => resource_path('views/vendor/phpreports')]);
      $this->publishes([__DIR__.'/config/config.php' => config_path('phpreports.php')]);

      $this->loadRoutesFrom(__DIR__.'/routes.php');

      if(is_dir(resource_path('views/vendor/phpreports/templates')))
        $this->loadViewsFrom(resource_path('views/vendor/phpreports/templates'), 'PHPReports');
      else
        $this->loadViewsFrom(__DIR__.'/templates', 'phpreports');


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if(!is_dir(config("phpreports.reportDir"))){
          \File::makeDirectory(config("phpreports.reportDir"), 0775, true);
        }
        if(!is_dir(config("phpreports.dashboardDir"))){
          \File::makeDirectory(config("phpreports.dashboardDir"), 0775, true);
        }
        if(!is_dir(config("phpreports.cacheDir"))){
          \File::makeDirectory(config("phpreports.cacheDir"), 0775, true);
        }

        $this->app->make('Pushmotion\PHPReports\PHPReportsController');
    }
}
