<?php

namespace Framework\Rules;

interface RuleInterface
{
    public function validate(array $data, string $field, mixed $params = '');
    public function getMessage(array $data, string $field, mixed $params = '');
}