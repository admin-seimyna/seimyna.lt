<?php

namespace App\Models;

use App\Models\Scopes\VerifiedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_type',
        'module_id',
        'code',
        'type',
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
     * @param int $code
     */
    public function setOriginalCode(int $code): void
    {
        $this->originalCode = $code;
    }

    /**
     * @return int|null
     */
    public function getOriginalCode(): ?int
    {
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
}
