<?php

namespace App\Http\Requests\User;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateOrUpdateRequest extends FormRequest
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

        $data = [
              'email' => 'required|regex:/^([a-z,0-9,․]+)@([a-z,․]+)\.(.+)/i|email|unique:users,email',
              'password' => 'required|same:confirm-password|min:8',
              'roles' => 'required'
          ];

        if(Auth::getDefaultDriver() == 'web'){
              $data['name'] = 'required';
              $data['surname'] = 'required';

            }
        return $data;
    }
}
