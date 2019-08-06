<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonRolesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_stakeholder', CheckboxType::class, [
                'label' => 'Sócio',
                'data' => true,
                'disabled' => true,
            ])
            ->add('is_administrative_assistant', CheckboxType::class, [
                'label' => 'Auxiliar Administrativo',
            ])
            ->add('is_administrator', CheckboxType::class, [
                'label' => 'Administrivo',
            ])
            ->add('is_system_administrator', CheckboxType::class, [
                'label' => 'Administrador do Sistema',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
