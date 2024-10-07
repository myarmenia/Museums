<?php

namespace App\Observers;

use App\Models\Purchase;
use App\Services\Log\CashierService;

class PurchaseObserver
{
    /**
     * Handle the Purchase "created" event.
     */
    public function created(Purchase $purchase): void
    {

        CashierService::store($purchase);

    }


}
