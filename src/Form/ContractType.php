<?php

namespace App\Form;

use App\Entity\Contract;
use App\Entity\StakeholdPlan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plan', EntityType::class, [
                'label' => 'Plano de Patrocínio',
                'class' => StakeholdPlan::class,
                'choice_label' => 'administrativeName',
            ])
            ->add('value', NumberType::class, [
                'label' => 'Valor do Patrocínio',
                'scale' => 2,
                'html5' => true,
            ])
            ->add('executionDate', DateType::class, [
                'label' => 'Data de Contratação',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('firstReturnDate', DateType::class, [
                'label' => 'Data do Primeiro Rendimento',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('expirationDate', DateType::class, [
                'label' => 'Data de Expiração do Contrato',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('isEntitledToRefund', CheckboxType::class, [
                'label' => 'Direito a rembolso',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
