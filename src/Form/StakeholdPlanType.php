<?php

namespace App\Form;

use App\Entity\StakeholdPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StakeholdPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('administrativeName', TextType::class, [
                'label' => 'Nome interno',
            ])
            ->add('commercialName', TextType::class, [
                'label' => 'Nome público',
            ])
            ->add('minimumValue', NumberType::class, [
                'label' => 'Valor mínimo',
                'scale' => 2,
                'html5' => true,
            ])
            ->add('valueMultiple', NumberType::class, [
                'label' => 'Múltiplo do valor',
                'scale' => 2,
                'html5' => true,
            ])
            ->add('rewardDay', IntegerType::class, [
                'label' => 'Dia de Rendimento',
                'scale' => 0,
                'attr' => [
                    'min' => 1,
                    'max' => 28
                ],
            ])
            ->add('monthlyCommission', NumberType::class, [
                'label' => 'Porcentagem de comissão mensal',
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => -99.99,
                    'max' => 99.99,
                ],
                'html5' => true,
            ])
            ->add('gracePeriod', IntegerType::class, [
                'label' => 'Carência em meses',
            ])
            ->add('bestAcquisitionDay', IntegerType::class, [
                'label' => 'Melhor dia de adesão',
                'attr' => [
                    'min' => 1,
                    'max' => 28,
                ],
            ])
            ->add('monthlyAdministrativeFee', NumberType::class, [
                'label' => 'Porcentagem de taxa administrativa mensal',
                'required' => false,
                'empty_data' => '0,00',
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => -99.99,
                    'max' => 99.99,
                ],
                'html5' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StakeholdPlan::class,
            'empty_data' => function (FormInterface $form) {
                $plan = new StakeholdPlan(
                    $form->get('administrativeName')->getData(),
                    $form->get('commercialName')->getData(),
                    $form->get('minimumValue')->getData(),
                    $form->get('valueMultiple')->getData(),
                    $form->get('rewardDay')->getData(),
                    $form->get('monthlyCommission')->getData(),
                    $form->get('gracePeriod')->getData(),
                    $form->get('bestAcquisitionDay')->getData(),
                    $form->get('monthlyAdministrativeFee')->getData()
                );

                return $plan;
            }
        ]);
    }
}
