<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

interface MarriageRepositoryInterface
{
    public function create(int $spouseAId, int $spouseBId, string $startDate): int;
    public function end(int $marriageId, ?string $endDate, string $status): void;
    public function activeFor(int $personId): ?array;
}
