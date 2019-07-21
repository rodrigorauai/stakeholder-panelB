<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Payment;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @param DateTime $limitDate
     * @return Payment[]
     */
    public function findUnpaidDueBefore(DateTime $limitDate)
    {
        $qb = $this->createQueryBuilder('payment');
        $qb
            ->select('payment')
            ->join('payment.reward', 'reward')
            ->where('payment.wasMade = :false')
            ->andWhere('reward.paymentDueDate < :limit')
            ->setParameters([
                'false' => false,
                'limit' => $limitDate,
            ])
        ;

        return $qb->getQuery()->getResult();
    }
    
    public function findUsingSearchForm(FormInterface $form)
    {
        $qb = $this->createQueryBuilder('payment');
        $qb
            ->select('payment')
            ->orderBy('payment.id', 'DESC');

        if ($form->isSubmitted() && $form->isValid()) {
            $qb
                ->join('payment.contract', 'contract')
                ->join('contract.plan', 'plan')
                ->join('payment.account', 'account')
                ->join('account.owner',  'owner')
                ->where($qb->expr()->orX(
                    $qb->expr()->in('payment.id', ':queryString'),
                    $qb->expr()->like('plan.administrativeName', ':queryString'),
                    $qb->expr()->like('owner.name', ':queryString'),
                    $qb->expr()->like('payment.invoiceUrl', ':queryString')
                ))
                ->setParameter('queryString', '%'.$form->get('queryString')->getData().'%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $accounts
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function calculateTotalCoParticipation(array $accounts)
    {
        $qb = $this->createQueryBuilder('payment');
        $qb
            ->select('SUM(payment.value)')
            ->leftJoin('payment.reward', 'reward')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('payment.provenance', ':provenance'),
                $qb->expr()->lte('reward.disclosureDate', ':now')
            ))
            ->setParameter('provenance', Payment::PROVENANCE_CO_PARTICIPATION)
            ->setParameter('now', new DateTime())
        ;

        if (count($accounts) > 0) {
            $qb
                ->andWhere(
                    $qb->expr()->in('payment.account', ':accounts')
                )
                ->setParameter('accounts', $accounts)
            ;
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $accounts
     * @return Payment[]
     * @throws Exception
     */
    public function findCoParticipationsByAccount(array $accounts)
    {
        $qb = $this->createQueryBuilder('payment');
        $qb
            ->select('payment')
            ->leftJoin('payment.reward', 'reward')
            ->orderBy('reward.paymentDueDate')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('payment.provenance', ':provenance'),
                $qb->expr()->lte('reward.disclosureDate', ':now')
            ))
            ->setParameter('provenance', Payment::PROVENANCE_CO_PARTICIPATION)
            ->setParameter('now', new DateTime())
        ;

        if (count($accounts) > 0) {
            $qb
                ->andWhere(
                    $qb->expr()->in('payment.account', ':accounts')
                )
                ->setParameter('accounts', $accounts)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Account[] $accounts
     * @param bool $aggregatePaymentsOnTheSameDay
     * @return array
     * @throws Exception
     */
    public function findNextPayment(array $accounts, bool $aggregatePaymentsOnTheSameDay)
    {
        $qb = $this->createQueryBuilder('payment');
        $qb
            ->select('SUM(payment.value) as value, reward.paymentDueDate as dueDate')
            ->leftJoin('payment.reward', 'reward')
            ->orderBy('reward.paymentDueDate')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('payment.provenance', ':provenance'),
                $qb->expr()->lte('reward.disclosureDate', ':now'),
                $qb->expr()->gt('reward.paymentDueDate', ':now')
            ))
            ->setParameter('provenance', Payment::PROVENANCE_CO_PARTICIPATION)
            ->setParameter('now', new DateTime())
            ->setMaxResults(1)
        ;

        if (count($accounts) > 0) {
            $qb
                ->andWhere(
                    $qb->expr()->in('payment.account', ':accounts')
                )
                ->setParameter('accounts', $accounts)
            ;
        }

        if ($aggregatePaymentsOnTheSameDay) {
            $qb->groupBy('dueDate');
        }

        $result = $qb->getQuery()->getScalarResult();

        if (empty($result) || null === $result[0]['value'] || null === $result[0]['dueDate']) {
            return null;
        }

        // TODO: Find a better structure for returned data
        return $result[0];
    }
}
