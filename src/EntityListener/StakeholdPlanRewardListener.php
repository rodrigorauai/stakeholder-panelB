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
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\ORMException;

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
            // Co-participation (Plan's reward)

            $value = bcmul(bcdiv($reward->getRate(), '100', 4), $contract->getValue(), 2);

            $payment = new Payment(
                $contract->getAccount(),
                $value,
                $reward,
                $contract,
                Payment::PROVENANCE_CO_PARTICIPATION
            );

            $args->getObjectManager()->persist($payment);

            // Commission
            if ($contract->getAccount()->getOwner()->getTradeRepresentative()) {
                $value = bcmul(bcdiv($reward->getPlan()->getMonthlyCommission(), 100, 4), $contract->getValue(), 2);

                $commission = new Payment(
                    $contract->getAccount()->getOwner()->getTradeRepresentative()->getAccount(),
                    $value,
                    $reward,
                    $contract,
                    Payment::PROVENANCE_COMMISSION
                );

                $args->getObjectManager()->persist($commission);
            }
        }
    }

    /**
     * @ORM\PreFlush()
     * @param StakeholdPlanReward $reward
     * @param PreFlushEventArgs $args
     * @throws ORMException
     */
    public function preFlush(StakeholdPlanReward $reward, PreFlushEventArgs $args)
    {
        foreach ($reward->getPayments() as $payment) {

            if ($payment->getProvenance() !== Payment::PROVENANCE_CO_PARTICIPATION) {
                continue;
            }

            $value = bcmul(bcdiv($reward->getRate(), '100', 4), $payment->getContract()->getValue(), 2);

            $payment->setValue($value);

            $args->getEntityManager()->persist($payment);
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet(
                $args->getEntityManager()->getClassMetadata(Payment::class),$payment);
        }
    }
}