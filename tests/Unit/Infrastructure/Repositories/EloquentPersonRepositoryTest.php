<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Infrastructure\Repositories\Eloquent\EloquentPersonRepository;
use App\Models\Person;

class EloquentPersonRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_and_paginate(): void
    {
        $repo = new EloquentPersonRepository();
        $id = $repo->create(['first_name'=>'A','last_name'=>'B']);
        $this->assertTrue($repo->exists($id));
        $found = $repo->find($id);
        $this->assertInstanceOf(Person::class, $found);
        $repo->update($id, ['first_name'=>'C']);
        $this->assertDatabaseHas('persons', ['id'=>$id,'first_name'=>'C']);
        $page = $repo->paginate(10);
        $this->assertSame(1, $page->total());
        $repo->delete($id);
        $this->assertSoftDeleted('persons', ['id'=>$id]);
    }
}
