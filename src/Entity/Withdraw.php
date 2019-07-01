<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var null|Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="withdrawRequests", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $requester;

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
     * @var null|DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $operationTimestamp;

    /**
     * @ORM\OneToMany(targetEntity="UploadedReceiptFile", mappedBy="withdraw", cascade={"persist"})
     */
    private $receipts;

    const STATUS_PENDING = 'Pending';

    const STATUS_EXECUTED = 'Executed';

    public function __construct(Account $account, string $value, BankAccount $bankAccount, ?Person $requester)
    {
        parent::__construct($account, $value);

        $this->bankAccount = $bankAccount;
        $this->requester = $requester;

        $this->getAccount()->subtractBalance($value);

        $this->requestTimestamp = new DateTime();
        $this->receipts = new ArrayCollection();
    }

    /**
     * @return BankAccount
     */
    public function getBankAccount(): BankAccount
    {
        return $this->bankAccount;
    }

    public function getRequester(): ?Person
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
        if ($this->getExecutionTimestamp()) {
            return self::STATUS_EXECUTED;
        }

        return self::STATUS_PENDING;
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

    public function getOperationTimestamp(): ?DateTime
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

    public function wasExecuted()
    {
        return !is_null($this->getExecutionTimestamp());
    }

    public function setExecuted(Person $operator)
    {
        $this->operator = $operator;
        $this->setOperationTimestamp(new DateTime());
        $this->setExecutionTimestamp(new DateTime());
    }

    /**
     * @return Collection|UploadedReceiptFile[]
     */
    public function getReceipts(): Collection
    {
        return $this->receipts;
    }

    public function getReceipt(): ?UploadedReceiptFile
    {
        return $this->receipts->last();
    }

    public function addReceiptFile(UploadedReceiptFile $receipt): self
    {
        if (!$this->receipts->contains($receipt)) {
            $this->receipts[] = $receipt;
            $receipt->setWithdraw($this);
        }

        return $this;
    }

    public function removeReceiptFile(UploadedReceiptFile $receipt): self
    {
        if ($this->receipts->contains($receipt)) {
            $this->receipts->removeElement($receipt);
            // set the owning side to null (unless already changed)
            if ($receipt->getWithdraw() === $this) {
                $receipt->setWithdraw(null);
            }
        }

        return $this;
    }
}
