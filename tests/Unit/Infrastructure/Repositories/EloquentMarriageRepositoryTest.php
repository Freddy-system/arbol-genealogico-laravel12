<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Repositories\Eloquent\EloquentMarriageRepository;
use App\Models\Person;

class EloquentMarriageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_end_marriage(): void
    {
        $repo = new EloquentMarriageRepository();
        $a = Person::factory()->create();
        $b = Person::factory()->create();
        $id = $repo->create($a->id, $b->id, '2020-01-01');
        $this->assertDatabaseHas('marriages', ['id'=>$id,'status'=>'active']);
        $repo->end($id, '2024-01-01', 'divorced');
        $this->assertDatabaseHas('marriages', ['id'=>$id,'status'=>'divorced','end_date'=>'2024-01-01']);
        $this->assertIsArray($repo->activeFor($a->id) ?? []);
    }
}
