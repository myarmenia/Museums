<?php

namespace App\Models\Project;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'process_time', 
        'creation_date_at',
        'link_project',
        'link_app_store',
        'link_play_market',
        'project_language',
        'type',
    ];

    protected $table = 'projects';

    const TYPE_WEB = 'web';
    const TYPE_MOBILE = 'mobile';
    const TYPE_3D = '3d';

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function translation(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    // public function translation($lang){
    //     return $this->hasOne(ProjectTranslation::class)->where('lang', $lang)->first();
    // }


}
