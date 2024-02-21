<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MuseumRequest extends FormRequest
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
            'name.*' => 'required|string',
            'description.*' => 'required|string',
            'address.*' => 'required|string',
            'work_days.*' => 'required|string',
            'owner' => 'required',
            'phones.phone1' => 'required',
            'region' => 'required',
            'account_number' => 'required',
            'photos.*' => 'required',
            'mainPhoto' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
          'name.*' => 'Անվանում դաշտը պարտադիր է',
          'description.*' =>  "Նկարագրություն դաշտը պարտադիր է",
          'address.*' => 'Հասցե դաշտը պարտադիր է',
          'work_days.*' => 'Աշխատանքային օր դաշտը պարտադիր ',
          'owner' => 'Տնօրենի անուն դաշտը պարտադիր է',
          'phones.phone1' => 'Հեռախոսահամար 1 դաշտը պարտադիր է',
          'region' => 'Մարզ դաշտը պարտադիր է',
          'account_number' => 'Հաշվեհամար դաշտը պարտադիր է',
          'photos.*' => 'Նկար դաշտը պարտադիր է',
          'mainPhoto' => 'Պետք է ունենք գոնե մեկ գլխավոր նկար',
         
        ];
    }
}
