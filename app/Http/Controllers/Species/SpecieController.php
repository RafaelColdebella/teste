<?php

namespace App\Http\Controllers\Species;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Species\StoreSpecieRequest;
use App\Http\Requests\Species\UpdateSpecieRequest;
use App\Services\Shared\BaseService;
use App\Services\Species\SpecieService;
use Illuminate\Http\Request;

class SpecieController extends BaseController
{
    public function __construct(SpecieService $service)
    {
        return parent::__construct($service);
    }

    public function store(StoreSpecieRequest $request) {
        return $this->service->store($request->validated());
    }

    public function update(UpdateSpecieRequest $request) {
        return $this->service->update($request->validated(), $request->route('species'));
    }
}
