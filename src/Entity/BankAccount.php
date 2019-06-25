<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankAccountRepository")
 */
class BankAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var AbstractEntity
     * @ORM\OneToOne(targetEntity="AbstractEntity", inversedBy="account")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $bankCode;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $agency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $naturalPerson;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $holderName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $holderDocumentNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notes;

    public function __construct(
        AbstractEntity $owner,
        string $bank,
        string $bankCode,
        string $agency,
        string $number,
        string $type,
        bool $naturalPerson,
        string $holderName,
        string $holderDocumentNumber
    ) {
        $this->owner = $owner;
        $this->bank = $bank;
        $this->bankCode = $bankCode;
        $this->agency = $agency;
        $this->number = $number;
        $this->type = $type;
        $this->naturalPerson = $naturalPerson;
        $this->holderName = $holderName;
        $this->holderDocumentNumber = $holderDocumentNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(string $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function setBankCode(?string $bankCode): self
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    public function getAgency(): ?string
    {
        return $this->agency;
    }

    public function setAgency(string $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNaturalPerson(): ?bool
    {
        return $this->naturalPerson;
    }

    public function setNaturalPerson(bool $naturalPerson): self
    {
        $this->naturalPerson = $naturalPerson;

        return $this;
    }

    public function getHolderName(): ?string
    {
        return $this->holderName;
    }

    public function setHolderName(string $holderName): self
    {
        $this->holderName = $holderName;

        return $this;
    }

    public function getHolderDocumentNumber(): ?string
    {
        return $this->holderDocumentNumber;
    }

    public function setHolderDocumentNumber(string $holderDocumentNumber): self
    {
        $this->holderDocumentNumber = $holderDocumentNumber;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
