<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\Form;


use Symfony\Component\Validator\Constraints as Assert;

class StakeholdPlanData
{
    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome não pode ter mais de 255 caracteres.")
     */
    public $administrativeName;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome não pode ter mais de 255 caracteres.")
     */
    public $commercialName;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 7 inteiros e 2 decimais")
     * @Assert\Range(min="0.01", max="9999999.99")
     */
    public $minimumValue;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 7 inteiros e 2 decimais")
     * @Assert\Range(min="0.01", max="9999999.99")
     */
    public $valueMultiple;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     * @Assert\Range(min="1", max="28")
     */
    public $firstDayOfMonthlyPayment;


    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     * @Assert\Range(min="1", max="28")
     */
    public $lastDayOfMonthlyPayment;


    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     */
    public $gracePeriod;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     * @Assert\Range(min="1", max="28")
     */
    public $bestAcquisitionDay;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="0.00", max="99,99")
     */
    public $monthlyCommission;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="0.00", max="99,99")
     */
    public $monthlyAdministrativeFee;

    /**
     * @Assert\Type(type="boolean")
     */
    public $yieldFixed;

    /**
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="0.01", max="99,99")
     */
    public $monthlyYield;
}