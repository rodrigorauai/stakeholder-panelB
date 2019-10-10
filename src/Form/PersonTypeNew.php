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

class PersonTypeNew extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'person.name__label',
            ])
            ->add('cpf', TextType::class, [
                'label' => 'CPF',
            ])
            ->add('email', EmailType::class, [
                'label' => 'person.email__label',
            ])
            ->add('rg', TextType::class, [
                'label' => 'RG',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefone',
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
            'data_class' => PersonNew::class,
            'method' => 'post',
        ]);
    }
}
