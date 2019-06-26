<?php

namespace App\Form;

use App\Entity\StakeholdPlanReward;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StakeholdPlanRewardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', NumberType::class, [
                'label' => 'Porcentagem de Rendimento',
                'scale' => 2,
                'attr' => [
                    'min' => 0.00,
                    'max' => 99.99,
                    'step' => 0.01,
                ],
            ])
            ->add('disclosureDate', DateType::class, [
                'label' => 'Data de Divulgação',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('firstPaymentDate', DateType::class, [
                'label' => 'Data de Início dos Pagamentos',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('lastPaymentDate', DateType::class, [
                'label' => 'Data de Conclusão dos Pagamentos',
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
                    $form->get('firstPaymentDate')->getData(),
                    $form->get('lastPaymentDate')->getData()
                );

                return $reward;
            }
        ]);
    }
}
