<?php

namespace Framework;

use Framework\Rules\RuleInterface;

class Validator
{
    private array $rules = [];

    public function add(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;

    }

    public function validate(array $formData, array $fields)
    {
        $formData = array_map('sanitize', $formData);

        $errors = [];

        foreach ($fields as $field => $values) {
            foreach ($values as $key => $rule) {
                $ruleArr = explode(':', $rule);
                $rule = $ruleArr[0];
                $ruleParam = $ruleArr[1] ?? '';
                $initRule = $this->rules[$rule];
                if (!$initRule->validate($formData, $field, $ruleParam)) {
                    $errors[] = $initRule->getMessage($formData, $field, $ruleParam);
                }
            }
        }

        return $errors;

    }
}