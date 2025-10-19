<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Contracts\Services\KinshipServiceInterface;

class KinshipController extends Controller
{
    /**
     * Constructor
     * Desacopla el controlador de los detalles de acceso a datos y facilita pruebas y mantenimiento (principio de inversión de dependencias).
     */
    public function __construct(private KinshipServiceInterface $service)
    {
    }

    /**
     * Obtiene la relación entre dos personas.
     * Lee personA e personB de la query para identificar las personas.
     * Devuelve la relación entre ellas.
     */
    public function relation(Request $request): JsonResponse
    {
        $a = (int) $request->query('personA');
        $b = (int) $request->query('personB');
        $data = $this->service->relationBetween($a, $b);
        return response()->json($data);
    }
}
