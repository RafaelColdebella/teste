<?php

namespace App\Http\Resources\Payments;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->public_id,
            "status" => $this->status,
            "specie_id" => $this->specie,
            "value" => $this->value,
            "transaction_tax" => $this->transaction_tax,
            "paid_by" => $this->payer->public_id,
            "received_by" => $this->receiver->public_id
        ];
    }
}
