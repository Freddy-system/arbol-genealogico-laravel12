<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;

class PersonService
{
    public function __construct(private PersonRepositoryInterface $persons, private ?ClosureRepositoryInterface $closure = null)
    {
    }

    public function create(array $data): int
    {
        return $this->persons->create($data);
    }

    public function update(int $id, array $data): void
    {
        $this->persons->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($this->closure) {
            $desc = $this->closure->descendantsOf($id);
            foreach ($desc as $row) {
                $did = (int) ($row['descendant_id'] ?? 0);
                if ($did > 0) {
                    $this->persons->delete($did);
                }
            }
        }
        $this->persons->delete($id);
    }
}
