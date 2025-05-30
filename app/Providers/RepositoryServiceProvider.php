<?php

namespace App\Providers;

use App\Repositories\External\BeneficiosRepository;
use App\Repositories\External\FichasRepository;
use App\Repositories\External\FiltrosRepository;
use App\Repositories\Interfaces\BeneficiosRepositoryInterface;
use App\Repositories\Interfaces\FichasRepositoryInterface;
use App\Repositories\Interfaces\FiltrosRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar interfaces con sus implementaciones concretas
        $this->app->bind(BeneficiosRepositoryInterface::class, BeneficiosRepository::class);
        $this->app->bind(FiltrosRepositoryInterface::class, FiltrosRepository::class);
        $this->app->bind(FichasRepositoryInterface::class, FichasRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 