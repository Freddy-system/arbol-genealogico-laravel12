<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\Repositories\ParentageRepositoryInterface;
use App\Domain\Contracts\Repositories\MarriageRepositoryInterface;
use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;

class RelationshipService
{
    public function __construct(
        private ParentageRepositoryInterface $parentages,
        private MarriageRepositoryInterface $marriages,
        private ClosureRepositoryInterface $closure
    ) {
    }

    public function addParentage(int $parentId, int $childId, string $type): void
    {
        $this->parentages->attach($parentId, $childId, $type);
        $this->closure->ensureSelf($parentId);
        $this->closure->ensureSelf($childId);
        $anc = array_merge([["ancestor_id" => $parentId, "depth" => 0]], $this->closure->ancestorsOf($parentId));
        $desc = array_merge([["descendant_id" => $childId, "depth" => 0]], $this->closure->descendantsOf($childId));
        $rows = [];
        foreach ($anc as $a) {
            foreach ($desc as $d) {
                $rows[] = [
                    'ancestor_id' => $a['ancestor_id'],
                    'descendant_id' => $d['descendant_id'],
                    'depth' => (int) $a['depth'] + 1 + (int) $d['depth'],
                ];
            }
        }
        if ($rows) {
            $this->closure->bulkLink($rows);
        }
    }

    public function removeParentage(int $parentId, int $childId, string $type): void
    {
        $this->parentages->detach($parentId, $childId, $type);
    }

    public function createMarriage(int $aId, int $bId, string $startDate): int
    {
        return $this->marriages->create($aId, $bId, $startDate);
    }

    public function endMarriage(int $marriageId, ?string $endDate, string $status): void
    {
        $this->marriages->end($marriageId, $endDate, $status);
    }

    public function moveSubtree(int $nodeId, int $newParentId, string $type): void
    {
        foreach ($this->closure->descendantsOf($nodeId) as $d) {
            if ((int) ($d['descendant_id'] ?? 0) === $newParentId) {
                return;
            }
        }
        foreach ($this->parentages->parentsOf($nodeId) as $p) {
            if (($p['type'] ?? '') === $type) {
                $this->parentages->detach((int) $p['parent_id'], $nodeId, $type);
            }
        }
        $this->addParentage($newParentId, $nodeId, $type);
        $this->closure->rebuildSubtree($nodeId);
    }
}
