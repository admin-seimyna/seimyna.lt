<?php

namespace App\Models\Family;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    protected $connection = '1_connection';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'status',
        'user_id'
    ];

    /**
     * @return HasOne
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(MemberInvitation::class);
    }
}
