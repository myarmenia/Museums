<?php

namespace App\Http\Requests\Corporative;

use Illuminate\Foundation\Http\FormRequest;

class CorporativeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|filled',
            'tin' => 'required|filled',
            'tickets_count' => 'required|numeric|min:100',
            'price' => 'required|filled|numeric',
            'email' => 'nullable|email',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Անվանում դաշտը պարտադիր է',
            'tin' => "ՀՎՀՀ դաշտը պարտադիր է",
            'tickets_count.required' => "Տոմսերի քանակ դաշտը պարտադիր է",
            'tickets_count.numeric' => "Տոմսերի քանակ դաշտը պետք է պարունակի միայն թիվ",
            'tickets_count.min' => "Տոմսերի քանակ դաշտը պետք է ամենաքիչը 100տոմս",
            'price.required' => 'Գին դաշտը պարտադիր է',
            'price.numeric' => 'Գին դաշտը դաշտը պետք է պարունակի միայն թիվ',
            'email' => 'Էլփոստ դաշտը պետք է լինի email տիպի',
        ];
    }
}
