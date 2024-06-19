<?php

namespace App\Providers;

use App\Adapters\ABankCurrencyRateAdapter;
use App\Adapters\PrivatBankCurrencyRateAdapter;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Repositories\CurrencyRateRepository;
use App\Repositories\SubscriberRepository;
use App\Services\CurrencyRateService;
use GuzzleHttp\Client;
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
    private function registerServices(): void
    {
        $this->app->singleton(CurrencyRateService::class, function ($app) {
            $client = new Client();
            $providers = [
                new ABankCurrencyRateAdapter($client),
                new PrivatBankCurrencyRateAdapter($client),
            ];

            return new CurrencyRateService($providers);
        });
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
