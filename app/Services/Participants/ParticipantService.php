<?php

namespace App\Services\Participants;

use App\Http\Resources\Participants\ParticipantCollection;
use App\Http\Resources\Participants\ParticipantResource;
use App\Repositories\Participants\ParticipantRepository;
use App\Services\Shared\BaseService;
use App\Services\Participants\Contracts\ParticipantServiceInterface;

class ParticipantService extends BaseService implements ParticipantServiceInterface
{
    public function __construct(ParticipantRepository $repository)
    {
        parent::__construct($repository, ParticipantResource::class, ParticipantCollection::class);
    }
}
