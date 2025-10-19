<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Application\Services\PersonService;
use App\Domain\Contracts\Repositories\PersonRepositoryInterface;
use App\Http\Resources\PersonResource;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;

class PersonController extends Controller
{
    /**
     * Constructor
     * Desacopla el controlador de los detalles de acceso a datos y facilita pruebas y mantenimiento (principio de inversión de dependencias).
     */
    public function __construct(private PersonService $service, private PersonRepositoryInterface $repo)
    {
    }
    /**
     * Obtiene la lista paginada de personas leyendo per_page de la query.
     * Formatea la salida con PersonResource::collection.
     */
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->repo->paginate((int) $request->query('per_page', 15));
        return response()->json(PersonResource::collection($paginator)->response()->getData(true));
    }

    /**
     * Crea una persona con los datos proporcionados.
     * Formatea la salida con PersonResource.
     */
    public function store(StorePersonRequest $request): JsonResponse
    {
        $id = $this->service->create($request->validated());
        $p = $this->repo->find($id);
        return response()->json((new PersonResource($p))->resolve(), 201);
    }
    /**
     * Busca una persona por su id.
     * Si existe, devuelve la persona formateada con PersonResource.
     * Si no existe, responde que no fue encontrada.
     */
    public function show(int $person): JsonResponse
    {
        $p = $this->repo->find($person);
        if (!$p) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json((new PersonResource($p))->resolve());
    }

    /**
     * Actualiza una persona con los datos proporcionados.
     * Formatea la salida con PersonResource.
     */
    public function update(UpdatePersonRequest $request, int $person): JsonResponse
    {
        $this->service->update($person, $request->validated());
        $p = $this->repo->find($person);
        return response()->json((new PersonResource($p))->resolve());
    }

    /**
     * Elimina una persona por su id.
     * Responde con un estado 204 si se elimina correctamente.
     */
    public function destroy(int $person): JsonResponse
    {
        $this->service->delete($person);
        return response()->json([], 204);
    }
}
