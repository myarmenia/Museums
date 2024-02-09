<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\Project;

class ProjectAddOrCreateRequest extends FormRequest
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
// dd($this->id);
        $project = Project::find($this->id);

        $data =  [
            'name' => 'required',
            "proj-am" => 'required',
            "proj-ru" => 'required',
            "proj-en" => 'required',
            "lang" => 'required',
            "process_time" => 'required',
            "creation_date_at" => 'required',
            "type" => 'required',
            "project_photos"=>'required',

        ];


        if(isset($this->project_photos)){

          if(isset($this->id)){
            $images_count = count($this->project_photos) + $project->images->count();

            if($images_count >= 4){
                $count = 4 - $project->images->count();
                $data["project_photos"] = "required|array|max:$count";
            }
            else{
                $data["project_photos"] = 'required';
            }
          }
        }

       return $data;
    }
}
