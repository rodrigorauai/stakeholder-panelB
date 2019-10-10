<?php

namespace App\Form;

use App\Entity\PaymentInvoice;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentInvoiceTypeUSN extends AbstractType
{
    /**
     * @var Person
     */
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'URL of invoice',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $user = $this->user;

        $resolver->setDefaults([
            'data_class' => PaymentInvoice::class,
            'empty_data' => function (FormInterface $form) use ($user) {
                $invoice = new PaymentInvoice();
                $invoice->setPayment($form->getConfig()->getOption('payment'));
                $invoice->setSubmittor($user);

                return $invoice;
            },
            'payment' => null,
        ]);
    }
}
