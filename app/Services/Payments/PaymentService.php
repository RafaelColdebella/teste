<?php

namespace App\Services\Payments;

use App\Http\Resources\Payments\PaymentCollection;
use App\Http\Resources\Payments\PaymentResource;
use App\Http\Resources\Payments\TotalsCollection;
use App\Http\Resources\Payments\TotalsResource;
use App\Repositories\Participants\ParticipantRepository;
use App\Repositories\Payments\PaymentRepository;
use App\Repositories\Species\SpecieRepository;
use App\Services\Payments\Contracts\PaymentServiceInterface;
use App\Services\Shared\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentService extends BaseService implements PaymentServiceInterface
{
    public function __construct(
        PaymentRepository $repository,
        protected SpecieRepository $specieRepo,
        protected ParticipantRepository $participantRepo
    ) {
        parent::__construct($repository, PaymentResource::class, PaymentCollection::class);
    }

    public function store(array $data, string|int|null $parentId = null)
    {
        try {
            if (empty($data['paid_by']) || empty($data['received_by']) || empty($data['specie_id'])) {
                return response()->json(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Request malformada, impossível criar.',], Response::HTTP_BAD_REQUEST);
            }

            $data['specie_id'] = $this->specieRepo->get($data['specie_id'])->id;

            $receiver = $this->participantRepo->get($data['received_by']);

            $payer = $this->participantRepo->get($data['paid_by']);

            if ($receiver->type !== 'receiver') {
                return response()->json([
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'Recebedor com tipo inválido.'
                ], Response::HTTP_CONFLICT);
            }

            if ($payer->type !== 'payer') {
                return response()->json([
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'Pagante com tipo inválido.'
                ], Response::HTTP_CONFLICT);
            }

            $data['received_by'] = $receiver->id;
            $data['paid_by'] = $payer->id;

            $this->repository->store($data);

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

    public function finish(string|int $id)
    {
        try {
            DB::beginTransaction();

            if (!is_null($id)) {
                $payment = $this->repository->get($id);
            }

            if ($payment->status === 'pending') {
                $receiver = $this->participantRepo->get($payment->received_by);
                $payer = $this->participantRepo->get($payment->paid_by);

                if ($payer->balance >= $payment->value) {
                    $specie = $this->specieRepo->get($payment->specie_id);

                    $tax = $payment->value * ($specie->tax / 100);

                    if ($receiver || $payer) {
                        $receiverBalance['newBalance'] = $receiver->balance + ($payment->value - $tax);

                        $payerBalance['newBalance'] = $payer->balance - ($payment->value - $tax);

                        $this->participantRepo->updateBalance($receiverBalance['newBalance'], $receiver->id);

                        $this->participantRepo->updateBalance($payerBalance['newBalance'], $payer->id);
                    }

                    $newData = [
                        'transaction_tax' => $tax,
                        'status' => 'paid'
                    ];

                    $updated = $this->repository->update($newData, $id);

                    if ($updated === 0) {
                        return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Nenhum registro atualizado!'], Response::HTTP_NOT_FOUND);
                    }

                    DB::commit();
                } else {
                    return response()->json([
                        'code' => Response::HTTP_CONFLICT,
                        'message' => 'O pagante não possui saldo o suficiente para finalizar a transação!',
                    ], Response::HTTP_CONFLICT);
                }
            } else {
                return response()->json([
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'Pagamento não está pendente de finalização.'
                ], Response::HTTP_CONFLICT);
            }

            return response()->json(['code' => Response::HTTP_OK, 'message' => 'Pagamento finalizado com sucesso!'], Response::HTTP_OK);
        } catch (ModelNotFoundException) {
            DB::rollBack();
            return response()->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Registro não encontrado para atualização.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(array_filter([
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Falha ao tentar atualizar.',
                'errorCode' => $e->getCode(),
                'errorMessage' => $e->getMessage(),
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTotals(Request $request) {
        try {
            $data = $this->repository->getTotals($request);

            return response()->json([
                'data' => [
                    'specieTotals' => $data[0],
                    'totalGeneral' => $data[1]
                ]
            ]);
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
