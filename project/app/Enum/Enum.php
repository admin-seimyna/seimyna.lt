<?php

namespace App\Enum;

use Illuminate\Support\Collection;

class Enum
{
    /**
     * @return Collection
     */
    public static function all() : Collection
    {
        return static::collect();
    }

    /**
     * @return Collection
     */
    public static function get(): Collection
    {
        return static::all();
    }

    /**
     * @return Collection
     */
    public static function values(): Collection
    {
        return static::collect()->values();
    }

    /**
     * @return Collection
     */
    public static function keys(): Collection
    {
        return static::collect()->keys();
    }

    /**
     * @param Collection $values
     * @return Collection
     */
    public static function export(Collection $values): Collection
    {
        return $values;
    }

    /**
     * @return Collection
     */
    private static function collect(): Collection
    {
        return collect((new \ReflectionClass(static::class))->getConstants());
    }
}
