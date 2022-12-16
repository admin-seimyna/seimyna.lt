<?php

namespace App\Models\Family;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Family extends Model
{
    use HasFactory;

    protected $connection = 'main';

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @return bool
     */
    public function createConnection(): bool
    {
        return DB::statement('create database if not exists ' . $this->connection_name);
    }

    /**
     * Create and cache user connection settings
     */
    public function connect()
    {
        if (!Auth::guard('api')->check()) {
            return;
        }

        Cache::forget(User::getConnectionKey());
        Cache::rememberForever(User::getConnectionKey(), function () {
            $defaultConnection = config('database.connections.' . DB::getDefaultConnection());
            $clientConnection = array_merge($defaultConnection, [
                'database' => $this->connection_name
            ]);
            config(['database.connections.' . User::getConnectionKey() => $clientConnection]);
            DB::setDefaultConnection(User::getConnectionKey());
            return $clientConnection;
        });
    }

    /**
     * Migrate family
     */
    public function migrate(): void
    {
        Artisan::call('migrate:fresh --database=' . User::getConnectionKey() . ' --path=database/migrations/family');
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
        return $this->belongsToMany(User::class, 'family_users');
    }

    /**
     * @return HasOne
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }
}
