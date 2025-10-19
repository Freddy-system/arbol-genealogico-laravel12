<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\GenealogyController;
use App\Domain\Contracts\Services\GenealogyQueryServiceInterface;
use Illuminate\Http\Request;

class GenealogyControllerTest extends TestCase
{
    public function test_endpoints_return_json(): void
    {
        $svc = $this->createMock(GenealogyQueryServiceInterface::class);
        $svc->method('ancestors')->willReturn(['ok'=>true]);
        $svc->method('descendants')->willReturn(['ok'=>true]);
        $svc->method('tree')->willReturn(['ancestors'=>[], 'descendants'=>[]]);
        $controller = new GenealogyController($svc);
        $this->assertSame(200, $controller->ancestors(1, new Request())->getStatusCode());
        $this->assertSame(200, $controller->descendants(1, new Request())->getStatusCode());
        $this->assertSame(200, $controller->tree(1, new Request())->getStatusCode());
    }
}
