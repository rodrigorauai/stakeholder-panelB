<?php

namespace App\Repository;

use App\Entity\StakeholdPlanReward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StakeholdPlanReward|null find($id, $lockMode = null, $lockVersion = null)
 * @method StakeholdPlanReward|null findOneBy(array $criteria, array $orderBy = null)
 * @method StakeholdPlanReward[]    findAll()
 * @method StakeholdPlanReward[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StakeholdPlanRewardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StakeholdPlanReward::class);
    }

    // /**
    //  * @return StakeholdPlanReward[] Returns an array of StakeholdPlanReward objects
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
    public function findOneBySomeField($value): ?StakeholdPlanReward
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
