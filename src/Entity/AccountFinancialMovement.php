<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountFinancialMovementRepository")
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class AccountFinancialMovement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="financialMovements")
     */
    private $account;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $value;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    public function __construct(Account $account, string $value)
    {
        $this->account = $account;
        $this->value = $value;

        $this->timestamp = new DateTime();
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
}
