<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use Tests\TestCase;
use App\Application\Services\QueryService;
use App\Domain\Contracts\Repositories\ClosureRepositoryInterface;
use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use App\Domain\Contracts\Repositories\ParentageRepositoryInterface;

class QueryServiceTest extends TestCase
{
    public function test_ancestors_calls_repository(): void
    {
        $closure = $this->createMock(ClosureRepositoryInterface::class);
        $closure->expects($this->once())->method('ancestorsOf')->with(1, 2)->willReturn([['ancestor_id' => 5, 'depth' => 1]]);
        $people = $this->createMock(PersonRepositoryInterface::class);
        $parentages = $this->createMock(ParentageRepositoryInterface::class);
        $svc = new QueryService($closure, $people, $parentages);
        $this->assertSame([['ancestor_id' => 5, 'depth' => 1]], $svc->ancestors(1, 2));
    }

    public function test_descendants_calls_repository(): void
    {
        $closure = $this->createMock(ClosureRepositoryInterface::class);
        $closure->expects($this->once())->method('descendantsOf')->with(1, 3)->willReturn([['descendant_id' => 7, 'depth' => 1]]);
        $people = $this->createMock(PersonRepositoryInterface::class);
        $parentages = $this->createMock(ParentageRepositoryInterface::class);
        $svc = new QueryService($closure, $people, $parentages);
        $this->assertSame([['descendant_id' => 7, 'depth' => 1]], $svc->descendants(1, 3));
    }
}
