<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('firstDayOfMonthlyPayment', IntegerType::class, [
                'label' => 'Dia de início dos pagamentos',
                'scale' => 0,
                'attr' => [
                    'min' => 1,
                    'max' => 28
                ],
            ])
            ->add('lastDayOfMonthlyPayment', IntegerType::class, [
                'label' => 'Dia limite de pagamento',
                'attr' => [
                    'min' => 1,
                    'max' => 28
                ]
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
            ->add('monthlyCommission', NumberType::class, [
                'label' => 'Comissão mensal',
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => 0.00,
                    'max' => 99.99,
                ],
                'html5' => true,
            ])
            ->add('monthlyAdministrativeFee', NumberType::class, [
                'label' => 'Taxa administrativa mensal',
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => 0.00,
                    'max' => 99.99,
                ],
                'html5' => true,
            ])
            ->add('hasFixedMonthlyRewardRate', ChoiceType::class, [
                'label' => 'Tipo de rendimento',
                'choices' => [
                    'Variável' => false,
                    'Fixo' => true,
                ],
                'mapped' => false,
            ])
            ->add('monthlyRewardRate', NumberType::class, [
                'label' => 'Rendimento mensal',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => 0.00,
                    'max' => 99.99,
                ],
                'html5' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StakeholdPlanData::class,
        ]);
    }
}
