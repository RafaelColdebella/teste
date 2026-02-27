<?php

namespace App\Services\Species;

use App\Http\Resources\Species\SpecieCollection;
use App\Http\Resources\Species\SpecieResource;
use App\Repositories\Species\SpecieRepository;
use App\Services\Shared\BaseService;
use App\Services\Species\Contracts\SpecieServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class SpecieService extends BaseService implements SpecieServiceInterface
{
    public function __construct(SpecieRepository $repository)
    {
        parent::__construct($repository, SpecieResource::class, SpecieCollection::class);
    }

    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        try {
            $updated = $this->repository->update($data, $id, $parentId);

            if ($updated === 0) {
                return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado para atualização.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['code' => Response::HTTP_OK, 'message' => 'Espécie atualizada com sucesso!'], Response::HTTP_OK);
        } catch (ModelNotFoundException) {
            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado para atualização.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Falha ao tentar atualizar.',
                'errorCode' => config('app.debug') ? $e->getCode() : null,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
