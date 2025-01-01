<?php

namespace Framework\Rules;

class MaxRule implements RuleInterface
{
    public function validate(array $data, string $field, mixed $param = '')
    {
        $value = trim($data[$field]);
        $length = strlen($value);
        return $length <= $param;

    }
    public function getMessage(array $data, string $field, mixed $param = '')
    {
        return "The max length must be less than $param characters";
    }

}