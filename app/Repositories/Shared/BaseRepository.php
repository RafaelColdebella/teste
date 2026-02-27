<?php

namespace App\Repositories\Shared;

use App\Repositories\Shared\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model) {
    }

    /**
     * Retrieves all resources of the given model by pagination
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(Request $request, null|string|int $parentId = null)
    {
        $query = $this->model->query();
        $traits = class_uses($this->model);

        if ($traits && is_array($traits)) {
            foreach ($traits as $trait) {
                $reflection = new \ReflectionClass($trait);

                if ($reflection->getShortName() === 'Filterable') {
                    $query->ignoreRequest(['page', 'perPage', 'sort', 'sortDir', 'q', 'with'])
                        ->filter();
                    break;
                }
            }
        }

        if ($request->has('with')) {
            $query->with($request->get('with'));
        }

        return $query->paginate($request->get('perPage', config()->get('constants.pagination.perPage')));
    }

    /**
     * Retrieves all resources of the given model
     *
     * @param array $options
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRaw(array $options = [], null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if (array_key_exists('with', $options) && isset($options['with'])) {
            $query->with($options['with']);
        }

        return $query->get();
    }

    /**
     * Create a new resource
     *
     * @param array $data
     * @param null|string|int $parentId
     * @return Model;
     */
    public function store(array $data, null|string|int $parentId = null)
    {
        return $this->model->create($data);
    }

    /**
     * Retrieves resource of the given model
     *
     * @param string|int $id
     * @param null|string|int $parentId
     * @return Model|null
     */
    public function get(string|int $id, null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if (request()->has('with')) {
            $query->with(request()->get('with'));
        }

        if (is_string($id) && Str::isUuid($id)) {
            return $query->where('public_id', $id)->firstOrFail();
        }

        return $query->findOrFail($id);
    }

    /**
     * Update the given resource
     *
     * @param array $data
     * @param string|int $id
     * @param null|string|int $parentId
     * @return Model;
     *
     * @throws ModelNotFoundException
     */
    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if (request()->has('with')) {
            $query->with(request()->get('with'));
        }

        if (is_string($id) && Str::isUuid($id)) {
            $query->where('public_id', $id);
        } else {
            $query->where('id', $id);
        }

        return $query->update($data);
    }

    /**
     * Delete the given resource
     *
     * @param string|int $id
     * @param null|string|int $parentId
     * @return mixed
     *
     * @throws ModelNotFoundException
     */
    public function delete(string|int $id, null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if (request()->has('with')) {
            $query->with(request()->get('with'));
        }

        if (is_string($id) && Str::isUuid($id)) {
            $query->where('public_id', $id);
        } else {
            $query->where('id', $id);
        }

        return $query->delete();
    }
}
