<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Course\CourseLanguagesTrait;
use App\Traits\Users\UsersTrait;
use App\Traits\Projects\ProjectsTrait;
use App\Http\Resources\Projects\ProjectsForHomePageResource;

class HomeResource extends JsonResource
{
    use CourseLanguagesTrait, UsersTrait, ProjectsTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $calculator = [
        'projects' => [ 'id' => 0, 'count' => $this->getAllProjects()->count()],
        'students' => ['id' => 1, 'count' => $this->getAllStudents()->count()]
       ];

    

        return [
            'course_languages' => CourseLanguagesAllInfoResource::collection($this->getAllCourseLanguages()),
            'calculator' => $calculator,
            'all_projects' => ProjectsForHomePageResource::collection($this->getAllProjects())
        ];
    }
}
