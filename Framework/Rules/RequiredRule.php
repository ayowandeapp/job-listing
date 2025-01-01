<?php

namespace Framework\Rules;

class RequiredRule implements RuleInterface
{
    public function validate(array $data, string $field, mixed $params = '')
    {
        return !empty($data[$field]);

    }
    public function getMessage(array $data, string $field, mixed $params = '')
    {
        return "The " . ucfirst($field) . " field is required";

    }

}