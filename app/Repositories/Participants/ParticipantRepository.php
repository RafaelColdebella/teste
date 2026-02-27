<?php

namespace App\Repositories\Participants;

use App\Models\Participants\Participant;
use App\Repositories\Shared\BaseRepository;
use App\Repositories\Participants\Contracts\ParticipantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParticipantRepository extends BaseRepository implements ParticipantRepositoryInterface
{
    public function __construct(Participant $model)
    {
        parent::__construct($model);
    }

    public function updateBalance(float $balance, string|int|null $parentId = null) {
       $query = $this->model->query();

       if (is_string($parentId) && Str::isUuid($parentId)) {
            $query->where('public_id', $parentId);
        } else {
            $query->where('id', $parentId);
        }

        return $query->update([
            'balance' => $balance
        ]);
    }

    public function getAllReceivers(Request $request) {
        $perPage = $request->get('perPage') ?? 15;

        return $this->model->query()->where('type', 'receiver')->paginate($perPage);
    }

    public function getAllPayers(Request $request) {
        $perPage = $request->get('perPage') ?? 15;

        return $this->model->query()->where('type', 'payer')->paginate($perPage);
    }

    public function getBalance(string|int $participantId) {
        return $this->model->query()->where('id', $participantId)->first();
    }
}
