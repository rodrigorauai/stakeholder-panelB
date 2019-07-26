<?php
namespace App\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\PersonRepository;
class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var PersonRepository
     */
    // private PersonRepository $personRepository;
    private $personRepository;
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueUser */
        if (null === $value || '' === $value) {
            return;
        }
        $existingUser = $this->personRepository->findOneBy(['email' => $value]) ||
        $existingUser = $this->personRepository->findOneBy(['cpf'   => $value]) ||
        $existingUser = $this->personRepository->findOneBy(['rg'    => $value]) ||
        $existingUser = $this->personRepository->findOneBy(['phone' => $value]);
        if (!$existingUser) {
            return;
        }
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}