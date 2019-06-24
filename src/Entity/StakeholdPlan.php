<?php

namespace App\Entity;

use App\Form\StakeholdPlanData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;

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
     */
    private $administrativeName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $commercialName;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $minimumValue;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valueMultiple;

    /**
     * @ORM\Column(type="integer")
     */
    private $firstDayOfMonthlyPayment;

    /**
     * @ORM\Column(type="integer")
     */
    private $lastDayOfMonthlyPayment;

    /**
     * @ORM\Column(type="integer")
     */
    private $gracePeriod;

    /**
     * @ORM\Column(type="integer")
     */
    private $bestAcquisitionDay;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $monthlyCommission;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $monthlyAdministrativeFee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $yieldFixed;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $monthlyYield;

    /**
     * @var Contract[]|Collection
     */
    private $contracts;

    /**
     * StakeholdingPlan constructor.
     * @param string $administrativeName
     * @param string $commercialName
     * @param string $minimumValue
     * @param string $valueMultiple
     * @param int $firstDayOfMonthlyPayment
     * @param int $lastDayOfMonthlyPayment
     * @param int $gracePeriod
     * @param int $bestAcquisitionDay
     * @param string $monthlyCommission
     * @param string $monthlyAdministrativeFee
     * @param bool $yieldFixed
     * @param string|null $monthlyYield
     * @throws Exception
     */
    public function __construct(
        string $administrativeName,
        string $commercialName,
        string $minimumValue,
        string $valueMultiple,
        int $firstDayOfMonthlyPayment,
        int $lastDayOfMonthlyPayment,
        int $gracePeriod,
        int $bestAcquisitionDay,
        string $monthlyCommission,
        string $monthlyAdministrativeFee,
        bool $yieldFixed,
        ?string $monthlyYield
    ) {
        $this->administrativeName = $administrativeName;
        $this->commercialName = $commercialName;
        $this->minimumValue = $minimumValue;
        $this->valueMultiple = $valueMultiple;
        $this->firstDayOfMonthlyPayment = $firstDayOfMonthlyPayment;
        $this->lastDayOfMonthlyPayment = $lastDayOfMonthlyPayment;
        $this->gracePeriod = $gracePeriod;
        $this->bestAcquisitionDay = $bestAcquisitionDay;
        $this->monthlyCommission = $monthlyCommission;
        $this->monthlyAdministrativeFee = $monthlyAdministrativeFee;
        $this->yieldFixed = $yieldFixed;
        $this->monthlyYield = $monthlyYield;

        if ($yieldFixed && null === $monthlyYield) {
            throw new Exception('Monthly yield must be defined when yield is fixed');
        }

        $this->contracts = new ArrayCollection();
    }

    /**
     * @param StakeholdPlanData $data
     * @return StakeholdPlan
     * @throws Exception
     */
    public static function fromDataObject(StakeholdPlanData $data)
    {
        return new StakeholdPlan(
            $data->administrativeName,
            $data->commercialName,
            $data->minimumValue,
            $data->valueMultiple,
            $data->firstDayOfMonthlyPayment,
            $data->lastDayOfMonthlyPayment,
            $data->gracePeriod,
            $data->bestAcquisitionDay,
            $data->monthlyCommission,
            $data->monthlyAdministrativeFee,
            $data->yieldFixed,
            $data->monthlyYield
        );
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

    public function getFirstDayOfMonthlyPayment(): ?int
    {
        return $this->firstDayOfMonthlyPayment;
    }

    public function setFirstDayOfMonthlyPayment(int $firstDayOfMonthlyPayment): self
    {
        $this->firstDayOfMonthlyPayment = $firstDayOfMonthlyPayment;

        return $this;
    }

    public function getLastDayOfMonthlyPayment(): ?int
    {
        return $this->lastDayOfMonthlyPayment;
    }

    public function setLastDayOfMonthlyPayment(int $lastDayOfMonthlyPayment): self
    {
        $this->lastDayOfMonthlyPayment = $lastDayOfMonthlyPayment;

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

    public function isYieldFixed(): ?bool
    {
        return $this->yieldFixed;
    }

    public function setYieldFixed(bool $yieldFixed): self
    {
        $this->yieldFixed = $yieldFixed;

        return $this;
    }

    public function getMonthlyYield()
    {
        return $this->monthlyYield;
    }

    public function setMonthlyYield($monthlyYield): self
    {
        $this->monthlyYield = $monthlyYield;

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
}
