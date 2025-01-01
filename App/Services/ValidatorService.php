<?php

namespace App\Services;

use Framework\Rules\EmailRule;
use Framework\Rules\ExistsRule;
use Framework\Rules\MaxRule;
use Framework\Rules\MinRule;
use Framework\Rules\RequiredRule;
use Framework\Rules\StringRule;
use Framework\Validator;

class ValidatorService
{

    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator;

        $this->validator->add('required', new RequiredRule());
        $this->validator->add('email', new EmailRule());
        $this->validator->add('min', new MinRule());
        $this->validator->add('max', new MaxRule());
        $this->validator->add('string', new StringRule());
        $this->validator->add('exists', new ExistsRule());

        // $this->validator->add('email', new EmailRule());
    }

    public function validateCreateListing(array $formData)
    {
        $errors = $this->validator->validate($formData, [
            'title' => ['required'],
            'salary' => ['required'],
            'description' => ['required'],
            'email' => ['required', 'email'],
            'city' => ['required'],
            'state' => ['required'],
        ]);

        return $errors;

    }
    public function validateRegister(array $formData)
    {
        $errors = $this->validator->validate($formData, [
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:6',],
        ]);

        return $errors;

    }

    public function validateLogin(array $formData)
    {
        $errors = $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        return $errors;
    }


}