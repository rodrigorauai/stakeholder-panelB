<?php

namespace App\Form;

use App\Entity\PaymentInvoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentInvoiceReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Avaliação',
                'choices' => [
                    'Não avaliado' => PaymentInvoice::STATUS_WAITING,
                    'Aprovado'     => PaymentInvoice::STATUS_APPROVED,
                    'Reprovado'    => PaymentInvoice::STATUS_REPROVED,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentInvoice::class,
        ]);
    }
}
