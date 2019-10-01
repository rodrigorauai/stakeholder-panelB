<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonRolesTypeUSN extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_stakeholder', CheckboxType::class, [
                'label' => 'Partner',
                'data' => true,
                'disabled' => true,
            ])
            ->add('is_administrative_assistant', CheckboxType::class, [
                'label' => 'Administrative Assistant',
            ])
            ->add('is_administrator', CheckboxType::class, [
                'label' => 'Administrative',
            ])
            ->add('is_system_administrator', CheckboxType::class, [
                'label' => 'System Administrator',
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
