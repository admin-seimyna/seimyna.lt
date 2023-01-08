<?php

namespace App\Models\Family\Finances;

use App\Models\Bank;
use App\Models\Scopes\ValidRequisitionScope;
use App\Models\Traits\FamilyConnectionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requisition extends Model
{
    use HasFactory, FamilyConnectionTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'link',
        'redirect',
        'bank_id',
        'expires_at',
    ];

    /**
     * Boot
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new ValidRequisitionScope);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->whereNotNull('activated_at');
    }

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
