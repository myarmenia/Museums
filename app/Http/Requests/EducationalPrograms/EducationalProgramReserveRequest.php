<?php

namespace App\Http\Requests\EducationalPrograms;

use App\Models\EducationalProgram;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EducationalProgramReserveRequest extends FormRequest
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

          $min = 1;
          $max = 50;

          if(request()->educational_program_id != "null_id" && request()->educational_program_id != "null" && request()->educational_program_id != null){

              $educational_program = EducationalProgram::where(['museum_id' => museumAccessId(), 'id' => request()->educational_program_id])->first();
              $min = $educational_program->min_quantity;
              $max = $educational_program->max_quantity;
          }

          return [
            'educational_program_id' => 'required',
            'date' => 'required|after_or_equal:' . now()->format('Y-m-d'),
            'time' => 'required|date_format:H:i',
            'visitor_quantity' => 'required|integer|min:' . $min . '|max:' . $max,
            'description' => 'required',
          ];


    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

        'errors' => $validator->errors(),
        // 'status' => true
        ], 422));
    }
}
