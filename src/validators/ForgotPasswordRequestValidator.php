<?php

declare(strict_types=1);

namespace Src\Validators;

use Src\Contracts\RequestValidatorInterface;
use Src\Errors\ValidationException;
use Valitron\Validator;

class ForgotPasswordRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', 'email')->message('Required field');
        $v->rule('email', 'email');

        if (! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
