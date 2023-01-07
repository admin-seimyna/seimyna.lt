<?php

namespace App\Models\Family\Finances;

use App\Models\Scopes\ValidRequisitionScope;
use App\Models\Traits\FamilyConnectionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory, FamilyConnectionTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'link',
        'bank_id',
        'activated_at',
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
}
