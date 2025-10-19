<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\ParentageRepositoryInterface;
use App\Models\Parentage;

class EloquentParentageRepository implements ParentageRepositoryInterface
{
    public function attach(int $parentId, int $childId, string $type): void
    {
        Parentage::query()->firstOrCreate(['parent_id' => $parentId, 'child_id' => $childId, 'type' => $type]);
    }

    public function detach(int $parentId, int $childId, string $type): void
    {
        Parentage::query()->where(['parent_id' => $parentId, 'child_id' => $childId, 'type' => $type])->delete();
    }

    public function parentsOf(int $childId): array
    {
        return Parentage::query()->where('child_id', $childId)->get()->toArray();
    }

    public function childrenOf(int $parentId): array
    {
        return Parentage::query()->where('parent_id', $parentId)->get()->toArray();
    }
}
