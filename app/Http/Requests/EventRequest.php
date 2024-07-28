<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EventRequest extends FormRequest
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
      // dd($this);
      $array= [
        'start_date'=>'required|date|before:end_date',
        'end_date' => "required|date|after:start_date",
        'price'=> 'required|integer|gt:0',
        'visitors_quantity_limitation'=> 'required|integer|gt:0',
        'translate.*.name' => 'required',
        'translate.*.description' => 'required',
    ];

      if(Request::method()=="POST"){
        $array['photo'] = 'required|image';
    }
    return $array;
    }
    public function messages(): array
    {
        return [

            'translate.*.name' => 'Անվանում դաշտը պարտադիր է',
            'translate.*.description' => 'Նկարագիր դաշտը պարտադիր է',
            'price.required' => 'Գին դաշտը պարտադիր է',
            'price.integer'=> 'Գին դաշտը պետք է լինի վավեր',
            'visitors_quantity_limitation.required' => 'Քանակ դաշտը պարտադիր է',
            'visitors_quantity_limitation.integer'=> 'Քանակ դաշտը պետք է լինի վավեր',
            'photo' => 'Լուսանկարի դաշտը պարտադիր է:',
        ];
    }
}
