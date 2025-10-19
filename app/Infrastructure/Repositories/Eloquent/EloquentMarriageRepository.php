<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\MarriageRepositoryInterface;
use App\Models\Marriage;

class EloquentMarriageRepository implements MarriageRepositoryInterface
{
    public function create(int $spouseAId, int $spouseBId, string $startDate): int
    {
        return Marriage::query()->create(['spouse_a_id' => $spouseAId, 'spouse_b_id' => $spouseBId, 'start_date' => $startDate, 'status' => 'active'])->id;
    }

    public function end(int $marriageId, ?string $endDate, string $status): void
    {
        Marriage::query()->whereKey($marriageId)->update(['end_date' => $endDate, 'status' => $status]);
    }

    public function activeFor(int $personId): ?array
    {
        $m = Marriage::query()->where(function ($q) use ($personId) {
            $q->where('spouse_a_id', $personId)->orWhere('spouse_b_id', $personId);
        })->where('status', 'active')->first();
        return $m ? $m->toArray() : null;
    }
}
