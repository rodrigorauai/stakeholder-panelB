<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $balance;

    /**
     * @var Entity
     * @ORM\OneToOne(targetEntity="Entity", inversedBy="account")
     */
    private $owner;

    /**
     * @var Contract[]|Collection
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="account")
     */
    private $contracts;

    /**
     * @var AccountFinancialMovement|Collection
     * @ORM\OneToMany(targetEntity="AccountFinancialMovement", mappedBy="account")
     */
    private $financialMovements;

    /**
     * Account constructor.
     * @param Entity $owner
     */
    public function __construct(Entity $owner)
    {
        $this->balance = '0.00';
        $this->owner = $owner;

        $this->contracts = new ArrayCollection();
        $this->financialMovements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     */
    public function setBalance(string $balance)
    {
        $this->balance = $balance;
    }

    public function addBalance(string $value)
    {
        $this->balance = bcadd($this->balance, $value, 2);
    }

    public function subtractBalance(string $value)
    {
        $this->balance = bcsub($this->balance, $value, 2);
    }

    /**
     * @return Entity
     */
    public function getOwner(): Entity
    {
        return $this->owner;
    }

    /**
     * @param Entity $owner
     */
    public function setOwner(Entity $owner)
    {
        $this->owner = $owner;
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
