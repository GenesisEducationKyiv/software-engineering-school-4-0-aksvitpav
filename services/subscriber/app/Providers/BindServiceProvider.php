<?php

namespace App\Providers;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\RabbitMQServiceInterface;
use App\Repositories\SubscriberRepository;
use App\Services\RabbitMQService;
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
        $this->app->bind(RabbitMQServiceInterface::class, RabbitMQService::class);
    }
}
