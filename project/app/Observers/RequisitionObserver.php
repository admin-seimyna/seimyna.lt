<?php

namespace App\Observers;

use App\Models\Family\Finances\Requisition;
use Carbon\Carbon;

class RequisitionObserver
{
    /**
     * @param Requisition $requisition
     */
    public function creating(Requisition $requisition): void
    {
        if (!$requisition->expires_at) {
            $requisition->expires_at = Carbon::now()->addDays(config('services.nordigen.expiration'));
        }
    }
}
