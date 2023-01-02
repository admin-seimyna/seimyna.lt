<?php

namespace App\Models\Family;

use App\Enum\MemberInvitationTypesEnum;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function createDatabase(): bool
    {
        return DB::statement('create database if not exists ' . $this->connection_name);
    }

    /**
     * Create and cache user connection settings
     */
    public function connect()
    {
        if (!Auth::check()) {
            return;
        }

        $this->disconnect();

        Cache::rememberForever(User::getFamilyKey(), function () {
            return $this->id;
        });
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
     * @param callable $callback
     */
    public function connectMoment(callable $callback): void
    {
        $this->connect();
        $callback();
        $this->disconnect();
    }

    /**
     * Migrate family
     * @param string $connection
     */
    public function migrate(string $connection): void
    {
        Artisan::call('migrate:fresh --database=' . ($connection  ?? User::getConnectionKey()) . ' --path=database/migrations/family');
    }

    /**
     * Destroy user connection settings
     */
    public function disconnect(): void
    {
        Cache::forget(User::getConnectionKey());
        Cache::forget(User::getFamilyKey());
    }

    /**
     * @param string $identifier
     * @param string $name
     * @param int $memberId
     * @return Model
     */
    public function inviteViaEmail(string $identifier, string $name, int $memberId): Model
    {
        return $this->invitation()->create([
            'type' => MemberInvitationTypesEnum::EMAIL,
            'identifier' => $identifier,
            'name' => $name,
            'member_id' => $memberId
        ]);
    }

    /**
     * @return HasOne
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    /**
     * @return HasMany
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
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
        return $this->setConnection(DB::getDefaultConnection())->hasOne(Member::class);
    }
}
