<?php

declare(strict_types=1);

namespace Src\Validators;

use Src\Contracts\RequestValidatorInterface;
use Src\Errors\ValidationException;
use Valitron\Validator;

class TwoFactorLoginRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['code'])->message('Required field');

        if (! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
