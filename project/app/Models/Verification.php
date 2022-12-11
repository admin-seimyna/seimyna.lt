<?php

namespace App\Models;

use App\Models\Scopes\VerifiedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;

class Verification extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'module_type',
        'module_id',
        'code',
        'type',
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
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new VerifiedScope());
    }

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
