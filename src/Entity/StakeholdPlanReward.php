<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StakeholdPlanRewardRepository")
 */
class StakeholdPlanReward
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var StakeholdPlan
     * @ORM\ManyToOne(targetEntity="StakeholdPlan", inversedBy="rewards")
     */
    private $plan;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $rate;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $disclosureDate;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $firstPaymentDate;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $lastPaymentDate;

    public function __construct(string $rate, DateTime $disclosureDate, DateTime $firstPaymentDate, DateTime $lastPaymentDate)
    {
        $this->rate = $rate;
        $this->disclosureDate = $disclosureDate;
        $this->setFirstPaymentDate($firstPaymentDate);
        $this->lastPaymentDate = $lastPaymentDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return StakeholdPlan
     */
    public function getPlan(): StakeholdPlan
    {
        return $this->plan;
    }

    /**
     * @param StakeholdPlan $plan
     */
    public function setPlan(StakeholdPlan $plan)
    {
        $this->plan = $plan;
    }

    /**
     * @return string
     */
    public function getRate(): string
    {
        return $this->rate;
    }

    /**
     * @param string $rate
     */
    public function setRate(string $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return DateTime
     */
    public function getDisclosureDate(): DateTime
    {
        return $this->disclosureDate;
    }

    /**
     * @param DateTime $disclosureDate
     */
    public function setDisclosureDate(DateTime $disclosureDate)
    {
        $this->disclosureDate = $disclosureDate;
    }

    /**
     * @return DateTime
     */
    public function getFirstPaymentDate(): DateTime
    {
        return $this->firstPaymentDate;
    }

    /**
     * @param DateTime $firstPaymentDate
     */
    public function setFirstPaymentDate(DateTime $firstPaymentDate)
    {
        $this->firstPaymentDate = $firstPaymentDate;
        $this->reference = $firstPaymentDate->format('Ym');
    }

    /**
     * @return DateTime
     */
    public function getLastPaymentDate(): DateTime
    {
        return $this->lastPaymentDate;
    }

    /**
     * @param DateTime $lastPaymentDate
     */
    public function setLastPaymentDate(DateTime $lastPaymentDate)
    {
        $this->lastPaymentDate = $lastPaymentDate;
    }


}
