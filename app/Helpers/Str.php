<?php

namespace App\Helpers;

class Str
{
    public static function studly($value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }

    public static function camel($value): string
    {
        return ucfirst(static::studly($value));
    }
}