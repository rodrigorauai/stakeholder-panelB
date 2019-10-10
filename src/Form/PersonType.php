<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'person.name__label',
                'attr' => ['maxlength' => 80]
            ])
            ->add('cpf', TextType::class, [
                'label' => 'CPF',
                'attr' => ['maxlength' => 14]
            ])
            ->add('email', EmailType::class, [
                'label' => 'person.email__label',
                'attr' => ['maxlength' => 50]
            ])
            ->add('rg', TextType::class, [
                'label' => 'RG',
                'attr' => ['maxlength' => 12],
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefone',
                'attr' => ['maxlength' => 15],
                'required' => false,
            ])
            ->add('tradeRepresentative', EntityType::class, [
                'label' => 'Representante Responsável',
                'required' => false,
                'class' => Person::class,
                'choice_label' => 'name',
            ])
            ->add('sendPasswordDefinitionEmail', CheckboxType::class, [
                'label' => 'Enviar e-mail de definição de senha',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PersonData::class,
            'method' => 'post',
        ]);
    }
}
