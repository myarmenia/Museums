<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class MuseumBranchRequest extends FormRequest
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
        'email'=> 'required',
        'phone_number'=> 'required',
        'translate.*.name' => 'required',
        'translate.*.address' => 'required',
        'translate.*.description' => 'required',
        'translate.*.working_days'=>'required',

    ];
    if($this->link!=null){
      $array['link']= 'url';
    }
    // if($this->phone_number!=null){
    //   $array['phone_number']= 'required';
    // }
    if($this->email!=null){
      $array['email']= 'email';
    }
      if(Request::method()=="POST"){
        $array['photo'] = 'required|image';
    }
    return $array;
    }
    public function messages(): array
    {
        return [
          'translate.*.name' => 'Անվանում դաշտը պարտադիր է:',
          'translate.*.description'=>'Նկարագիր դաշտը պարտադիր է:',
          'translate.*.address' => 'Հասցե դաշտը պարտադիր է։',
          'translate.*.working_days'=>'Աշխատանքային օրեր դաշտը պարտադիր է։',
          'photo' => 'Լուսանկարի դաշտը պարտադիր է:',
          'link' => 'Հղումը պետք է լինի վավեր URL:',
          // 'phone_number' => 'Հեռախոսահամար պետք է լինի թիվ:',
        ];
    }
}
