<?php

namespace App\Http\Requests\User;

use App\Models\User;
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
              'roles' => 'required',
              'name' => 'required',
              'surname' => 'required'
        ];

        if(request()->getMethod() == 'POST'){
            $data['email'] = 'required|email|unique:users,email';
            $data['password'] = 'required|same:confirm-password|min:8';
        }
        else{

          $id = request()->segment(2);
          $user = User::find($id);
          if(request()->email != $user->email){
            $data['email'] = 'required|email|unique:users,email';
            $data['password'] = 'required|same:confirm-password|min:8';

          }

          if (request()->password) {
              $data['password'] = 'required|same:confirm-password|min:8';
          }

        }


        return $data;
    }
}
