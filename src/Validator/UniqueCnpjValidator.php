<?php
namespace App\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\CompanyRepository;

class UniqueCnpjValidator extends ConstraintValidator
{
    /**
     * @var CompanyRepository
     */
    // private PersonRepository $personRepository;
    private $companyRepository;
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint UniqueCnpj */
        if (null === $value || '' === $value) {
            return;
        }
        $existingCnpj = $this->companyRepository->findOneBy(['cnpj' => $value]);

        if (!$existingCnpj) {
            return;
        }
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}