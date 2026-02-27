<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'specie_id' => ['sometimes', 'integer'],
            'value' => ['sometimes', 'numeric', 'decimal:0,2'],
            'paid_by' => ['sometimes', 'string'],
            'received_by' => ['sometimes', 'string']
        ];
    }
}
