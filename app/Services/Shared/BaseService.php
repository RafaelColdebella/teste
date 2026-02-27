<?php

namespace App\Services\Shared;

use App\Repositories\Shared\BaseRepository;
use App\Services\Shared\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService implements BaseServiceInterface
{
    public const MESSAGE_STORE_SUCCESS = 'Registro criado com sucesso';
    public const MESSAGE_STORE_FAIL = 'Não foi possível criar o registro';
    public const MESSAGE_UPDATE_SUCCESS = 'Registro atualizado com sucesso';
    public const MESSAGE_UPDATE_FAIL = 'Não foi possível atualizar o registro';
    public const MESSAGE_DELETE_SUCCESS = 'Registro excluído';
    public const MESSAGE_DELETE_FAIL = 'Não foi possível excluir o registro';
    public const MESSAGE_MODEL_NOT_FOUND = 'Registro não encontrado';
    public const MESSAGE_GET_FAIL = 'Não foi possível encontrar o registro';

    public function __construct(
        protected BaseRepository $repository,
        protected string $resource,
        protected string $collection
    ) {}

    public function getMessage(string $key){
        return  __($key);
    }

    /**
     * Retrieves all resources of the given model by pagination
     *
     * @param Request $request
     * @param null|string|int $parentId
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function getAll(Request $request, null|string|int $parentId = null)
    {
        try {
            return new $this->collection($this->repository->getAll($request, $parentId));
        } catch (\Exception) {
            return new $this->collection(
                (new LengthAwarePaginator(
                    [],
                    0,
                    $request->get('perPage', config()->get('constants.pagination.perPage'))
                ))->appends($request->query())
            );
        }
    }

    /**
     * Retrieves all resources of the given model
     *
     * @param array $options
     * @param null|string|int $parentId
     * @return Collection
     */    
    public function getAllRaw(array $options = [], null|string|int $parentId = null)
    {
        try {
            return $this->repository->getAllRaw($options, $parentId);
        } catch (\Exception) {
            return new Collection([]);
        }
    }

    /**
     * Create a new resource
     *
     * @param array $data
     * @param null|string|int $parentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(array $data, null|string|int $parentId = null)
    {
        try {
            $this->repository->store($data, $parentId);

            return response()->json(['code' => Response::HTTP_CREATED, 'message' => $this->getMessage(Self::MESSAGE_STORE_SUCCESS)], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => self::getMessage('MESSAGE_STORE_FAIL'),
                'errorCode' => config('app.debug') ? $e->getCode() : null,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieves resource of the given model
     *
     * @param string|int $id
     * @param null|string|int $parentId
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function get(string|int $id, null|string|int $parentId = null)
    {
        try {
            return new $this->resource($this->repository->get($id, $parentId));
        } catch (ModelNotFoundException) {
            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => Self::getMessage('MESSAGE_MODEL_NOT_FOUND')], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => self::getMessage('MESSAGE_GET_FAIL'),
                'errorCode' => config('app.debug') ? $e->getCode() : null,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the given resource
     *
     * @param array $data
     * @param string|int $id
     * @param null|string|int $parentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        try {
            $affectedRecords = $this->repository->update($data, $id, $parentId);

            if ($affectedRecords === 0) {
                return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => Self::getMessage('MESSAGE_MODEL_NOT_FOUND')], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['code' => Response::HTTP_OK, 'message' => self::getMessage('MESSAGE_UPDATE_SUCCESS')], Response::HTTP_OK);
        } catch (ModelNotFoundException) {
            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => Self::getMessage('MESSAGE_MODEL_NOT_FOUND')], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => self::getMessage('MESSAGE_UPDATE_FAIL'),
                'errorCode' => config('app.debug') ? $e->getCode() : null,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete the given resource
     *
     * @param string|int $id
     * @param null|string|int $parentId
     * @return void|\Illuminate\Http\JsonResponse
     */
    public function delete(string|int $id, null|string|int $parentId = null)
    {
        try {
            $affectedRecords = $this->repository->delete($id, $parentId);

            if ($affectedRecords === 0) {
                return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => Self::getMessage('MESSAGE_MODEL_NOT_FOUND')], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['code' => Response::HTTP_NO_CONTENT, 'message' => self::getMessage('MESSAGE_DELETE_SUCCESS')], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException) {
            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => Self::getMessage('MESSAGE_MODEL_NOT_FOUND')], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => self::getMessage('MESSAGE_DELETE_FAIL'),
                'errorCode' => config('app.debug') ? $e->getCode() : null,
                'errorMessage' => config('app.debug') ? $e->getMessage() : null
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
