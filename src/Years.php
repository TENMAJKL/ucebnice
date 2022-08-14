<?php

namespace App;

class Years
{
    public const Years = [
        '1. čtyřleté',
        '2. čtyřleté',
        '3. čtyřleté',
        '4. šestileté',
        '1. šestileté',
        '2. šestileté',
        '3. šestileté',
        '4. šestileté',  
        '5. šestileté',
        '6. šestileté',
    ];

    public static function is(string $target): bool
    {
        return in_array($target, self::Years);
    }

    public static function id(string $target): int
    {
        return array_key_first(
            array_filter(self::Years, fn($item) => $item === $target)
        );
    }
}
