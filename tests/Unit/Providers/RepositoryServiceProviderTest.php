<?php

declare(strict_types=1);

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Providers\RepositoryServiceProvider;
use App\Domain\Contracts\Repositories\{PersonRepositoryInterface, ParentageRepositoryInterface, MarriageRepositoryInterface, ClosureRepositoryInterface};
use App\Infrastructure\Repositories\Eloquent\{EloquentPersonRepository, EloquentParentageRepository, EloquentMarriageRepository, EloquentClosureRepository};
use App\Domain\Contracts\Services\{GenealogyQueryServiceInterface, KinshipServiceInterface};
use App\Application\Services\{QueryService, KinshipService};

class RepositoryServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->register(RepositoryServiceProvider::class);
    }

    public function test_repository_bindings(): void
    {
        $this->assertInstanceOf(EloquentPersonRepository::class, $this->app->make(PersonRepositoryInterface::class));
        $this->assertInstanceOf(EloquentParentageRepository::class, $this->app->make(ParentageRepositoryInterface::class));
        $this->assertInstanceOf(EloquentMarriageRepository::class, $this->app->make(MarriageRepositoryInterface::class));
        $this->assertInstanceOf(EloquentClosureRepository::class, $this->app->make(ClosureRepositoryInterface::class));
    }

    public function test_service_bindings(): void
    {
        $this->assertInstanceOf(QueryService::class, $this->app->make(GenealogyQueryServiceInterface::class));
        $this->assertInstanceOf(KinshipService::class, $this->app->make(KinshipServiceInterface::class));
    }
}
