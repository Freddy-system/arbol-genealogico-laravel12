<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

interface ClosureRepositoryInterface
{
    public function ensureSelf(int $personId): void;
    public function link(int $ancestorId, int $descendantId, int $depth): void;
    public function bulkLink(array $rows): void;
    public function rebuildSubtree(int $rootId): void;
    public function ancestorsOf(int $id, ?int $maxDepth = null): array;
    public function descendantsOf(int $id, ?int $maxDepth = null): array;
    public function pathBetween(int $aId, int $bId): array;
}
