<?php

namespace App\Providers;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Repositories\SubscriberRepository;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerRepositories();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @return void
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(SubscriberRepositoryInterface::class, SubscriberRepository::class);
    }
}
