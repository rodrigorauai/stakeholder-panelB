<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CnpjFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CnpjFormat */

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^\d{2}\.?\d{3}\.?\d{3}\/?\d{4}\-?\d{2}$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
