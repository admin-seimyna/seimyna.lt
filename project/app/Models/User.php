<?php

namespace App\Models;

use App\Models\Family\Family;
use App\Models\Family\FamilyUser;
use App\Models\Family\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'main';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getConnectionKey(): string
    {
        return app()->runningUnitTests() ? env('DB_DATABASE') : Auth::id() . '_connection';
    }

    /**
     * @return string
     */
    public static function getFamilyKey(): string
    {
        return Auth::id() . '_family';
    }

    /**
     * @return BelongsToMany
     */
    public function family(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_users');
    }

    /**
     * @return HasOneThrough
     */
    public function currentFamily(): HasOneThrough
    {
        return $this->hasOneThrough(Family::class, FamilyUser::class, 'user_id', 'id', 'id', 'family_id')
            ->where('id', Cache::get(static::getFamilyKey()));
    }

    /**
     * @return HasOne
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * @return MorphOne
     */
    public function verification(): MorphOne
    {
        return $this->morphOne(Verification::class, 'module');
    }
}
