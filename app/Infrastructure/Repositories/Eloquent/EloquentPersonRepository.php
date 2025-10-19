<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use App\Models\Person;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPersonRepository implements PersonRepositoryInterface
{
    public function create(array $data): int
    {
        return Person::query()->create($data)->id;
    }

    public function update(int $id, array $data): void
    {
        Person::query()->whereKey($id)->update($data);
    }

    public function delete(int $id): void
    {
        Person::query()->whereKey($id)->delete();
    }

    public function find(int $id): ?\App\Models\Person
    {
        return Person::query()->find($id);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Person::query()->paginate($perPage);
    }

    public function exists(int $id): bool
    {
        return Person::query()->whereKey($id)->exists();
    }

    public function findMany(array $ids): array
    {
        return Person::query()->whereIn('id', $ids)->get()->toArray();
    }
}
