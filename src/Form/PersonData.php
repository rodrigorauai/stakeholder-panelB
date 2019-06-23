<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class PersonData
{
    /**
     * @var null|string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3", minMessage="Informe seu nome completo",
     *     max="64", maxMessage="O nome não pode ter mais de 64 caracteres"
     * )
     */
    public $name;

    /**
     * @var null|string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email(checkHost=false, checkMX=false, message="E-mail inválido")
     */
    public $email;
}