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
     * @param Account $account
     * @param StakeholdPlan $plan
     * @param string $value
     * @param DateTime $executionDate
     * @param DateTime $firstReturnDate
     * @param null|DateTime $expirationDate
     * @throws Exception
     */
    public function __construct(
        Account $account,
        StakeholdPlan $plan,
        string $value,
        DateTime $executionDate,
        ?DateTime $firstReturnDate,
        DateTime $expirationDate
    ) {
        $this->account = $account;
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

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getFirstReturnDate(): DateTime
    {
        return $this->firstReturnDate;
    }

    /**
     * @param DateTime $firstReturnDate
     */
    public function setFirstReturnDate(DateTime $firstReturnDate)
    {
        $this->firstReturnDate = $firstReturnDate;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    /**
     * @param DateTime $expirationDate
     */
    public function setExpirationDate(DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return Person
     */
    public function getRequester(): Person
    {
        return $this->requester;
    }

    /**
     * @param Person $requester
     */
    public function setRequester(Person $requester)
    {
        $this->requester = $requester;
    }

    /**
     * @return Person
     */
    public function getApprover(): Person
    {
        return $this->approver;
    }

    /**
     * @param Person $approver
     */
    public function setApprover(Person $approver)
    {
        $this->approver = $approver;
    }

    /**
     * @return DateTime
     */
    public function getCreationTimestamp(): DateTime
    {
        return $this->creationTimestamp;
    }

    /**
     * @param DateTime $creationTimestamp
     */
    public function setCreationTimestamp(DateTime $creationTimestamp)
    {
        $this->creationTimestamp = $creationTimestamp;
    }
}
