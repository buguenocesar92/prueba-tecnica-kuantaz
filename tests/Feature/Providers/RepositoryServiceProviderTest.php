<?php

namespace Tests\Feature\Providers;

use App\Providers\RepositoryServiceProvider;
use App\Repositories\External\BeneficiosRepository;
use App\Repositories\External\FichasRepository;
use App\Repositories\External\FiltrosRepository;
use App\Repositories\Interfaces\BeneficiosRepositoryInterface;
use App\Repositories\Interfaces\FichasRepositoryInterface;
use App\Repositories\Interfaces\FiltrosRepositoryInterface;
use Tests\TestCase;

class RepositoryServiceProviderTest extends TestCase
{
    public function test_register_binds_interfaces_to_implementations(): void
    {
        // Verificar que las interfaces están correctamente vinculadas
        $this->assertInstanceOf(
            BeneficiosRepository::class,
            $this->app->make(BeneficiosRepositoryInterface::class)
        );

        $this->assertInstanceOf(
            FiltrosRepository::class,
            $this->app->make(FiltrosRepositoryInterface::class)
        );

        $this->assertInstanceOf(
            FichasRepository::class,
            $this->app->make(FichasRepositoryInterface::class)
        );
    }

    public function test_boot_method_executes_without_errors(): void
    {
        // Crear una instancia del provider
        $provider = new RepositoryServiceProvider($this->app);
        
        // Verificar que el método boot se ejecuta sin errores
        $provider->boot();
        
        // Si llegamos aquí, el método boot se ejecutó correctamente
        $this->assertTrue(true);
    }

    public function test_provider_is_registered_in_application(): void
    {
        // Verificar que el provider está registrado en la aplicación
        $providers = $this->app->getLoadedProviders();
        
        $this->assertArrayHasKey(RepositoryServiceProvider::class, $providers);
    }
} 