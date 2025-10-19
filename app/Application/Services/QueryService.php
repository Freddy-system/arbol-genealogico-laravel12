<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;
use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use App\Domain\Contracts\Repositories\ParentageRepositoryInterface;
use App\Domain\Contracts\Services\GenealogyQueryServiceInterface;
use App\Http\Resources\PersonResource;

class QueryService implements GenealogyQueryServiceInterface
{
    public function __construct(private ClosureRepositoryInterface $closure, private PersonRepositoryInterface $persons, private ParentageRepositoryInterface $parentages)
    {
    }

    public function ancestors(int $personId, ?int $maxDepth = null, bool $includeFull = false): array
    {
        $rows = $this->closure->ancestorsOf($personId, $maxDepth);
        if (!$includeFull) {
            return $rows;
        }
        $ids = array_values(array_unique(array_map(fn ($r) => $r['ancestor_id'], $rows)));
        $people = $this->indexById($this->persons->findMany($ids));
        return array_map(function ($r) use ($people) {
            $p = $people[$r['ancestor_id']] ?? null;
            return ['depth' => $r['depth'], 'person' => $p ? (new PersonResource((object) $p))->resolve() : null];
        }, $rows);
    }

    public function descendants(int $personId, ?int $maxDepth = null, bool $includeFull = false): array
    {
        $rows = $this->closure->descendantsOf($personId, $maxDepth);
        if (!$includeFull) {
            return $rows;
        }
        $ids = array_values(array_unique(array_map(fn ($r) => $r['descendant_id'], $rows)));
        $people = $this->indexById($this->persons->findMany($ids));
        return array_map(function ($r) use ($people) {
            $p = $people[$r['descendant_id']] ?? null;
            return ['depth' => $r['depth'], 'person' => $p ? (new PersonResource((object) $p))->resolve() : null];
        }, $rows);
    }

    public function tree(int $personId, int $depth = 1, string $direction = 'both', bool $includeFull = false): array
    {
        $a = $direction === 'desc' ? [] : $this->ancestors($personId, $depth, $includeFull);
        $d = $direction === 'asc' ? [] : $this->descendants($personId, $depth, $includeFull);
        return ['ancestors' => $a, 'descendants' => $d];
    }

    public function bfs(int $personId, string $direction = 'desc'): array
    {
        $visited = [];
        $order = [];
        $q = [[$personId, 0]];
        $visited[$personId] = true;
        while ($q) {
            [$id, $depth] = array_shift($q);
            if ($depth > 0) {
                $order[] = ['id' => $id, 'depth' => $depth];
            }
            $next = $direction === 'anc' ? $this->parentIds($id) : $this->childIds($id);
            foreach ($next as $nid) {
                if (!isset($visited[$nid])) {
                    $visited[$nid] = true;
                    $q[] = [$nid, $depth + 1];
                }
            }
        }
        return $order;
    }

    public function dfs(int $personId, string $direction = 'desc'): array
    {
        $order = [];
        $visited = [];
        $stack = [[$personId, 0, 0]];
        while ($stack) {
            [$id, $depth, $state] = array_pop($stack);
            if ($state === 0) {
                $visited[$id] = true;
                if ($depth > 0) {
                    $order[] = ['id' => $id, 'depth' => $depth];
                }
                $children = $direction === 'anc' ? $this->parentIds($id) : $this->childIds($id);
                for ($i = count($children) - 1; $i >= 0; $i--) {
                    $nid = $children[$i];
                    if (!isset($visited[$nid])) {
                        $stack[] = [$nid, $depth + 1, 0];
                    }
                }
            }
        }
        return $order;
    }

    public function descendantsCount(int $personId): int
    {
        $rows = $this->closure->descendantsOf($personId);
        $c = 0;
        foreach ($rows as $r) {
            if ((int) ($r['depth'] ?? 0) > 0) {
                $c++;
            }
        }
        return $c;
    }

    private function indexById(array $rows): array
    {
        $out = [];
        foreach ($rows as $r) {
            $out[$r['id']] = $r;
        }
        return $out;
    }

    private function childIds(int $id): array
    {
        $rows = $this->parentages->childrenOf($id);
        $out = [];
        foreach ($rows as $r) {
            $out[] = (int) $r['child_id'];
        }
        return $out;
    }

    private function parentIds(int $id): array
    {
        $rows = $this->parentages->parentsOf($id);
        $out = [];
        foreach ($rows as $r) {
            $out[] = (int) $r['parent_id'];
        }
        return $out;
    }
}
