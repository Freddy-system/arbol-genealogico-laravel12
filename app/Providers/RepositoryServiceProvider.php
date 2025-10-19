<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Contracts\Repositories\{PersonRepositoryInterface, ParentageRepositoryInterface, MarriageRepositoryInterface, ClosureRepositoryInterface};
use App\Infrastructure\Repositories\Eloquent\{EloquentPersonRepository, EloquentParentageRepository, EloquentMarriageRepository, EloquentClosureRepository};
use App\Domain\Contracts\Services\{GenealogyQueryServiceInterface, KinshipServiceInterface};
use App\Application\Services\{QueryService, KinshipService};

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PersonRepositoryInterface::class, EloquentPersonRepository::class);
        $this->app->bind(ParentageRepositoryInterface::class, EloquentParentageRepository::class);
        $this->app->bind(MarriageRepositoryInterface::class, EloquentMarriageRepository::class);
        $this->app->bind(ClosureRepositoryInterface::class, EloquentClosureRepository::class);
        $this->app->bind(GenealogyQueryServiceInterface::class, QueryService::class);
        $this->app->bind(KinshipServiceInterface::class, KinshipService::class);
    }

    public function boot(): void
    {
    }
}
