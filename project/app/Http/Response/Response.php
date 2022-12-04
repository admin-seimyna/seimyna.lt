<?php

namespace App\Http\Response;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class Response
{
    /**
     * @return $this
     */
    public function create(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [];
    }
}
