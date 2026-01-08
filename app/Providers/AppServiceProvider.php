<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind interfaces to implementations
        $this->app->bind(
            \App\Contracts\MessageClassifierInterface::class,
            \App\Services\MessageClassifier::class
        );

        $this->app->bind(
            \App\Contracts\MessageFactoryInterface::class,
            \App\Services\MessageFactory::class
        );

        $this->app->bind(
            \App\Contracts\MessageRepositoryInterface::class,
            \App\Repositories\JsonMessageRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
