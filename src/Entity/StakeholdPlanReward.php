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
    private $firstPaymentDate;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $lastPaymentDate;

    /**
     * @var string
     * @ORM\Column(type="string", length=6, unique=true, nullable=false)
     */
    private $reference;

    /**
     * StakeholdPlanReward constructor.
     * @param StakeholdPlan $plan
     * @param string $rate
     * @param DateTime $firstPaymentDate
     * @param DateTime $lastPaymentDate
     */
    public function __construct(StakeholdPlan $plan, string $rate, DateTime $firstPaymentDate, DateTime $lastPaymentDate)
    {
        $this->plan = $plan;
        $this->rate = $rate;
        $this->setFirstPaymentDate($firstPaymentDate);
        $this->lastPaymentDate = $lastPaymentDate;

        $this->reference = $firstPaymentDate->format('Ym');
    }

    public function getId(): ?int
    {
        return $this->id;
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
