<?php

namespace Framework\Rules;

class EmailRule implements RuleInterface
{
    public function validate(array $data, string $field, mixed $params = '')
    {
        return filter_var($data[$field], FILTER_VALIDATE_EMAIL);

    }
    public function getMessage(array $data, string $field, mixed $params = '')
    {
        return "Please enter a valid email address for the " . ucfirst($field) . " field ";

    }

}