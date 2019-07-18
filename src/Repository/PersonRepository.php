<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param FormInterface $form
     * @return Person[]
     */
    public function findUsingSearchForm(FormInterface $form)
    {
        $qb = $this->createQueryBuilder('person');
        $qb
            ->select('person')
            ->orderBy('person.id', 'DESC');

        if ($form->isSubmitted() && $form->isValid()) {
            $queryString = $form->get('queryString')->getData();
            $digitsOnlyQueryString = preg_replace('/[^\d]/', '', $queryString);

            $qb
                ->where(
                    $qb->expr()->orX(
                        $qb->expr()->eq('person.id', ':queryString'),
                        $qb->expr()->like('person.name', ':containingQueryString'),
                        $qb->expr()->eq('person.cpf', ':digitsOnlyQueryString'),
                        $qb->expr()->like('person.email', ':containingQueryString'),
                        $qb->expr()->eq('person.phone', ':containingDigitsOnlyQueryString')
                    )
                )
                ->setParameters([
                    'queryString' => $queryString,
                    'containingQueryString' => "%$queryString%",
                    'digitsOnlyQueryString' => $digitsOnlyQueryString,
                    'containingDigitsOnlyQueryString' => "%$digitsOnlyQueryString%",
                ]);
        }

        return $qb->getQuery()->getResult();
    }
}
