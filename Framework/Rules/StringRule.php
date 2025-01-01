<?php

namespace Framework\Rules;

class StringRule implements RuleInterface
{
    public function validate(array $data, string $field, mixed $params = '')
    {
        return is_string($data[$field]);

    }
    public function getMessage(array $data, string $field, mixed $params = '')
    {
        return "The " . ucfirst($field) . " is not a string";

    }

}