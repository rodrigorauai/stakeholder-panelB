<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isEntitledToRefund = false;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    /**
     * @ORM\OneToMany(targetEntity="UploadedDigitizedContractFile", mappedBy="contract")
     */
    private $digitizedContracts;

    /**
     * Contract constructor.
     * @param StakeholdPlan $plan
     * @param string $value
     * @param DateTime $executionDate
     * @param DateTime $firstReturnDate
     * @param null|DateTime $expirationDate
     * @throws Exception
     */
    public function __construct(
        ?StakeholdPlan $plan,
        ?string $value,
        ?DateTime $executionDate,
        ?DateTime $firstReturnDate,
        ?DateTime $expirationDate
    ) {
        $this->plan = $plan;
        $this->value = $value;
        $this->executionDate = $executionDate;
        $this->firstReturnDate = $firstReturnDate;
        $this->expirationDate = $expirationDate;

        $this->creationTimestamp = new DateTime();
        $this->digitizedContracts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account)
    {
        $this->account = $account;
    }

    public function getPlan(): ?StakeholdPlan
    {
        return $this->plan;
    }

    public function setPlan(?StakeholdPlan $plan)
    {
        $this->plan = $plan;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getExecutionDate(): DateTime
    {
        return $this->executionDate;
    }

    /**
     * @param DateTime $executionDate
     */
    public function setExecutionDate(DateTime $executionDate)
    {
        $this->executionDate = $executionDate;
    }

    public function getFirstReturnDate(): ?DateTime
    {
        return $this->firstReturnDate;
    }

    public function setFirstReturnDate(?DateTime $firstReturnDate)
    {
        $this->firstReturnDate = $firstReturnDate;
    }

    public function getExpirationDate(): ?DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return bool
     */
    public function isEntitledToRefund(): bool
    {
        return $this->isEntitledToRefund ?? false;
    }

    /**
     * @param bool $isEntitledToRefund
     */
    public function setIsEntitledToRefund(bool $isEntitledToRefund)
    {
        $this->isEntitledToRefund = $isEntitledToRefund;
    }

    public function getCreationTimestamp(): ?DateTime
    {
        return $this->creationTimestamp;
    }

    public function setCreationTimestamp(?DateTime $creationTimestamp)
    {
        $this->creationTimestamp = $creationTimestamp;
    }

    /**
     * @return Collection|UploadedDigitizedContractFile[]
     */
    public function getDigitizedContracts(): Collection
    {
        return $this->digitizedContracts;
    }

    public function addDigitizedContract(UploadedDigitizedContractFile $digitizedContract): self
    {
        if (!$this->digitizedContracts->contains($digitizedContract)) {
            $this->digitizedContracts[] = $digitizedContract;
            $digitizedContract->setContract($this);
        }

        return $this;
    }

    public function removeDigitizedContract(UploadedDigitizedContractFile $digitizedContract): self
    {
        if ($this->digitizedContracts->contains($digitizedContract)) {
            $this->digitizedContracts->removeElement($digitizedContract);
            // set the owning side to null (unless already changed)
            if ($digitizedContract->getContract() === $this) {
                $digitizedContract->setContract(null);
            }
        }

        return $this;
    }
}
