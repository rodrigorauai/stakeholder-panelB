<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\EntityListener;


use App\Entity\Contract;
use App\Entity\Payment;
use App\Entity\StakeholdPlanReward;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class StakeholdPlanRewardListener
{
    /**
     * @ORM\PrePersist()
     * @param StakeholdPlanReward $reward
     * @param LifecycleEventArgs $args
     */
    public function prePersist(StakeholdPlanReward $reward, LifecycleEventArgs $args)
    {
        $plan = $reward->getPlan();
        $contracts = $plan->getContracts();

        /** @var Contract $contract */
        foreach ($contracts as $contract) {
            $value = bcmul(bcdiv($reward->getRate(), '100', 2), $contract->getValue(), 2);

            $payment = new Payment(
                $contract->getAccount(),
                $value,
                $reward,
                $contract,
                Payment::PROVENANCE_CO_PARTICIPATION
            );

            $args->getObjectManager()->persist($payment);
        }
    }
}