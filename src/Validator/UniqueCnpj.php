<?php
namespace App\Validator;
use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class UniqueCnpj extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'CNPJ já cadastrado!';
}