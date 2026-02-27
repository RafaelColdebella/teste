<?php

namespace App\Http\Controllers\Participants;

use App\Http\Controllers\Shared\BaseController;
use App\Http\Requests\Participants\StoreParticipantRequest;
use App\Http\Requests\Participants\UpdateBalanceRequest;
use App\Http\Requests\Participants\UpdateParticipantRequest;
use App\Services\Participants\ParticipantService;
use Symfony\Component\HttpFoundation\Request;

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

    public function updateBalance(UpdateBalanceRequest $request) {
        return $this->service->updateBalance($request->validated(), $request->route('participant'));
    }

    public function getAllReceivers(Request $request) {
        return $this->service->getAllReceivers($request);
    }

    public function getAllPayers(Request $request) {
        return $this->service->getAllPayers($request);
    }

    public function getBalance(Request $request) {
        return $this->service->getBalance($request->route('participant'));
    }
}
