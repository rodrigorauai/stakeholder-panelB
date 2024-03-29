<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityRepository")
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 */
abstract class Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var null|Account
     * @ORM\OneToOne(targetEntity="Account", mappedBy="owner", cascade={"persist"})
     */
    protected $account;

    /**
     * @var null|BankAccount
     * @ORM\OneToOne(targetEntity="BankAccount", mappedBy="owner")
     */
    protected $bankAccount;

    /**
     * @var null|Address
     * @ORM\OneToOne(targetEntity="Address", mappedBy="entity", cascade={"persist"})
     */
    private $address;

    /**
     * @var null|Person
     * @ORM\ManyToOne(targetEntity="Person")
     */
    protected $tradeRepresentative;

    /**
     * AbstractEntity constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public abstract function getDocumentNumber(): string;

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     */
    public function setAccount(?Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return BankAccount|null
     */
    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    /**
     * @param BankAccount|null $bankAccount
     */
    public function setBankAccount(?BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return null|Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param null|Address $address
     */
    public function setAddress(?Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return Person|null
     */
    public function getTradeRepresentative(): ?Person
    {
        return $this->tradeRepresentative;
    }

    /**
     * @param Person|null $tradeRepresentative
     */
    public function setTradeRepresentative(?Person $tradeRepresentative)
    {
        $this->tradeRepresentative = $tradeRepresentative;
    }
}
