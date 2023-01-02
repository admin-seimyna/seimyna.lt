<?php

namespace App\Models\Family;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyUser extends Model
{
    use HasFactory;

    protected $connection = 'main';
}
