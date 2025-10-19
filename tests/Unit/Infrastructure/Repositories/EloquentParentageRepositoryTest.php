<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Repositories\Eloquent\EloquentParentageRepository;
use App\Models\Person;

class EloquentParentageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_attach_detach_and_queries(): void
    {
        $repo = new EloquentParentageRepository();
        $parent = Person::factory()->create();
        $child = Person::factory()->create();
        $repo->attach($parent->id, $child->id, 'father');
        $this->assertCount(1, $repo->parentsOf($child->id));
        $this->assertCount(1, $repo->childrenOf($parent->id));
        $repo->detach($parent->id, $child->id, 'father');
        $this->assertCount(0, $repo->parentsOf($child->id));
    }
}
