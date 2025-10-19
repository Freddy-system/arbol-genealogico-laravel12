<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Services;

interface KinshipServiceInterface
{
    public function relationBetween(int $aId, int $bId): array;
}
