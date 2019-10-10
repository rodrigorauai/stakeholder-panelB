<?php
namespace App\Validator;
use App\Entity\Person;
use App\Form\PersonTypeNew;
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
        /* @var $constraint \App\Validator\UniqueUser */
        if (null === $value || '' === $value) {
            return;
        }

        $valuesCPF = str_replace(['-', '.'], '', $value);

        $valueCPF = $this->personRepository->findOneBy(['cpf' => $valuesCPF]);
//        dd($valueCPF);

        $existingUser = $valueCPF ||
        $existingUser = $this->personRepository->findOneBy(['email' => $value]) ||
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