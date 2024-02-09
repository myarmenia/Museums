<?php
namespace App\Repositories\Project;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project\Project;

class ProjectRepository implements ProjectRepositoryInterface{
    public function getProject()
    {
        return Project::with(['images', 'translation'])->get();
    }
}