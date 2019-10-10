<?php

namespace App\Form;

use App\Entity\StakeholdPlanReward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StakeholdPlanRewardTypeUSN extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', NumberType::class, [
                'label' => 'Income percentage',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'min' => 0.00,
                    'max' => 99.99,
                    'step' => 0.01,
                ],
            ])
            ->add('disclosureDate', DateType::class, [
                'label' => 'Release date',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('paymentDueDate', DateType::class, [
                'label' => 'Achievement Deadline',
                'html5' => true,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StakeholdPlanReward::class,
            'empty_data' => function (FormInterface $form) {
                $reward = new StakeholdPlanReward(
                    $form->get('rate')->getData(),
                    $form->get('disclosureDate')->getData(),
                    $form->get('paymentDueDate')->getData()
                );

                return $reward;
            }
        ]);
    }
}
