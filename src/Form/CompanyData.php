<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\Form;


use App\Entity\Company;
use App\Validator\CnpjFormat;
use App\Validator\CnpjNumbers;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyData
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome não pode ter mais de 255 caracteres")
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @CnpjFormat()
     * @CnpjNumbers()
     */
    public $cnpj;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="\App\Entity\Person")
     */
    public $manager;

    public static function fromEntity(Company $company): CompanyData
    {
        $data = new CompanyData();
        $data->name = $company->getName();
        $data->cnpj = $company->getCnpj();

        if ($company->getManagers()->count() !== 0) {
            $data->manager = $company->getManagers()->get(0);
        }

        return $data;
    }
}