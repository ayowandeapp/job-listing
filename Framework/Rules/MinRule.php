<?php

namespace Framework\Rules;

class MinRule implements RuleInterface
{
    public function validate(array $data, string $field, mixed $param = '')
    {
        $value = trim($data[$field]);
        $length = strlen($value);
        return $length >= $param;

    }
    public function getMessage(array $data, string $field, mixed $param = '')
    {
        return "The min length must be at least $param characters";
    }

}