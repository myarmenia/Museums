<?php

namespace App\Models;

use App\Traits\FilterTrait;
use App\Traits\Reports\ReportFilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierLog extends Model
{
    use HasFactory, ReportFilterTrait;
    protected $guarded = [];
    protected $table = 'cashier_logs';

    protected $defaultFields = ['action'];
    protected $relationFilter = [
      'purchases' => ['museum_id'],
      // 'ticket_qrs' => ['museum_id'],

    ];

  public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket_qrs(): BelongsTo
    {
      return $this->belongsTo(TicketQr::class, 'item_relation_id');
    }

    public function purchases(): BelongsTo
    {
      return $this->belongsTo(Purchase::class, 'item_relation_id');
    }

}
