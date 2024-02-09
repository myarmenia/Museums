<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TaskRequest extends FormRequest
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

        $lastSegment = Request::segment(count(request()->segments()));
        $array = [
          'lesson_id'=>'required',
          'duration' =>'required',
          'translate.*.description'=>'required',
      ];
      if($lastSegment=="task-store"){

          $array['video']='required|mimes:mp4,mov,ogg,qt';

      }

      return $array;
    }
    public function messages(): array
    {
        return [
          'duration' => 'Поле продолжительность обязательно для заполнения',
          'translate.*.description'=>'Поле описание обязательно для заполнения.',
          'video'=>'Поле видео обязательно для заполнения.'
        ];
    }
}
