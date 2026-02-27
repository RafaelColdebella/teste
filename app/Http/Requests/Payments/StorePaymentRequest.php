<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:0',
            'payment_type' => 'required|string|max:50',
            'tax' => 'nullable|numeric|min:0|max:100',
            'payer_id' => 'required|exists:participants,id',
            'receiver_id' => 'required|exists:participants,id',
        ];
    }
}
