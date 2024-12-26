<?php

namespace App\Observers;

use App\Models\TicketQr;
use App\Services\Log\CashierService;

class TicketQrObserver
{
    /**
     * Handle the TicketQr "created" event.
     */
    public function created(TicketQr $ticketQr): void
    {
        //
    }

    /**
     * Handle the TicketQr "updated" event.
     */
    public function updated(TicketQr $ticketQr): void
    {
        CashierService::returned($ticketQr);

  }

    /**
     * Handle the TicketQr "deleted" event.
     */
    public function deleted(TicketQr $ticketQr): void
    {
        //
    }

    /**
     * Handle the TicketQr "restored" event.
     */
    public function restored(TicketQr $ticketQr): void
    {
        //
    }

    /**
     * Handle the TicketQr "force deleted" event.
     */
    public function forceDeleted(TicketQr $ticketQr): void
    {
        //
    }
}
