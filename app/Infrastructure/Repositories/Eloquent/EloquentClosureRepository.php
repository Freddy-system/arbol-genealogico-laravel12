<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;
use App\Models\PersonClosure;
use App\Models\Parentage;

class EloquentClosureRepository implements ClosureRepositoryInterface
{
    public function ensureSelf(int $personId): void
    {
        PersonClosure::query()->firstOrCreate(['ancestor_id' => $personId, 'descendant_id' => $personId], ['depth' => 0]);
    }

    public function link(int $ancestorId, int $descendantId, int $depth): void
    {
        PersonClosure::query()->updateOrCreate(['ancestor_id' => $ancestorId, 'descendant_id' => $descendantId], ['depth' => $depth]);
    }

    public function bulkLink(array $rows): void
    {
        foreach ($rows as $r) {
            PersonClosure::query()->updateOrCreate(['ancestor_id' => $r['ancestor_id'], 'descendant_id' => $r['descendant_id']], ['depth' => $r['depth']]);
        }
    }

    public function rebuildSubtree(int $rootId): void
    {
        $anc = $this->collectAncestorsByParentage($rootId);
        $sub = $this->collectSubtreeByParentage($rootId);
        $ancIds = array_keys($anc);
        $subIds = array_keys($sub);
        if ($subIds) {
            PersonClosure::query()->whereIn('descendant_id', $subIds)->delete();
        }
        foreach (array_unique(array_merge($ancIds, $subIds)) as $id) {
            PersonClosure::query()->updateOrCreate(['ancestor_id' => $id, 'descendant_id' => $id], ['depth' => 0]);
        }
        $rows = [];
        foreach ($anc as $aid => $up) {
            foreach ($sub as $sid => $down) {
                if ($aid !== $sid) {
                    $rows[] = ['ancestor_id' => $aid, 'descendant_id' => $sid, 'depth' => $up + $down];
                }
            }
        }
        $nodes = array_keys($sub);
        foreach ($nodes as $u) {
            $du = $sub[$u];
            foreach ($this->collectSubtreeByParentage($u) as $v => $dv) {
                if ($v !== $u) {
                    $rows[] = ['ancestor_id' => $u, 'descendant_id' => $v, 'depth' => $dv - $du];
                }
            }
        }
        $this->bulkLink($rows);
    }

    public function ancestorsOf(int $id, ?int $maxDepth = null): array
    {
        $q = PersonClosure::query()->where('descendant_id', $id)->where('depth', '>', 0);
        if ($maxDepth !== null) {
            $q->where('depth', '<=', $maxDepth);
        }
        return $q->get()->toArray();
    }

    public function descendantsOf(int $id, ?int $maxDepth = null): array
    {
        $q = PersonClosure::query()->where('ancestor_id', $id)->where('depth', '>', 0);
        if ($maxDepth !== null) {
            $q->where('depth', '<=', $maxDepth);
        }
        return $q->get()->toArray();
    }

    public function pathBetween(int $aId, int $bId): array
    {
        return [];
    }

    private function collectSubtreeByParentage(int $rootId): array
    {
        $out = [$rootId => 0];
        $q = [[$rootId, 0]];
        while ($q) {
            [$id, $d] = array_shift($q);
            $rows = Parentage::query()->where('parent_id', $id)->get(['child_id'])->toArray();
            foreach ($rows as $r) {
                $cid = (int) $r['child_id'];
                if (!array_key_exists($cid, $out)) {
                    $out[$cid] = $d + 1;
                    $q[] = [$cid, $d + 1];
                }
            }
        }
        return $out;
    }

    private function collectAncestorsByParentage(int $rootId): array
    {
        $out = [$rootId => 0];
        $q = [[$rootId, 0]];
        while ($q) {
            [$id, $d] = array_shift($q);
            $rows = Parentage::query()->where('child_id', $id)->get(['parent_id'])->toArray();
            foreach ($rows as $r) {
                $pid = (int) $r['parent_id'];
                if (!array_key_exists($pid, $out)) {
                    $out[$pid] = $d + 1;
                    $q[] = [$pid, $d + 1];
                }
            }
        }
        return $out;
    }
}
