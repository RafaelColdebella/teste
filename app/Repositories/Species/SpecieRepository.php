<?php

namespace App\Repositories\Species;

use App\Models\Species\Specie;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Species\Contracts\SpecieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecieRepository extends BaseRepository implements SpecieRepositoryInterface 
{
    public function __construct(Specie $model)
    {
        parent::__construct($model);
    }

    public function update(array $data, string|int $id, null|string|int $parentId = null)
    {
        $query = $this->model->query();

        if (is_string($id) && Str::isUuid($id)) {
            $query->where('public_id', $id);
        } else {
            $query->where('id', $id);
        }

        return $query->update($data);
    }
}
