<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use Tests\TestCase;
use App\Application\Services\PersonService;
use App\Domain\Contracts\Repositories\PersonRepositoryInterface;

class PersonServiceTest extends TestCase
{
    public function test_create_delegates_to_repository(): void
    {
        $repo = $this->createMock(PersonRepositoryInterface::class);
        $repo->expects($this->once())->method('create')->with(['a'=>1])->willReturn(10);
        $svc = new PersonService($repo);
        $this->assertSame(10, $svc->create(['a'=>1]));
    }

    public function test_update_delegates_to_repository(): void
    {
        $repo = $this->createMock(PersonRepositoryInterface::class);
        $repo->expects($this->once())->method('update')->with(5, ['a'=>2]);
        $svc = new PersonService($repo);
        $svc->update(5, ['a'=>2]);
        $this->assertTrue(true);
    }

    public function test_delete_delegates_to_repository(): void
    {
        $repo = $this->createMock(PersonRepositoryInterface::class);
        $repo->expects($this->once())->method('delete')->with(7);
        $svc = new PersonService($repo);
        $svc->delete(7);
        $this->assertTrue(true);
    }
}
