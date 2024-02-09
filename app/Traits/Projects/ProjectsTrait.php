<?php
namespace App\Traits\Projects;
use App\Models\Project\Project;

trait ProjectsTrait
{
    public function getAllProjects(){

        return Project::all();
    }
}