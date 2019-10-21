<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StakeholdPlanRepository")
 */
class StakeholdPlan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome privado não pode ter mais de 255 caracteres.")
     */
    private $administrativeName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome comercial não pode ter mais de 255 caracteres.")
     */
    private $commercialName;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 7 inteiros e 2 decimais")
     * @Assert\Range(min="0.01", max="9999999.99")
     */
    private $minimumValue;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 7 inteiros e 2 decimais")
     * @Assert\Range(min="0.01", max="9999999.99")
     */
    private $valueMultiple;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     * @Assert\Range(min="1", max="28")
     */
    private $rewardDay;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     */
    private $gracePeriod;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\d+/", message="Utilize apenas números")
     * @Assert\Range(min="1", max="28")
     */
    private $bestAcquisitionDay;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="-99.99", max="99,99")
     */
    private $monthlyCommission;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="-99.99", max="99,99")
     */
    private $monthlyAdministrativeFee;

    /**
     * @var Contract[]|Collection
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="plan")
     */
    private $contracts;

    /**
     * @var StakeholdPlanReward[]|Collection
     * @ORM\OneToMany(targetEntity="StakeholdPlanReward", mappedBy="plan")
     */
    private $rewards;

    /**
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $monthlyRate;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     * @Assert\Length(max="11", maxMessage="Utilize até 2 inteiros e 2 decimais")
     * @Assert\Range(min="-99.99", max="99,99")
     */
    private $monthlyPercenteRate;

    /**
     * StakeholdingPlan constructor.
     * @param string $administrativeName
     * @param string $commercialName
     * @param string $minimumValue
     * @param string $valueMultiple
     * @param int $rewardDay
     * @param int $gracePeriod
     * @param int $bestAcquisitionDay
     * @param string $monthlyCommission
     * @param string $monthlyAdministrativeFee
     * @param string $monthlyPercenteRate
     * @param DateTime $monthlyRate
     * @throws Exception
     */
    public function __construct(
        ?string $administrativeName,
        ?string $commercialName,
        ?string $minimumValue,
        ?string $valueMultiple,
        ?int $rewardDay,
        ?int $gracePeriod,
        ?int $bestAcquisitionDay,
        ?string $monthlyCommission,
        ?string $monthlyAdministrativeFee,
        ?DateTime $monthlyRate,
        ?string $monthlyPercenteRate
    ) {
        $this->administrativeName = $administrativeName;
        $this->commercialName = $commercialName;
        $this->minimumValue = $minimumValue;
        $this->valueMultiple = $valueMultiple;
        $this->rewardDay = $rewardDay;
        $this->gracePeriod = $gracePeriod;
        $this->bestAcquisitionDay = $bestAcquisitionDay;
        $this->monthlyCommission = $monthlyCommission;
        $this->monthlyAdministrativeFee = $monthlyAdministrativeFee;
        $this->monthlyPercenteRate = $monthlyPercenteRate;
        $this->monthlyRate = $monthlyRate;

        $this->contracts = new ArrayCollection();
        $this->rewards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAdministrativeName(): string
    {
        return $this->administrativeName;
    }

    /**
     * @param string $administrativeName
     */
    public function setAdministrativeName(string $administrativeName)
    {
        $this->administrativeName = $administrativeName;
    }

    /**
     * @return string
     */
    public function getCommercialName(): string
    {
        return $this->commercialName;
    }

    /**
     * @param string $commercialName
     */
    public function setCommercialName(string $commercialName)
    {
        $this->commercialName = $commercialName;
    }

    public function getMinimumValue()
    {
        return $this->minimumValue;
    }

    public function setMinimumValue($minimumValue): self
    {
        $this->minimumValue = $minimumValue;

        return $this;
    }

    public function getValueMultiple()
    {
        return $this->valueMultiple;
    }

    public function setValueMultiple($valueMultiple): self
    {
        $this->valueMultiple = $valueMultiple;

        return $this;
    }

    public function getRewardDay(): ?int
    {
        return $this->rewardDay;
    }

    public function setRewardDay(int $rewardDay): self
    {
        $this->rewardDay = $rewardDay;

        return $this;
    }

    public function getGracePeriod(): ?int
    {
        return $this->gracePeriod;
    }

    public function setGracePeriod(int $gracePeriod): self
    {
        $this->gracePeriod = $gracePeriod;

        return $this;
    }

    public function getBestAcquisitionDay(): ?int
    {
        return $this->bestAcquisitionDay;
    }

    public function setBestAcquisitionDay(int $bestAcquisitionDay): self
    {
        $this->bestAcquisitionDay = $bestAcquisitionDay;

        return $this;
    }

    public function getMonthlyCommission()
    {
        return $this->monthlyCommission;
    }

    public function setMonthlyCommission($monthlyCommission): self
    {
        $this->monthlyCommission = $monthlyCommission;

        return $this;
    }

    public function getMonthlyAdministrativeFee()
    {
        return $this->monthlyAdministrativeFee;
    }

    public function setMonthlyAdministrativeFee($monthlyAdministrativeFee): self
    {
        $this->monthlyAdministrativeFee = $monthlyAdministrativeFee;

        return $this;
    }

    public function addContract(Contract $contract)
    {
        $this->contracts->add($contract);
    }

    /**
     * @return Contract[]|Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @return StakeholdPlanReward[]|Collection
     */
    public function getRewards()
    {
        return $this->rewards;
    }


    /**
     * @return DateTime
     * @throws Exception
     */
    public function getMonthlyRate(): DateTime
    {
        return $this->monthlyRate;
    }

    /**
     * @param DateTime $monthlyRate
     */
    public function setMonthlyRate(DateTime $monthlyRate): void
    {
        $this->monthlyRate = $monthlyRate;
    }

    public function getMonthlyPercenteRate()
    {
        return $this->monthlyPercenteRate;
    }

    public function setMonthlyPercenteRate($monthlyPercenteRate): self
    {
        $this->monthlyPercenteRate = $monthlyPercenteRate;

        return $this;
    }



}
