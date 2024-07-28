<?php

namespace App\Rules;

use App\Models\EducationalProgram;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCashierEducational implements Rule
{
    public function passes($attribute, $value)
    {
        $ids = array_keys($value);
        $educational = EducationalProgram::whereIn('id', $ids)->get();

        foreach ($value as $key=> $inputValue) {
            $tmpEduc = $educational->where('id', $key)->first();
            if($inputValue < $tmpEduc->min_quantity || $inputValue > $tmpEduc->max_quantity){
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'Տոմսերի քանակը պետք է լինի միջակայքին համապատասխան';
    }


}
