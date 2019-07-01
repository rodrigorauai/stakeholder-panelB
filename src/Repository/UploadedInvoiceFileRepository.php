<?php

namespace App\Repository;

use App\Entity\UploadedReceiptFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UploadedReceiptFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadedReceiptFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadedReceiptFile[]    findAll()
 * @method UploadedReceiptFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadedInvoiceFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UploadedReceiptFile::class);
    }

    // /**
    //  * @return UploadedInvoiceFile[] Returns an array of UploadedInvoiceFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UploadedInvoiceFile
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
