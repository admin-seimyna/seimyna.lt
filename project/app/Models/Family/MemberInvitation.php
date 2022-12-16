<?php

namespace App\Models\Family;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberInvitation extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'identifier'
    ];


    /**
     * @return BelongsTo
     */
    protected function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo
     */
    protected function author(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'invited_by');
    }
}
