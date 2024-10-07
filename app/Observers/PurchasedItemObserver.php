<?php

namespace App\Observers;

use App\Models\CashierLog;
use App\Models\PurchasedItem;
use App\Services\Log\CashierService;
use Auth;

class PurchasedItemObserver
{
    /**
     * Handle the PurchasedItem "created" event.
     */
    public function created(PurchasedItem $purchasedItem): void
    {

        CashierService::store($purchasedItem);

    }

    /**
     * Handle the PurchasedItem "updated" event.
     */
    public function updated(PurchasedItem $purchasedItem): void
    {

    }

    /**
     * Handle the PurchasedItem "deleted" event.
     */
    public function deleted(PurchasedItem $purchasedItem): void
    {
        //
    }

    /**
     * Handle the PurchasedItem "restored" event.
     */
    public function restored(PurchasedItem $purchasedItem): void
    {
        //
    }

    /**
     * Handle the PurchasedItem "force deleted" event.
     */
    public function forceDeleted(PurchasedItem $purchasedItem): void
    {
        //
    }
}
