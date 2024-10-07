<?php

namespace App\Models;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierLog extends Model
{
    use HasFactory, FilterTrait;
    protected $guarded = [];
    protected $table = 'cashier_logs';

    protected $filterFields = ['type'];

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
