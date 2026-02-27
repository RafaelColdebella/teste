<?php

namespace App\Repositories\Participants;

use App\Models\Participants\Participant;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Participants\Contracts\ParticipantRepositoryInterface;
use Illuminate\Http\Request;

class ParticipantRepository extends BaseRepository implements ParticipantRepositoryInterface
{
    public function __construct(Participant $model)
    {
        parent::__construct($model);
    }
}
