<?php

namespace App\Providers;

use App\Adapters\CurrencyRateAdapter;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
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
        $this->registerServices();
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

    /**
     * @return void
     */
    private function registerServices(): void
    {
        $this->app->bind(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
    }
}
