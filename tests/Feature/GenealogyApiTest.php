<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Person;

class GenealogyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ancestors_and_descendants_endpoints(): void
    {
        $p = Person::factory()->create();
        $this->getJson('/api/genealogy/'.$p->id.'/ancestors')->assertOk();
        $this->getJson('/api/genealogy/'.$p->id.'/descendants')->assertOk();
        $this->getJson('/api/genealogy/'.$p->id.'/tree')->assertOk();
    }
}
