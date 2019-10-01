<?php

namespace App\Form;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyTypeUSN extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Corporate name',
            ])
            ->add('cnpj', TextType::class, [
                'label' => 'National Registry of Legal Entities',
            ])
            ->add('manager', EntityType::class, [
                'label' => 'Administrator',
                'class' => Person::class,
                'query_builder' =>  function (PersonRepository $repository) {
                    $qb = $repository->createQueryBuilder('p');
                    $qb->orderBy('p.name', 'ASC');

                    return $qb;
                },
                'choice_label' => 'name',
            ])
            ->add('tradeRepresentative', EntityType::class, [
                'label' => 'Responsible Representative',
                'required' => false,
                'class' => Person::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompanyData::class,
        ]);
    }
}
