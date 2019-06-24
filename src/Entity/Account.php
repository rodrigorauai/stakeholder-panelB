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
     * @var AbstractEntity
     * @ORM\OneToOne(targetEntity="AbstractEntity", inversedBy="account")
     */
    private $owner;

    /**
     * @var Contract[]|Collection
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="account")
     */
    private $contracts;

    /**
     * Account constructor.
     * @param AbstractEntity $owner
     */
    public function __construct(AbstractEntity $owner)
    {
        $this->balance = '0.00';
        $this->owner = $owner;

        $this->contracts = new ArrayCollection();
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

    /**
     * @return AbstractEntity
     */
    public function getOwner(): AbstractEntity
    {
        return $this->owner;
    }

    /**
     * @param AbstractEntity $owner
     */
    public function setOwner(AbstractEntity $owner)
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
