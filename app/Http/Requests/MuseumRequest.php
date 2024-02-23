<?php

namespace App\Http\Requests;

use App\Rules\PhotoRule;
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
            'general_photo' => 'required|dimensions:min_width=1520,min_height=445,max_width=1550,max_height=500',
            'photos.*' => 'dimensions:min_width=446,min_height=370,max_width=460,max_height=380',
        ];
    }

    public function messages(): array
    {
        return [
            'name.*' => 'Անվանում դաշտը պարտադիր է',
            'description.*' => "Նկարագրություն դաշտը պարտադիր է",
            'address.*' => 'Հասցե դաշտը պարտադիր է',
            'work_days.*' => 'Աշխատանքային օր դաշտը պարտադիր ',
            'owner' => 'Տնօրենի անուն դաշտը պարտադիր է',
            'phones.phone1' => 'Հեռախոսահամար 1 դաշտը պարտադիր է',
            'region' => 'Մարզ դաշտը պարտադիր է',
            'account_number' => 'Հաշվեհամար դաշտը պարտադիր է',
            'photos.*' => 'Նկարի լայնքը պետք է լինի 446 մինչև 460 և բարձրությունը 370 մինչև 380',
            'general_photo' => 'Նկարի լայնքը պետք է լինի 1520 մինչև 1550 և բարձրությունը 445 մինչև 500',

        ];
    }
}
