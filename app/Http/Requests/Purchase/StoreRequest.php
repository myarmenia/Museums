<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
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

        $user = auth('api')->user();
        $data = [];

        if(($user == null && $this->person == null) || ($user == null && $this->person != null && !isset($this->person['email']))) {
          $data = [
                "email" => "required|email"
            ];
        }

        return $data;
    }

    protected function failedValidation(Validator $validator)
    {
      throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
      ], 422));
    }
}
