<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\URL;



class EventConfigRequest extends FormRequest
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
        $url = URL::previous();
        $segments = explode('/', $url);
        $lastSegment = end($segments);
      // dd($lastSegment);
        $event=Event::where('id',$lastSegment)->first();

        $array= [
          // 'event_config.*.*.day'=>'required',
          'event_config.*.*.start_time' => 'required',
          'event_config.*.*.end_time' => 'required',
        ];
        // dd($this->event_config);
        foreach($this->event_config as $value){
          foreach($value as $data){

            if(strtotime($event->start_date)>strtotime($data['day']) || strtotime($event->end_date)<strtotime($data['day'])){
              $start_time=$event->start_date;
              $array['event_config.*.*.day']="required|date|after:$start_time|before:$event->end_date";


                        }
          }
        }


// dd($array);
        return $array;

    }
    public function messages(): array
    {
        $array=[

            'event_config.*.*.day' => 'Օր դաշտը պարտադիր է',
        
            'event_config.*.*.start_time' => 'Սկսվելու ժամանակը պարտադիր է',
            'event_config.*.*.end_time' => 'Ավարտվելու  ժամանակը պարտադիր է',

        ];

        return $array;

    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

        'errors' => $validator->errors(),

        ], 422));
    }
}
