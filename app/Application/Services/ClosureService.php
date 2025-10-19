<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;

class ClosureService
{
    public function __construct(private ClosureRepositoryInterface $closure)
    {
    }

    public function ensureSelf(int $personId): void
    {
        $this->closure->ensureSelf($personId);
    }

    public function rebuildSubtree(int $rootId): void
    {
        $this->closure->rebuildSubtree($rootId);
    }
}
