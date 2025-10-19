<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

interface ParentageRepositoryInterface
{
    public function attach(int $parentId, int $childId, string $type): void;
    public function detach(int $parentId, int $childId, string $type): void;
    public function parentsOf(int $childId): array;
    public function childrenOf(int $parentId): array;
}
