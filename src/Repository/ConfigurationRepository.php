<?php

namespace App\Repository;

use App\Entity\Configuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Configuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Configuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Configuration[]    findAll()
 * @method Configuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Configuration::class);
    }

    // /**
    //  * @return Configuration[] Returns an array of Configuration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByActive(): ?Configuration
    {
        return $this->createQueryBuilder('configuration')
            ->andWhere('configuration.active = :val')
            ->setParameter('val', 1)
            ->getQuery()
            // ->getResult()
            ->getOneOrNullResult()
        ;
    }
    
    public function findByDisabled($id)
    {
        return $this->createQueryBuilder('configuration')
            ->andWhere('configuration.id != :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }
}
