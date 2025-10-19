<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Contracts\Services\GenealogyQueryServiceInterface;

class GenealogyController extends Controller
{
    /**
     * Constructor
     * Inyecta el servicio de consultas genealógicas.
     */
    public function __construct(private GenealogyQueryServiceInterface $service)
    {
    }

    /**
     * Devuelve los ancestros de una persona.
     * Lee maxDepth e include de la query para controlar profundidad y datos completos.
     */
    public function ancestors(int $id, Request $request): JsonResponse
    {
        $include = $request->query('include') === 'full';
        $data = $this->service->ancestors($id, $request->query('maxDepth') ? (int) $request->query('maxDepth') : null, $include);
        return response()->json($data);
    }

    /**
     * Devuelve los descendientes de una persona.
     * Lee maxDepth e include de la query para controlar profundidad y datos completos.
     */
    public function descendants(int $id, Request $request): JsonResponse
    {
        $include = $request->query('include') === 'full';
        $data = $this->service->descendants($id, $request->query('maxDepth') ? (int) $request->query('maxDepth') : null, $include);
        return response()->json($data);
    }

    /**
     * Devuelve el árbol mixto de una persona.
     * Lee depth, direction e include para construir la respuesta.
     */
    public function tree(int $id, Request $request): JsonResponse
    {
        $depth = (int) $request->query('depth', 1);
        $direction = (string) $request->query('direction', 'both');
        $include = $request->query('include') === 'full';
        $data = $this->service->tree($id, $depth, $direction, $include);
        return response()->json($data);
    }

    public function bfs(int $id, Request $request): JsonResponse
    {
        $direction = (string) $request->query('direction', 'desc');
        return response()->json($this->service->bfs($id, $direction));
    }

    public function dfs(int $id, Request $request): JsonResponse
    {
        $direction = (string) $request->query('direction', 'desc');
        return response()->json($this->service->dfs($id, $direction));
    }

    public function descendantsCount(int $id): JsonResponse
    {
        return response()->json(['count' => $this->service->descendantsCount($id)]);
    }
}
