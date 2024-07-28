<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
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
        "product_category_id"=> 'required',
        'price'=> 'required|integer|gt:0',
        'quantity'=> 'required|integer|gt:0',
        'translate.*.name' => 'required',
    ];

      if(Request::method()=="POST"){
        $array['photo'] = 'required|image';
    }
    return $array;
    }
    public function messages(): array
    {
        return [
          "product_category_id" =>"Ապրանքի կատեգորիա դաշտը պարտադիր է",
            'translate.*.name' => 'Անվանում դաշտը պարտադիր է',
            'price.required' => 'Գին դաշտը պարտադիր է',
            'price.integer'=> 'Գին դաշտը պետք է լինի վավեր',
            'quantity.required' => 'Քանակ դաշտը պարտադիր է',
            'quantity.integer'=> 'Քանակ դաշտը պետք է լինի վավեր',
            'photo' => 'Լուսանկարի դաշտը պարտադիր է:',
        ];
    }
}
