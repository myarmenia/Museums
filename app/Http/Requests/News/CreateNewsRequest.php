<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class CreateNewsRequest extends FormRequest
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
     
        $array= [
            'translate.*.description' => 'required',
            'translate.*.title' => 'required',

        ];
          if(Request::method()=="POST"){
            $array['photo'] = 'required|image';
        }
        return $array;
    }
    public function messages(): array
    {
        return [
          'translate.*.description'=>'Վերնագրի դաշտը պարտադիր է:',
          'translate.*.title' => 'Տեքստի դաշտը պարտադիր է:',
          'photo' => 'Լուսանկարի դաշտը պարտադիր է:'
        ];
    }

}
