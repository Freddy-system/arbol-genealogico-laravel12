<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\Services\RelationshipService;

class RelationshipController extends Controller
{
    /**
     * Constructor
     * Desacopla el controlador de los detalles de acceso a datos y facilita pruebas y mantenimiento (principio de inversión de dependencias).
     */
    public function __construct(private RelationshipService $service)
    {
    }

    /**
     * Crea una parentesco entre dos personas.
     * Lee parent_id, child_id e type de la query para identificar las personas y el tipo de parentesco.
     * Devuelve un estado 201 si se crea correctamente.
     */
    public function storeParentage(Request $request): JsonResponse
    {
        $this->service->addParentage((int) $request->input('parent_id'), (int) $request->input('child_id'), (string) $request->input('type'));
        return response()->json([], 201);
    }

    /**
     * Elimina una parentesco entre dos personas.
     * Lee parent_id, child_id e type de la query para identificar las personas y el tipo de parentesco.
     * Devuelve un estado 204 si se elimina correctamente.
     */
    public function deleteParentage(Request $request): JsonResponse
    {
        $this->service->removeParentage((int) $request->input('parent_id'), (int) $request->input('child_id'), (string) $request->input('type'));
        return response()->json([], 204);
    }

    /**
     * Crea una union entre dos personas.
     * Lee spouse_a_id, spouse_b_id e start_date de la query para identificar las personas y la fecha de inicio.
     * Devuelve un estado 201 si se crea correctamente.
     */
    public function storeMarriage(Request $request): JsonResponse
    {
        $id = $this->service->createMarriage((int) $request->input('spouse_a_id'), (int) $request->input('spouse_b_id'), (string) $request->input('start_date'));
        return response()->json(['id' => $id], 201);
    }

    /**
     * Termina una union entre dos personas.
     * Lee spouse_a_id, spouse_b_id e start_date de la query para identificar las personas y la fecha de inicio.
     * Devuelve un estado 204 si se termina correctamente.
     */
    public function endMarriage(int $id, Request $request): JsonResponse
    {
        $this->service->endMarriage($id, $request->input('end_date'), (string) $request->input('status'));
        return response()->json([]);
    }

    public function moveSubtree(Request $request): JsonResponse
    {
        $this->service->moveSubtree((int) $request->input('node_id'), (int) $request->input('new_parent_id'), (string) $request->input('type'));
        return response()->json([]);
    }
}
