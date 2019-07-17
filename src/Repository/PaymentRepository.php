<?php

namespace App\Repository;

use App\Entity\Payment;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

    // /**
    //  * @return Payment[] Returns an array of Payment objects
    //  */
    
    public function findByExampleField($value)
    {
        if ($value !== null)
            return $this->createQueryBuilder('p')
                ->join('p.contract', 'c')
                ->join('c.plan', 'pl')
                ->join('p.account', 'a')
                ->join('a.owner', 'o')
                ->andWhere('p.id LIKE :val')
                ->orWhere('pl.administrativeName LIKE :val')
                ->orWhere('o.name LIKE :val')
                ->orWhere('p.invoiceUrl LIKE :val')
                ->setParameter('val', '%'.$value['index'].'%')
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
