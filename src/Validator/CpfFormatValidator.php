<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CpfFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CpfFormat */

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^\d{3}\.?\d{3}\.?\d{3}\-?\d{2}?$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

        }
    }
}
