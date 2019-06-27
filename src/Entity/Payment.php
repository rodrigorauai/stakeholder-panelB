<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment extends AccountFinancialMovement
{
    /**
     * @var StakeholdPlanReward
     * @ORM\ManyToOne(targetEntity="StakeholdPlanReward")
     */
    private $interest;

    /**
     * @var Contract
     * @ORM\ManyToOne(targetEntity="Contract")
     */
    private $contract;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account")
     */
    private $beneficiary;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    private $provenance;

    const PROVENANCE_CO_PARTICIPATION = 'co-participation';

    const PROVENANCE_COMMISSION = 'commission';

    public function __construct(
        Account $account,
        string $value,
        StakeholdPlanReward $interest,
        Contract $contract,
        Account $beneficiary,
        string $provenance
    ) {
        parent::__construct($account, $value);

        $this->interest = $interest;
        $this->contract = $contract;
        $this->beneficiary = $beneficiary;
        $this->provenance = $provenance;
    }

    /**
     * @return StakeholdPlanReward
     */
    public function getInterest(): StakeholdPlanReward
    {
        return $this->interest;
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
        return $this->beneficiary;
    }

    /**
     * @return string
     */
    public function getProvenance(): string
    {
        return $this->provenance;
    }
}
