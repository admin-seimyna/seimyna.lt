<?php

namespace App\Models\Family\Finances;

use App\Models\Traits\FamilyConnectionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory, FamilyConnectionTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'uid',
        'iban',
        'bank_id',
        'requisition_id',
        'created_at',
    ];

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeVirtual(Builder $builder): Builder
    {
        return $builder->whereNull('requisition_id');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeReal(Builder $builder): Builder
    {
        return $builder->whereNotNull('requisition_id');
    }
}
