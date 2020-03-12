<?php

namespace Quote\Laravel;

use Illuminate\Support\ServiceProvider;
use Quote\Laravel\Services\QuoteService;
use Quote\Quote;

class QuoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('quote', function ($app) {
            return new QuoteService(config('quote.apiKey'), config('quote.cache'));
        });

        $this->app->bind('Quote\Larave\Service\QuoteService', 'quote');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/quote.php' => config_path('quote.php')]);
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'quote'
        ];
    }
}
