<?php

namespace App\Form;

use App\Entity\Configuration;
use App\Entity\ConfigurationTranslate;
use Symfony\Component\Validator\Constraints as Assert;

class TranslateData
{
    /**
     * @Assert\NotBlank()
     */
    public $translate;


    public static function fromEntity(ConfigurationTranslate $config): TranslateData
    {
        $data = new TranslateData();
        $data->translate = $config->getTranslate();

        return $data;
    }
}