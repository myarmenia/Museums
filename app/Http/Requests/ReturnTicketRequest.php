<?php

namespace App\Http\Requests;

use App\Rules\PhotoRule;
use Illuminate\Foundation\Http\FormRequest;

class ReturnTicketRequest extends FormRequest
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
            'unique_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'unique_id' => 'Թոքեն դաշտը պարտադիր է',
        ];
    }
}
