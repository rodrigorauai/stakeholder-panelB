<?php

namespace App\Form;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Razão Social',
                'attr' => ['maxlength' => 120]
            ])
            ->add('cnpj', TextType::class, [
                'label' => 'CNPJ',
                'attr' => ['maxlength' => 18]
            ])
            ->add('manager', EntityType::class, [
                'label' => 'Administrador',
                'class' => Person::class,
                'query_builder' =>  function (PersonRepository $repository) {
                    $qb = $repository->createQueryBuilder('p');
                    $qb->orderBy('p.name', 'ASC');

                    return $qb;
                },
                'choice_label' => 'name',
            ])
            ->add('tradeRepresentative', EntityType::class, [
                'label' => 'Representante Responsável',
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
