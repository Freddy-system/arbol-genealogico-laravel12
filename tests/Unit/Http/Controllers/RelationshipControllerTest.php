<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\RelationshipController;
use App\Application\Services\RelationshipService;
use Illuminate\Http\Request;

class RelationshipControllerTest extends TestCase
{
    public function test_store_and_delete_parentage(): void
    {
        $svc = $this->createMock(RelationshipService::class);
        $svc->expects($this->once())->method('addParentage')->with(1, 2, 'father');
        $svc->expects($this->once())->method('removeParentage')->with(1, 2, 'father');
        $controller = new RelationshipController($svc);
        $req = Request::create('/api/relationships/parentage', 'POST', ['parent_id'=>1,'child_id'=>2,'type'=>'father']);
        $res = $controller->storeParentage($req);
        $this->assertSame(201, $res->getStatusCode());
        $reqDel = Request::create('/api/relationships/parentage', 'DELETE', ['parent_id'=>1,'child_id'=>2,'type'=>'father']);
        $resDel = $controller->deleteParentage($reqDel);
        $this->assertSame(204, $resDel->getStatusCode());
    }

    public function test_store_and_end_marriage(): void
    {
        $svc = $this->createMock(RelationshipService::class);
        $svc->expects($this->once())->method('createMarriage')->with(1, 2, '2020-01-01')->willReturn(10);
        $svc->expects($this->once())->method('endMarriage')->with(10, '2024-01-01', 'divorced');
        $controller = new RelationshipController($svc);
        $req = Request::create('/api/relationships/marriage', 'POST', ['spouse_a_id'=>1,'spouse_b_id'=>2,'start_date'=>'2020-01-01']);
        $res = $controller->storeMarriage($req);
        $this->assertSame(201, $res->getStatusCode());
        $reqEnd = Request::create('/api/relationships/marriage/10/end', 'PATCH', ['end_date'=>'2024-01-01','status'=>'divorced']);
        $resEnd = $controller->endMarriage(10, $reqEnd);
        $this->assertSame(200, $resEnd->getStatusCode());
    }
}
