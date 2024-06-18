<?php

namespace App\Providers;

use App\Adapters\PrivatBankCurrencyRateAdapter;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Repositories\CurrencyRateRepository;
use App\Repositories\SubscriberRepository;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerAdapters();
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
    private function registerAdapters(): void
    {
        $this->app->singleton(CurrencyRateAdapterInterface::class, PrivatBankCurrencyRateAdapter::class);
    }

    /**
     * @return void
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(CurrencyRateRepositoryInterface::class, CurrencyRateRepository::class);
        $this->app->singleton(SubscriberRepositoryInterface::class, SubscriberRepository::class);
    }
}
