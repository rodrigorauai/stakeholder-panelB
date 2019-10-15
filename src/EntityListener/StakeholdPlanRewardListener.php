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
use App\Entity\StakeholdPlan;
use App\Entity\StakeholdPlanReward;
use DateTime;
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
     * @param StakeholdPlan $plan
     * @throws \Exception
     */
    public function prePersist(StakeholdPlanReward $reward, LifecycleEventArgs $args, StakeholdPlan $plan)
    {
        $plan = $reward->getPlan();
        $contracts = $plan->getContracts();

        $nextRate = new DateTime();

        /** @var Contract $contract */
        foreach ($contracts as $contract) {
            // Co-participation (Plan's reward)

            $rate = $reward->getRate();
            $inicial_value = $contract->getValue();
            $yield = $contract->getYield();
            $last = $contract->getLast();

            $monthlyRate = $plan->getMonthlyRate();
            $percenteRate = $plan->getMonthlyPercenteRate();

            if ( $last < $inicial_value ) {

                if ($monthlyRate > $nextRate) {
                    $valueResult = bcmul(bcdiv($rate, 100, 4), $last, 2);
                    $value = bcmul(bcdiv($percenteRate, 100, 4), $valueResult, 2);
                } else {
                    $value = bcmul(bcdiv($rate, 100, 4), $last, 2);
                }

                $new_value = number_format(($last + $value), 2, ".", "");

                $contract->setYield(number_format(($yield+$value), 2, ".", ""));
                $contract->setLast($new_value);

            } else if ( $last >= $inicial_value ) {

                $value = bcmul(bcdiv($rate, 100, 4), $inicial_value, 2);
                $new_value = number_format(($last + $value), 2, ".", "");

                $contract->setYield(number_format(($yield+$value), 2, ".", ""));
                $contract->setLast($new_value);

            }
            $args->getObjectManager()->persist($contract);

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
        // dd('a');
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