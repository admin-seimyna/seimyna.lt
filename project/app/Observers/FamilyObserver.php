<?php

namespace App\Observers;

use App\Models\Family\Family;

class FamilyObserver
{
    /**
     * @param Family $family
     */
    public function creating(Family $family): void
    {
        $connection = app()->runningUnitTests() ? 'seimyna_test_family' : mb_strtolower($family->name) . '_' . time() . uniqid();
        $family->connection_name = $connection;
    }
}
