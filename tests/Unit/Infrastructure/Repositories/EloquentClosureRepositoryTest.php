<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Repositories\Eloquent\EloquentClosureRepository;
use App\Models\Person;
use App\Models\PersonClosure;

class EloquentClosureRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_ensure_self_and_basic_queries(): void
    {
        $repo = new EloquentClosureRepository();
        $p = Person::factory()->create();
        $repo->ensureSelf($p->id);
        $this->assertDatabaseHas('person_closure', ['ancestor_id'=>$p->id,'descendant_id'=>$p->id,'depth'=>0]);
        $this->assertSame([], $repo->ancestorsOf($p->id));
        $this->assertSame([], $repo->descendantsOf($p->id));
    }

    public function test_link_and_queries(): void
    {
        $repo = new EloquentClosureRepository();
        $a = Person::factory()->create();
        $b = Person::factory()->create();
        $repo->ensureSelf($a->id);
        $repo->ensureSelf($b->id);
        $repo->link($a->id, $b->id, 1);
        $anc = $repo->ancestorsOf($b->id);
        $desc = $repo->descendantsOf($a->id);
        $this->assertNotEmpty($anc);
        $this->assertNotEmpty($desc);
    }

    public function test_bulk_link(): void
    {
        $repo = new EloquentClosureRepository();
        $a = Person::factory()->create();
        $b = Person::factory()->create();
        $c = Person::factory()->create();
        $repo->ensureSelf($a->id);
        $repo->ensureSelf($b->id);
        $repo->ensureSelf($c->id);
        $repo->bulkLink([
            ['ancestor_id'=>$a->id,'descendant_id'=>$b->id,'depth'=>1],
            ['ancestor_id'=>$a->id,'descendant_id'=>$c->id,'depth'=>2],
        ]);
        $this->assertCount(2, $repo->descendantsOf($a->id, null));
    }
}
