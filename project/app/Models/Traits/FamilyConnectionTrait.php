<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

trait FamilyConnectionTrait
{
    /**
     * @return string
     */
    public function getTable(): string
    {
        return config('database.connections.' . DB::getDefaultConnection())['database'] . '.' . parent::getTable();
    }

    /**
     * @param string $table
     * @return string
     */
    public function setTable($table): string
    {
        return $this;
    }
}
