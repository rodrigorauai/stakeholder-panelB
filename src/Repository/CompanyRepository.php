<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @param FormInterface $form
     * @return Company[]
     */
    public function findByExampleField(FormInterface $form)
    {
        $qb = $this->createQueryBuilder('company');
        $qb
            ->select('company')
            ->orderBy('company.id', 'DESC');

        if ($form->isSubmitted() && $form->isValid()) {

            $queryString = $form->get('queryString')->getData();

            if ($queryString) {
                $qb
                    ->andWhere($qb->expr()->orX(
                        $qb->expr()->eq('company.id', ':queryString'),
                        $qb->expr()->like('company.name', ':containingQueryString'),
                        $qb->expr()->eq('company.cnpj', ':digitsOnlyQueryString')
                    ))
                    ->setParameters([
                        'queryString' => $queryString,
                        'containingQueryString' => "%$queryString%",
                        'digitsOnlyQueryString' => preg_replace('/[^\d]/', '', $queryString),
                    ]);
            }

        }

            return $qb->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
