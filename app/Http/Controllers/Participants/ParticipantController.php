<?php

namespace App\Http\Controllers\Participants;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Participants\StoreParticipantRequest;
use App\Http\Requests\Participants\UpdateParticipantRequest;
use App\Services\Participants\ParticipantService;

class ParticipantController extends BaseController
{
 public function __construct(ParticipantService $service)
    {
        parent::__construct($service);
    }

    public function store(StoreParticipantRequest $request)
    {
        return $this->service->store($request->validated());
    }

    public function update(UpdateParticipantRequest $request)
    {
        return $this->service->update($request->validated(), $request->route('id'));
    }
}
