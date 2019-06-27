<?php

namespace App\Repository;

use App\Entity\StakeholdPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StakeholdPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method StakeholdPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method StakeholdPlan[]    findAll()
 * @method StakeholdPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StakeholdPlanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StakeholdPlan::class);
    }

    // /**
    //  * @return StakeholdingPlan[] Returns an array of StakeholdingPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StakeholdingPlan
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
