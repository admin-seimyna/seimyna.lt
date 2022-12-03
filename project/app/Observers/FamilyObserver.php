<?php

namespace App\Observers;

use App\Models\Family\Family;

class FamilyObserver
{
    /**
     * @param Family $family
     */
    public function creating(Family $family)
    {
        $family->connection_name = mb_strtolower($family->name) . '_' . time();
    }
}
