<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StakeholdPlanRewardRepository")
 * @ORM\EntityListeners(value={"App\EntityListener\StakeholdPlanRewardListener"})
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
    private $paymentDueDate;

    public function __construct(string $rate, DateTime $disclosureDate, DateTime $paymentDueDate)
    {
        $this->rate = $rate;
        $this->disclosureDate = $disclosureDate;
        $this->paymentDueDate = $paymentDueDate;
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
    public function getPaymentDueDate(): DateTime
    {
        return $this->paymentDueDate;
    }

    /**
     * @param DateTime $paymentDueDate
     */
    public function setPaymentDueDate(DateTime $paymentDueDate)
    {
        $this->paymentDueDate = $paymentDueDate;
    }
}
