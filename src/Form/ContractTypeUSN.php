<?php

namespace App\Form;

use App\Entity\Contract;
use App\Entity\StakeholdPlan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractTypeUSN extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plan', EntityType::class, [
                'label' => 'Investment plan',
                'class' => StakeholdPlan::class,
                'choice_label' => 'administrativeName',
            ])
            ->add('value', NumberType::class, [
                'label' => 'Investment value',
                'scale' => 2,
                'html5' => true,
            ])
            ->add('executionDate', DateType::class, [
                'label' => 'Hiring date',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('firstReturnDate', DateType::class, [
                'label' => 'First Yield Date',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('expirationDate', DateType::class, [
                'label' => 'Contract Expiration Date',
                'format' => DateType::HTML5_FORMAT,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('contractFile', FileType::class, [
                'label' => 'Scanned Contract',
                'mapped' => false,
            ])
            ->add('isEntitledToRefund', CheckboxType::class, [
                'label' => 'Right to refund',
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
