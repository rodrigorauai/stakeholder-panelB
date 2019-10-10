<?php

namespace App\Form;

use App\Entity\Configuration;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigurationData
{
    /**
     * @Assert\NotBlank()
     */
    public $currency;


    public static function fromEntity(Configuration $config): ConfigurationData
    {
        $data = new ConfigurationData();
        $data->currency = $config->getCurrency();

        return $data;
    }
}