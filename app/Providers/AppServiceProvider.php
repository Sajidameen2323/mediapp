<?php

namespace App\Providers;

use App\Models\LabTestRequest;
use App\Observers\LabTestRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        LabTestRequest::observe(LabTestRequestObserver::class);
        
        // Register the test command
        if ($this->app->runningInConsole()) {
            $this->commands([
               
            ]);
        }
    }
}
