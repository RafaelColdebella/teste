<?php

namespace App\Services\Participants;

use App\Http\Resources\Participants\BalanceResource;
use App\Http\Resources\Participants\ParticipantCollection;
use App\Http\Resources\Participants\ParticipantResource;
use App\Repositories\Participants\ParticipantRepository;
use App\Services\Shared\BaseService;
use App\Services\Participants\Contracts\ParticipantServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipantService extends BaseService implements ParticipantServiceInterface
{
    public function __construct(ParticipantRepository $repository)
    {
        parent::__construct($repository, ParticipantResource::class, ParticipantCollection::class);
    }

    public function updateBalance(array $data, string|int|null $parentId = null) {
        try {
            if(!isset($data['newBalance'])) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Request malformada, não foi possível atualizar.'
                ], Response::HTTP_BAD_REQUEST);
            }

            $updated = $this->repository->updateBalance($data['newBalance'], $parentId);

            if($updated > 0) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'message' => 'Saldo atualizado com sucesso.',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Não foi possível atualizar o saldo.',
                'errorMessage' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllReceivers(Request $request) {
        try {
            return new ParticipantCollection(
                $this->repository->getAllReceivers($request)
            );
        } catch(\Exception) {
            return new ParticipantCollection(
                (new LengthAwarePaginator(
                    [],
                    0,
                    $request->get('perPage', config()->get('constants.pagination.perPage'))
                ))
            );
        }  
    }

    public function getAllPayers(Request $request) {
        try {
            return new ParticipantCollection(
                $this->repository->getAllPayers($request)
            );
        } catch(\Exception) {
            return new ParticipantCollection(
                (new LengthAwarePaginator(
                    [],
                    0,
                    $request->get('perPage', config()->get('constants.pagination.perPage'))
                ))
            );
        }  
    }

    public function getBalance(string|int $participantId) {
        try {
            $participant = $this->repository->get($participantId);

            return new BalanceResource(
                $this->repository->getBalance($participant->id)
            );
        } catch(\Exception) {
            return new BalanceResource(
                (new LengthAwarePaginator(
                    [],
                    0,
                    1
                ))
            );
        }  
    }
}
