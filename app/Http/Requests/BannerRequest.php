<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BannerRequest extends FormRequest
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

          'translate.*.text' => 'required',
      ];

        if(Request::method()=="POST"){
          $array['photo'] = 'required|image|dimensions:min_width=1530,min_height=880,max_width=1550,max_height=920';
      }
      return $array;
    }
    public function messages(): array
    {
        return [
            'translate.*.text' => 'Անվանում դաշտը պարտադիր է',
            'photo' => 'Լուսանկարի դաշտը պարտադիր է:',
            'photo.dimensions' =>'Լուսանկարի լայնքը պետք է լինի 1530 մինչև 1550 և բարձրությունը 880 մինչև 920',
        ];
    }
}
