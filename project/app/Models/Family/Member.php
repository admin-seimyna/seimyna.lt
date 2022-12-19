<?php

namespace App\Models\Family;

use App\Models\Traits\FamilyConnectionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        return $this->hasOne(MemberInvitation::class);
    }

    /**
     * @return BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_relations', 'parent_id', 'child_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_relations', 'member_id', 'related_to')->withTimestamps();
    }
}
