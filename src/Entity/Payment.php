<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment extends AccountFinancialMovement
{
    /**
     * @var StakeholdPlanReward
     * @ORM\ManyToOne(targetEntity="StakeholdPlanReward", inversedBy="payments")
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
    private $wasMade;

    const STATUS_MADE = 'Made';

    const STATUS_SCHEDULED = 'Scheduled';

    const STATUS_WAITING_INVOICE = 'Waiting Invoice';

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

        $this->wasMade = false;
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

    public function hasInvoice(): bool
    {
        return null !== $this->invoiceUrl;
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
    public function wasMade(): bool
    {
        return $this->wasMade;
    }

    /**
     * @param bool $wasMade
     * @throws Exception
     */
    public function setWasMade(bool $wasMade)
    {
        if ($this->needsInvoice() && !$this->hasInvoice()) {
            throw new Exception('This payment needs a invoice before being set as paid');
        }

        $this->setExecutionTimestamp(new DateTime());
        $this->wasMade = $wasMade;
    }

    public function getStatus(): string
    {
        if ($this->needsInvoice() && !$this->hasInvoice()) {
            return self::STATUS_WAITING_INVOICE;
        } elseif (!$this->wasMade()) {
            return self::STATUS_SCHEDULED;
        } else {
            return self::STATUS_MADE;
        }
    }

    public function canBeMade(): bool
    {
        if ($this->wasMade) {
            return false;
        }

        if ($this->getStatus() === self::STATUS_WAITING_INVOICE) {
            return false;
        }

        return true;
    }

    public function setValue(string $value)
    {
        parent::setValue($value);
    }
}
