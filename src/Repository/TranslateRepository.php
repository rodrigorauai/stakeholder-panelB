<?php

namespace App\Repository;

use App\Entity\Configuration;
use App\Entity\ConfigurationTranslate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Configuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Configuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Configuration[]    findAll()
 * @method Configuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ConfigurationTranslate::class);
    }

    public function findOneByActive(): ?ConfigurationTranslate
    {
        return $this->createQueryBuilder('configuration_translate')
            ->andWhere('configuration_translate.active = :val')
            ->setParameter('val', 1)
            ->getQuery()
            // ->getResult()
            ->getOneOrNullResult()
            ;
    }

    public function findByDisabled($id)
    {
        return $this->createQueryBuilder('configuration_translate')
            ->andWhere('configuration_translate.id != :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
            ;
    }
}
