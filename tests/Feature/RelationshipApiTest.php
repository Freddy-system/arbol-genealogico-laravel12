<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Person;

class RelationshipApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_parentage_endpoints(): void
    {
        $parent = Person::factory()->create();
        $child = Person::factory()->create();

        $this->postJson('/api/relationships/parentage', [
            'parent_id' => $parent->id,
            'child_id' => $child->id,
            'type' => 'father',
        ])->assertCreated();

        $this->deleteJson('/api/relationships/parentage', [
            'parent_id' => $parent->id,
            'child_id' => $child->id,
            'type' => 'father',
        ])->assertNoContent();
    }

    public function test_marriage_endpoints(): void
    {
        $a = Person::factory()->create();
        $b = Person::factory()->create();

        $res = $this->postJson('/api/relationships/marriage', [
            'spouse_a_id' => $a->id,
            'spouse_b_id' => $b->id,
            'start_date' => '2020-01-01',
        ])->assertCreated();

        $id = $res->json('id');

        $this->patchJson('/api/relationships/marriage/'.$id.'/end', [
            'end_date' => '2024-01-01',
            'status' => 'divorced',
        ])->assertOk();
    }
}
