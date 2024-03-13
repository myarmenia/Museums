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

        $event=Event::where('id',$lastSegment)->first();

        $array= [

          'event_config.*.*.start_time' => 'required|date_format:H:i|before_or_equal:event_config.*.*.end_time',
          'event_config.*.*.end_time'=>'required|date_format:H:i|after_or_equal:event_config.*.*.start_time'
        ];

        foreach($this->event_config as $value){
          foreach($value as $data){

            if(strtotime($event->start_date)>strtotime($data['day']) || strtotime($event->end_date)<strtotime($data['day'])){
              $start_time=$event->start_date;
              $array['event_config.*.*.day']="required|date|after:$start_time|before:$event->end_date";
                 }


          }
        }

        return $array;

    }
    public function messages(): array
    {
        return [

            'event_config.*.*.day.required' => 'Օր դաշտը պարտադիր է',
            'event_config.*.*.start_time.required' => 'Սկսվելու ժամանակը պարտադիր է',
            'event_config.*.*.end_time.required' => 'Ավարտվելու  ժամանակը պարտադիր է',

        ];




    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

        'errors' => $validator->errors(),

        ], 422));
    }
}
