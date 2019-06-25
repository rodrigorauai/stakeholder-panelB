<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CpfNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CpfNumber */

        if (null === $value || '' === $value) {
            return;
        }

        $digits = preg_replace('/[^\d]/', '', $value);

        if (11 !== strlen($digits)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        $falsePositives = [
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        ];

        if (in_array($digits, $falsePositives)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
            return;
        }

        $r1 = 0;
        $r2 = 0;

        $reverse = substr(strrev($digits), 2);

        for ($i = 0; $i < 9; $i++) {
            $digit = substr($reverse, $i, 1);

            $r1 = $r1 + $digit * (9 - ($i % 10));
            $r2 = $r2 + $digit * (9 - (($i + 1) % 10));
        }

        $r1 = ($r1 % 11) % 10;
        $r2 = $r2 + $r1 * 9;
        $r2 = ($r2 % 11) % 10;

        $v1 = substr($digits, 9, 1);
        $v2 = substr($digits, 10, 1);

        if ($v1 === $r1 && $v2 === $r2) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
