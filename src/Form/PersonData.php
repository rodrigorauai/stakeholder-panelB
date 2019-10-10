<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\Form;

use App\Entity\Person;
use App\Validator\CpfFormat;
use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class PersonData
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3", minMessage="Informe seu nome completo",
     *     max="64", maxMessage="O nome não pode ter mais de 64 caracteres."
     * )
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @CpfFormat()
     */
    public $cpf;

    /**
     * @Assert\Length(max="16", maxMessage="O RG não pode ter mais de 16 caracteres.")
     */
    public $rg;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(checkHost=true, checkMX=true, message="E-mail inválido.")
     */
    public $email;

    /**
     * @Assert\Length(
     *     max=13, maxMessage="O telefone deve ter no máximo 13 dígitos.",
     *     min=10, minMessage="O telefone deve ter no mínimo 10 dígitos.",
     * )
     */
    public $phone;

    /**
     * @var null|Person
     */
    public $tradeRepresentative;

    public static function fromEntity(Person $person): PersonData
    {
        $data = new PersonData();
        $data->name = $person->getName();
        $data->cpf = $person->getCpf();
        $data->rg = $person->getRg();
        $data->email = $person->getEmail();
        $data->phone = $person->getPhone();
        $data->tradeRepresentative = $person->getTradeRepresentative();

        return $data;
    }
}