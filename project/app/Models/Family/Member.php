<?php

namespace App\Models\Family;

use App\Models\Family\Finances\BankAccount;
use App\Models\Family\Finances\Requisition;
use App\Models\Invitation;
use App\Models\Traits\FamilyConnectionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory, FamilyConnectionTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'gender',
        'user_id'
    ];

    /**
     * @return HasOne
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(Invitation::class);
    }

    /**
     * @return HasOne
     */
    public function requisition(): HasOne
    {
        return $this->hasOne(Requisition::class);
    }

    /**
     * @return HasOne
     */
    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class);
    }
}
