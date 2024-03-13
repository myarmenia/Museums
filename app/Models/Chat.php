<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'museum_id',
        'email',
        'title',
        'education_program_type',
        'read',
    ];

    protected $table = 'chats';

    public function visitor():HasOne
    {
        return $this->hasOne(User::class, 'id', 'visitor_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    
}
