<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $connection  ='main';

    /**
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'name',
        'bic',
        'logo'
    ];
}
