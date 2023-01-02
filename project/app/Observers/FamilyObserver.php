<?php

namespace App\Observers;

use App\Models\Family\Family;
use Illuminate\Support\Facades\DB;

class FamilyObserver
{
    /**
     * @param Family $family
     */
    public function creating(Family $family): void
    {
        $connection = app()->runningUnitTests() ? env('DB_FAMILY_DATABASE') : time() . uniqid();
        $family->connection_name = $connection;
        config([
            'database.connections.migration' => array_merge(config('database.connections.main'), [
                'database' => $family->connection_name
            ])
        ]);
        $family->createDatabase();
        $family->migrate('migration');

        // destroy migration connection
        $connections = config('database.connections');
        unset($connections['migration']);
        config(['database.connections' => $connections]);
    }

    /**
     * @param Family $family
     */
    public function deleted(Family $family): void
    {
        DB::statement('drop database if exists ' . $family->connection_name);
        $family->disconnect();
    }
}
