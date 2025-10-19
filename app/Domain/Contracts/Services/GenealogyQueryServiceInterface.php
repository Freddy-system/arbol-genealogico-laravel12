<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Services;

interface GenealogyQueryServiceInterface
{
    public function ancestors(int $personId, ?int $maxDepth = null, bool $includeFull = false): array;
    public function descendants(int $personId, ?int $maxDepth = null, bool $includeFull = false): array;
    public function tree(int $personId, int $depth = 1, string $direction = 'both', bool $includeFull = false): array;
    public function bfs(int $personId, string $direction = 'desc'): array;
    public function dfs(int $personId, string $direction = 'desc'): array;
    public function descendantsCount(int $personId): int;
}
