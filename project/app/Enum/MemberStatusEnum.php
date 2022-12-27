<?php

namespace App\Enum;

use Illuminate\Support\Collection;

class MemberStatusEnum extends Enum
{
    const FATHER = 'father';
    const MOTHER = 'mother';
    const DAUGHTER = 'daughter';
    const SON = 'son';
    const GRANDFATHER = 'grandfather';
    const GRANDMOTHER = 'grandmother';
}
