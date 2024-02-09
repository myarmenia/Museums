<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'description', 
        'lang'
    ];

    protected $table = 'project_translations';


    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }
    

}
