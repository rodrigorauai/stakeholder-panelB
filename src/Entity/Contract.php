<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractRepository")
 */
class Contract
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="contracts")
     */
    private $account;

    /**
     * @var StakeholdPlan
     * @ORM\ManyToOne(targetEntity="StakeholdPlan", inversedBy="contracts")
     */
    private $plan;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $value;

    /**
     * The date on which the contract was signed
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $executionDate;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $firstReturnDate;

    /**
     * @var null|DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expirationDate;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    /**
     * Contract constructor.
     * @param StakeholdPlan $plan
     * @param string $value
     * @param DateTime $executionDate
     * @param DateTime $firstReturnDate
     * @param null|DateTime $expirationDate
     * @throws Exception
     */
    public function __construct(
        ?StakeholdPlan $plan,
        ?string $value,
        ?DateTime $executionDate,
        ?DateTime $firstReturnDate,
        ?DateTime $expirationDate
    ) {
        $this->plan = $plan;
        $this->value = $value;
        $this->executionDate = $executionDate;
        $this->firstReturnDate = $firstReturnDate;
        $this->expirationDate = $expirationDate;

        $this->creationTimestamp = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account)
    {
        $this->account = $account;
    }

    public function getPlan(): ?StakeholdPlan
    {
        return $this->plan;
    }

    public function setPlan(?StakeholdPlan $plan)
    {
        $this->plan = $plan;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getExecutionDate(): DateTime
    {
        return $this->executionDate;
    }

    /**
     * @param DateTime $executionDate
     */
    public function setExecutionDate(DateTime $executionDate)
    {
        $this->executionDate = $executionDate;
    }

    public function getFirstReturnDate(): ?DateTime
    {
        return $this->firstReturnDate;
    }

    public function setFirstReturnDate(?DateTime $firstReturnDate)
    {
        $this->firstReturnDate = $firstReturnDate;
    }

    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public function getCreationTimestamp(): ?DateTime
    {
        return $this->creationTimestamp;
    }

    public function setCreationTimestamp(?DateTime $creationTimestamp)
    {
        $this->creationTimestamp = $creationTimestamp;
    }
}
