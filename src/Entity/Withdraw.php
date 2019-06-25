<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WithdrawRepository")
 */
class Withdraw extends AccountFinancialMovement
{
    /**
     * @var BankAccount
     * @ORM\ManyToOne(targetEntity="BankAccount", inversedBy="withdraws")
     */
    private $bankAccount;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="withdrawRequests")
     */
    private $requester;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    private $status;

    const STATUS_WAITING = 'waiting';

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $requestTimestamp;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person")
     */
    private $operator;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $operationTimestamp;

    public function __construct(Account $account, string $value, BankAccount $bankAccount, Person $requester)
    {
        parent::__construct($account, $value);

        $this->bankAccount = $bankAccount;
        $this->requester = $requester;

        $this->status = self::STATUS_WAITING;

        $this->requestTimestamp = new DateTime();
    }

    /**
     * @return BankAccount
     */
    public function getBankAccount(): BankAccount
    {
        return $this->bankAccount;
    }

    /**
     * @return Person
     */
    public function getRequester(): Person
    {
        return $this->requester;
    }

    /**
     * @return DateTime
     */
    public function getRequestTimestamp(): DateTime
    {
        return $this->requestTimestamp;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return Person
     */
    public function getOperator(): Person
    {
        return $this->operator;
    }

    /**
     * @param Person $operator
     */
    public function setOperator(Person $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return DateTime
     */
    public function getOperationTimestamp(): DateTime
    {
        return $this->operationTimestamp;
    }

    /**
     * @param DateTime $operationTimestamp
     */
    public function setOperationTimestamp(DateTime $operationTimestamp)
    {
        $this->operationTimestamp = $operationTimestamp;
    }
}
