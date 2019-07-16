<?php

namespace App\Repository;

use App\Entity\UploadedDigitizedContractFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedDigitizedContractFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedDigitizedContractFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedDigitizedContractFile[]    findAll()
 * @method UploadedDigitizedContractFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitizedContractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedDigitizedContractFile::class);
    }

    // /**
    //  * @return DigitizedContract[] Returns an array of DigitizedContract objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DigitizedContract
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
