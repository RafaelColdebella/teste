<?php

namespace App\Http\Requests\Participants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:participants,email,' . $this->route('participant'),
            'document' => 'sometimes|required|string|max:20',
            'balance' => 'sometimes|numeric|min:0',
            'type' => 'sometimes|required|in:payer,receiver',
        ];
    }
}
