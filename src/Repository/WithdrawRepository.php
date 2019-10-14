<?php

namespace App\Repository;

use App\Entity\Withdraw;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method Withdraw|null find($id, $lockMode = null, $lockVersion = null)
 * @method Withdraw|null findOneBy(array $criteria, array $orderBy = null)
 * @method Withdraw[]    findAll()
 * @method Withdraw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WithdrawRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Withdraw::class);
    }

    /**
     * @param FormInterface $form
     * @param array|null $accounts
     * @return Withdraw[]
     */
    public function findUsingSearchForm(FormInterface $form, ?array $accounts = null)
    {
        $qb = $this->createQueryBuilder('withdraw');
        $qb
            ->select('withdraw')
            ->orderBy('withdraw.id', 'DESC');

        if ($accounts) {
            $qb
                ->andWhere($qb->expr()->in('withdraw.account', ':accounts'))
                ->setParameter('accounts', $accounts);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $queryString = $form->get('queryString')->getData();

            if ($queryString) {
                $qb
                    ->join('withdraw.account', 'account')
                    ->join('account.owner', 'owner')
                    ->where(
                        $qb->expr()->orX(
                            $qb->expr()->eq('withdraw.id', ':queryString'),
                            $qb->expr()->like('owner.name', ':containingQueryString')
                        )
                    )
                    ->setParameters([
                        'queryString' => $queryString,
                        'containingQueryString' => "%$queryString%",
                    ]);
            }
        }

        return $qb->getQuery()->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Withdraw
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
