<?php

namespace Framework;

class Validation
{

    public static function string(string $value, int $min = 1, float $max = INF): bool
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            return $length >= $min && $length <= $max;
        }

        return false;
    }

    public static function email(string $value): bool|string
    {
        $value = trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function match(string $value1, string $value2): bool
    {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return $value1 === $value2;
    }

    public static function int(mixed $value): bool
    {
        return is_int($value);
    }
}