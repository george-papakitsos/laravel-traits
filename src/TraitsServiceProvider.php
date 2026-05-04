<?php

namespace GPapakitsos\LaravelTraits;

use Illuminate\Support\ServiceProvider;

class TraitsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laraveltraits.php' => config_path('laraveltraits.php'),
            ]);
        }

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laraveltraits');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/laraveltraits'),
        ]);
    }
}
