<?php

namespace App\Entity;

use App\Repository\TranslateRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    const PROVENANCE_CO_PARTICIPATION_USN = 'co-participation';

    const PROVENANCE_COMMISSION = 'commission';

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $wasMade;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PaymentInvoice", mappedBy="payment")
     */
    private $invoices;

    /**
     * @var PaymentInvoice|null
     */
    private $invoice;

    const STATUS_MADE = 'Made';

    const STATUS_MADE_USN = 'MadeUSN';

    const STATUS_SCHEDULED = 'Scheduled';

    const STATUS_SCHEDULED_USN = 'ScheduledUSN';

    const STATUS_WAITING_INVOICE = 'Waiting Invoice';

    const STATUS_WAITING_INVOICE_USN = 'Waiting InvoiceUSN';

    const STATUS_WAITING_INVOICE_APPROVAL = 'Waiting Invoice Approval';

    const STATUS_WAITING_INVOICE_APPROVAL_USN = 'Waiting Invoice ApprovalUSN';

    public function __construct(
        Account $account,
        TranslateRepository $transrepository,
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
        $this->invoices = new ArrayCollection();

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
        $invoice = $this->getInvoice();

        if (!$invoice) {
            return false;
        }

        return $invoice->getStatus() !== PaymentInvoice::STATUS_REPROVED;
    }

    public function isInvoiceApproved(): bool
    {
        $invoice = $this->getInvoice();

        if (!$invoice) {
            return false;
        }

        return $this->getInvoice()->getStatus() === PaymentInvoice::STATUS_APPROVED;
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

        if ($this->needsInvoice()) {
                if (false === $this->hasInvoice()) {

                    return self::STATUS_WAITING_INVOICE;
                } elseif (false === $this->isInvoiceApproved()) {

                    return self::STATUS_WAITING_INVOICE_APPROVAL;
                }
            }

            if ($this->wasMade) {
                return self::STATUS_MADE;
            }

            return self::STATUS_SCHEDULED;

    }

    public function getStatusUSN(): string
    {
        if ($this->needsInvoice()) {
            if (false === $this->hasInvoice()) {

                return self::STATUS_WAITING_INVOICE_USN;
            } elseif (false === $this->isInvoiceApproved()) {

                return self::STATUS_WAITING_INVOICE_APPROVAL_USN;
            }
        }

        if ($this->wasMade) {
            return self::STATUS_MADE_USN;
        }

        return self::STATUS_SCHEDULED_USN;

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

    /**
     * @return Collection|PaymentInvoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(PaymentInvoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setPayment($this);
        }

        return $this;
    }

    public function removeInvoice(PaymentInvoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getPayment() === $this) {
                $invoice->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @return PaymentInvoice|null
     */
    public function getInvoice(): ?PaymentInvoice
    {
        if (!$this->invoice) {
            /** @var PaymentInvoice $invoice */
            $invoice = $this->invoices->last();

            if ($invoice && $invoice->getStatus() !== PaymentInvoice::STATUS_REPROVED) {
                $this->invoice = $invoice;
            }
        }

        return $this->invoice;
    }
}
