<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var Entity
     * @ORM\OneToOne(targetEntity="Entity", inversedBy="bankAccount")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\NotBlank()
     */
    private $bankCode;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank()
     */
    private $agency;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     * @Assert\Choice(choices={true, false})
     */
    private $naturalPerson;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $holderName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $holderDocumentNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notes;

    /**
     * @var Withdraw[]|Collection
     * @ORM\OneToMany(targetEntity="Withdraw", mappedBy="bankAccount")
     */
    private $withdraws;

    public function __construct(
        ?string $bank,
        ?string $bankCode,
        ?string $agency,
        ?string $number,
        ?string $type,
        ?bool $naturalPerson,
        ?string $holderName,
        ?string $holderDocumentNumber
    ) {
        $this->bank = $bank;
        $this->bankCode = $bankCode;
        $this->agency = $agency;
        $this->number = $number;
        $this->type = $type;
        $this->naturalPerson = $naturalPerson;
        $this->holderName = $holderName;
        $this->holderDocumentNumber = $holderDocumentNumber;

        $this->withdraws = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Withdraw[]|Collection
     */
    public function getWithdraws()
    {
        return $this->withdraws;
    }
}
