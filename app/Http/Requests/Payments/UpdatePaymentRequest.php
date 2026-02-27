<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'sometimes|numeric|min:0',
            'payment_type' => 'sometimes|string|max:50',
            'tax' => 'sometimes|numeric|min:0|max:100',
            'payer_id' => 'sometimes|exists:participants,id',
            'receiver_id' => 'sometimes|exists:participants,id',
        ];
    }
}
