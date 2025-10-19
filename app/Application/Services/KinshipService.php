<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;
use App\Domain\Contracts\Services\KinshipServiceInterface;

class KinshipService implements KinshipServiceInterface
{
    public function __construct(private ClosureRepositoryInterface $closure)
    {
    }

    public function relationBetween(int $aId, int $bId): array
    {
        $path = $this->closure->pathBetween($aId, $bId);
        return ['degree' => count($path) ? count($path) - 1 : 0, 'name' => ''];
    }
}
