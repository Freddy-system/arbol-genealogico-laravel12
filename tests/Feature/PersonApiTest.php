<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Person;

class PersonApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_list(): void
    {
        Person::factory()->count(3)->create();
        $res = $this->getJson('/api/persons');
        $res->assertOk()->assertJsonStructure(['data','links','meta']);
    }

    public function test_store_creates_person_and_returns_resource(): void
    {
        $payload = ['first_name'=>'Ana','last_name'=>'Lopez','gender'=>'F','document_id'=>'X1'];
        $res = $this->postJson('/api/persons', $payload);
        $res->assertCreated()->assertJsonFragment(['first_name'=>'Ana','last_name'=>'Lopez']);
        $this->assertDatabaseHas('persons', ['document_id'=>'X1']);
    }

    public function test_show_returns_not_found_when_absent(): void
    {
        $this->getJson('/api/persons/999')->assertNotFound();
    }

    public function test_update_modifies_person_and_returns_resource(): void
    {
        $p = Person::factory()->create(['first_name'=>'A']);
        $res = $this->putJson('/api/persons/'.$p->id, ['first_name'=>'B']);
        $res->assertOk()->assertJsonFragment(['first_name'=>'B']);
        $this->assertDatabaseHas('persons', ['id'=>$p->id,'first_name'=>'B']);
    }

    public function test_destroy_soft_deletes_person(): void
    {
        $p = Person::factory()->create();
        $this->deleteJson('/api/persons/'.$p->id)->assertNoContent();
        $this->assertSoftDeleted('persons', ['id'=>$p->id]);
    }
}
