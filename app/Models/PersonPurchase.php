<?php

namespace App\Models;

use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPurchase extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    protected $defaultFields = ['gender', 'country_id'];

}
