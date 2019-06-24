<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CnpjNumbersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CnpjNumbers */

        if (null === $value || '' === $value) {
            return;
        }

        $digits = preg_replace('/[^\d]/', '', $value);

        if (14 !== strlen($digits)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        $v1 = 5 * $digits[0] + 4 * $digits[1] + 3 * $digits[2] + 2 * $digits[3];
        $v1 += 9 * $digits[4] + 8 * $digits[5] + 7 * $digits[6] + 6 * $digits[7];
        $v1 += 5 * $digits[8] + 4 * $digits[9] + 3 * $digits[10] + 2 * $digits[11];
        $v1 = 11 - $v1 % 11;

        if ($v1 >= 10) {
            $v1 = 0;
        }

        $v2 = 6 * $digits[0] + 5 * $digits[1] + 4 * $digits[2] + 3 * $digits[3];
        $v2 += 2 * $digits[4] + 9 * $digits[5] + 8 * $digits[6] + 7 * $digits[7];
        $v2 += 6 * $digits[8] + 5 * $digits[9] + 4 * $digits[10] + 3 * $digits[11];
        $v2 += 2 * $digits[12];
        $v2 = 11 - $v2 % 11;

        if ($v2 >= 10) {
            $v2 = 0;
        }

        if ((string) $v1 !== (string) $digits[12] || (string) $v2 !== (string) $digits[13]) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
