<?php

namespace App\Models;

use App\Models\Family\Family;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $connection = 'main';

    /**
     * @var string|null
     */
    protected ?string $originalCode = null;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'identifier',
        'member_id',
        'family_id',
        'name'
    ];

    /**
     * @return string
     */
    public function generateCode(): string
    {
        $this->originalCode = mb_strtoupper(Str::random(config('auth.invitation.code_length')));
        $this->code = Hash::make($this->originalCode);
        return $this->code;
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        $this->activated_at = Carbon::now();
        return $this->save();
    }

    /**
     * @return string|null
     */
    public function getOriginalCode(): ?string
    {
        return $this->originalCode;
    }

    /**
     * @return BelongsTo
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
