<?php

namespace App\Entity;

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
     * Account constructor.
     * @param AbstractEntity $owner
     */
    public function __construct(AbstractEntity $owner)
    {
        $this->balance = '0.00';
        $this->owner = $owner;
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
}
