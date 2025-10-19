<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Person;

class GenealogyOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_closure_updates_on_add_parentage(): void
    {
        $p = Person::factory()->create();
        $c = Person::factory()->create();

        $this->postJson('/api/relationships/parentage', [
            'parent_id' => $p->id,
            'child_id' => $c->id,
            'type' => 'father',
        ])->assertCreated();

        $res = $this->getJson('/api/genealogy/descendants/'.$p->id)->assertOk();
        $ids = array_map(fn ($r) => (int) $r['descendant_id'], $res->json());
        $this->assertContains($c->id, $ids);
    }

    public function test_move_subtree_rebuilds_closure(): void
    {
        $a = Person::factory()->create();
        $b = Person::factory()->create();
        $c = Person::factory()->create();
        $d = Person::factory()->create();

        $this->postJson('/api/relationships/parentage', ['parent_id' => $a->id, 'child_id' => $b->id, 'type' => 'father'])->assertCreated();
        $this->postJson('/api/relationships/parentage', ['parent_id' => $b->id, 'child_id' => $c->id, 'type' => 'father'])->assertCreated();

        $this->patchJson('/api/relationships/move-subtree', [
            'node_id' => $b->id,
            'new_parent_id' => $d->id,
            'type' => 'father',
        ])->assertOk();

        $ancC = $this->getJson('/api/genealogy/ancestors/'.$c->id)->assertOk()->json();
        $ancIds = array_map(fn ($r) => (int) $r['ancestor_id'], $ancC);
        $this->assertContains($d->id, $ancIds);
        $this->assertNotContains($a->id, $ancIds);
    }

    public function test_delete_person_removes_entire_subtree(): void
    {
        $p = Person::factory()->create();
        $c1 = Person::factory()->create();
        $g1 = Person::factory()->create();

        $this->postJson('/api/relationships/parentage', ['parent_id' => $p->id, 'child_id' => $c1->id, 'type' => 'father'])->assertCreated();
        $this->postJson('/api/relationships/parentage', ['parent_id' => $c1->id, 'child_id' => $g1->id, 'type' => 'father'])->assertCreated();

        $this->deleteJson('/api/persons/'.$p->id)->assertNoContent();

        $this->assertSoftDeleted('persons', ['id' => $p->id]);
        $this->assertSoftDeleted('persons', ['id' => $c1->id]);
        $this->assertSoftDeleted('persons', ['id' => $g1->id]);
    }

    public function test_bfs_and_dfs_and_descendants_count(): void
    {
        $root = Person::factory()->create();
        $c1 = Person::factory()->create();
        $c2 = Person::factory()->create();
        $g11 = Person::factory()->create();
        $g21 = Person::factory()->create();

        $this->postJson('/api/relationships/parentage', ['parent_id' => $root->id, 'child_id' => $c1->id, 'type' => 'father'])->assertCreated();
        $this->postJson('/api/relationships/parentage', ['parent_id' => $root->id, 'child_id' => $c2->id, 'type' => 'father'])->assertCreated();
        $this->postJson('/api/relationships/parentage', ['parent_id' => $c1->id, 'child_id' => $g11->id, 'type' => 'father'])->assertCreated();
        $this->postJson('/api/relationships/parentage', ['parent_id' => $c2->id, 'child_id' => $g21->id, 'type' => 'father'])->assertCreated();

        $bfs = $this->getJson('/api/genealogy/bfs/'.$root->id.'?direction=desc')->assertOk()->json();
        $dfs = $this->getJson('/api/genealogy/dfs/'.$root->id.'?direction=desc')->assertOk()->json();
        $count = $this->getJson('/api/genealogy/descendants/'.$root->id.'/count')->assertOk()->json('count');

        $expected = [$c1->id, $c2->id, $g11->id, $g21->id];
        $this->assertEquals(4, $count);
        $this->assertEmpty(array_diff($expected, array_map(fn ($r) => (int) $r['id'], $bfs)));
        $this->assertEmpty(array_diff($expected, array_map(fn ($r) => (int) $r['id'], $dfs)));
        foreach ($bfs as $r) {
            $this->assertGreaterThan(0, (int) $r['depth']);
        }
        foreach ($dfs as $r) {
            $this->assertGreaterThan(0, (int) $r['depth']);
        }
    }
}
