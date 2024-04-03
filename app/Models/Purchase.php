<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    protected $defaultFields = ['museum_id', 'type'];

    protected $boolFilterFields = ['status'];
    protected $relationFilter = [
        'users' => ['gender', 'birth_day', 'country_id']
    ];

    public function purchased_items():HasMany
    {
      return $this->hasMany(PurchasedItem::class);
    }

    public function payment()
    {
      return $this->hasOne(Payment::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class, "user_id");
    }

}
