<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\KinshipController;
use App\Domain\Contracts\Services\KinshipServiceInterface;
use Illuminate\Http\Request;

class KinshipControllerTest extends TestCase
{
    public function test_relation_returns_json(): void
    {
        $svc = $this->createMock(KinshipServiceInterface::class);
        $svc->expects($this->once())->method('relationBetween')->with(1, 2)->willReturn(['degree'=>1,'name'=>'']);
        $controller = new KinshipController($svc);
        $req = Request::create('/api/kinship', 'GET', ['personA'=>1,'personB'=>2]);
        $res = $controller->relation($req);
        $this->assertSame(200, $res->getStatusCode());
    }
}
