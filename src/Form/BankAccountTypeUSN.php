<?php

namespace App\Form;

use App\Entity\BankAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountTypeUSN extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bank', TextType::class, [
                'label' => 'Bank name',
            ])
            ->add('bankCode', TextType::class, [
                'label' => 'Bank code',
            ])
            ->add('agency', TextType::class, [
                'label' => 'Agency',
            ])
            ->add('number', TextType::class, [
                'label' => 'Account number',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Account type',
                'choices' => [
                    'Account Savings' => 'Savings',
                    'Current Account' => 'Current',
                ],
                'required' => false,
            ])
            ->add('naturalPerson', ChoiceType::class, [
                'label' => 'Physical person or legal entity',
                'choices' => [
                    'Physical' => true,
                    'Legal Entity' => false,
                ]
            ])
            ->add('holderName', TextType::class, [
                'label' => 'Owner of the account',
                'required' => false,
            ])
            ->add('holderDocumentNumber', TextType::class, [
                'label' => 'Account Holder Document',
                'required' => false,
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Additional Information',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
            'empty_data' => function (FormInterface $form) {
                $account = new BankAccount(
                    $form->get('bank')->getData(),
                    $form->get('bankCode')->getData(),
                    $form->get('agency')->getData(),
                    $form->get('number')->getData(),
                    $form->get('type')->getData(),
                    $form->get('naturalPerson')->getData(),
                    $form->get('holderName')->getData(),
                    $form->get('holderDocumentNumber')->getData()
                );

                $account->setNotes($form->get('notes')->getData());

                return $account;
            }
        ]);
    }
}
