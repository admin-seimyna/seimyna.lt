<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;

class Verification extends Model
{
    use HasFactory;

    protected $connection = 'main';

    /**
     * @var string[]
     */
    protected $fillable = [
        'module_type',
        'module_id',
        'code',
        'type',
        'verified_at'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'code',
        'verified_at',
        'module_type',
        'module_id'
    ];

    protected $appends = [
        'is_verified',
        'is_expired'
    ];

    /**
     * @var int|null
     */
    private ?int $originalCode = null;

    /**
     * @return MorphTo
     */
    public function module(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeUnverified(Builder $builder): Builder
    {
        return $builder->whereNull('verified_at');
    }

    /**
     * @param string $value
     * @return int
     */
    public function getModuleIdAttribute(string $value): int
    {
        return (int)$value;
    }

    /**
     * @return int|null
     */
    public function getOriginalCode(): ?int
    {
        return $this->originalCode;
    }

    /**
     * @return int
     */
    public function generateCode(): int
    {
        $length = config('auth.verification.code_length');
        $from = '';
        $to = '';
        for($x = 0; $x < $length; $x++) {
            $from .= '1';
            $to .= '9';
        }

        $this->originalCode = rand((int)$from, (int)$to);
        $this->expires_in = Carbon::now()->addDay();
        $this->code = Hash::make($this->originalCode);
        return $this->originalCode;
    }

    /**
     * @return bool
     */
    public function verify(): bool
    {
        $this->verified_at = Carbon::now();
        return $this->update();
    }

    /**
     * @return bool
     */
    public function getIsVerifiedAttribute(): bool
    {
        return !empty($this->verified_at);
    }

    /**
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        return Carbon::now()->isAfter($this->expires_in);
    }
}
