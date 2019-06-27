<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment extends AccountFinancialMovement
{
    /**
     * @var StakeholdPlanReward
     * @ORM\ManyToOne(targetEntity="StakeholdPlanReward")
     */
    private $reward;

    /**
     * @var Contract
     * @ORM\ManyToOne(targetEntity="Contract")
     */
    private $contract;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    private $provenance;

    const PROVENANCE_CO_PARTICIPATION = 'co-participation';

    const PROVENANCE_COMMISSION = 'commission';

    /**
     * @var null|string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoiceUrl;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isRealized;

    public function __construct(
        Account $account,
        string $value,
        StakeholdPlanReward $reward,
        Contract $contract,
        string $provenance
    ) {
        parent::__construct($account, $value);

        $this->reward = $reward;
        $this->contract = $contract;
        $this->provenance = $provenance;

        $this->isRealized = false;
    }

    /**
     * @return StakeholdPlanReward
     */
    public function getReward(): StakeholdPlanReward
    {
        return $this->reward;
    }

    /**
     * @return Contract
     */
    public function getContract(): Contract
    {
        return $this->contract;
    }

    /**
     * @return Account
     */
    public function getBeneficiary(): Account
    {
        return $this->getAccount();
    }

    /**
     * @return string
     */
    public function getProvenance(): string
    {
        return $this->provenance;
    }

    public function needsInvoice(): bool
    {
        if ($this->getBeneficiary()->getOwner() instanceof Company) {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getInvoiceUrl(): ?string
    {
        return $this->invoiceUrl;
    }

    /**
     * @param string|null $invoiceUrl
     */
    public function setInvoiceUrl(?string $invoiceUrl)
    {
        $this->invoiceUrl = $invoiceUrl;
    }

    /**
     * @return bool
     */
    public function isRealized(): bool
    {
        return $this->isRealized;
    }

    /**
     * @param bool $isRealized
     * @throws Exception
     */
    public function setIsRealized(bool $isRealized)
    {
        if ($this->needsInvoice() && null === $this->getInvoiceUrl()) {
            throw new Exception('This payment needs a invoice before being set as paid');
        }

        $this->isRealized = $isRealized;
    }
}
