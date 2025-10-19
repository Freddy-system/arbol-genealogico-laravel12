<?php

declare(strict_types=1);

namespace App\Domain\Contracts\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Person;

interface PersonRepositoryInterface
{
    public function create(array $data): int;
    public function update(int $id, array $data): void;
    public function delete(int $id): void;
    public function find(int $id): ?Person;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function exists(int $id): bool;
    public function findMany(array $ids): array;
}
