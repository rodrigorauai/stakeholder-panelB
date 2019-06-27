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
}
