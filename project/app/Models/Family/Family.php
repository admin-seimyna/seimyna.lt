<?php

namespace App\Models\Family;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Family extends Model
{
    use HasFactory;

    protected $connection = 'main';

    protected $fillable = ['name'];

    /**
     * Create and cache user connection settings
     */
    public function connect()
    {
        if (!Auth::check()) {
            return;
        }

        Cache::forget(User::getConnectionKey());
        Cache::rememberForever(User::getConnectionKey(), function () {
            $defaultConnection = config('database.connections.main');
            $clientConnection = array_merge($defaultConnection, [
                'database' => $this->connection_name
            ]);
            config(['database.connections.' . User::getConnectionKey() => $clientConnection]);
            return $clientConnection;
        });
    }

    /**
     * Destroy user connection settings
     */
    public function disconnect(): void
    {
        Cache::forget(User::getConnectionKey());
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'seimyna.family_users');
    }
}
