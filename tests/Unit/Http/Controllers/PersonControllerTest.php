<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\PersonController;
use App\Application\Services\PersonService;
use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Person;
use App\Http\Requests\StorePersonRequest;

class PersonControllerTest extends TestCase
{
    public function test_index_uses_repository_and_returns_json(): void
    {
        $repo = $this->createMock(PersonRepositoryInterface::class);
        $repo->method('paginate')->willReturn(new LengthAwarePaginator([], 0, 15));
        $svc = $this->createMock(PersonService::class);
        $controller = new PersonController($svc, $repo);
        $res = $controller->index(new Request());
        $this->assertSame(200, $res->getStatusCode());
    }

    public function test_store_uses_service_and_returns_resource(): void
    {
        $repo = $this->createMock(PersonRepositoryInterface::class);
        $svc = $this->createMock(PersonService::class);
        $svc->method('create')->with(['first_name' => 'A'])->willReturn(10);
        $person = new Person(['id' => 10, 'first_name' => 'A', 'last_name' => 'B']);
        $repo->method('find')->with(10)->willReturn($person);
        $controller = new PersonController($svc, $repo);
        $form = new class extends StorePersonRequest {
            public function validated($key = null, $default = null): array { return ['first_name' => 'A']; }
        };
        $res = $controller->store($form);
        $this->assertSame(201, $res->getStatusCode());
    }
}
