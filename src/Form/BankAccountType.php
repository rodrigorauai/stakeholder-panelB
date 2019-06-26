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

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bank', TextType::class, [
                'label' => 'Nome do Banco',
            ])
            ->add('bankCode', TextType::class, [
                'label' => 'Código do Banco',
            ])
            ->add('agency', TextType::class, [
                'label' => 'Agência',
            ])
            ->add('number', TextType::class, [
                'label' => 'Número da Conta',
            ])
            ->add('type', TextType::class, [
                'label' => 'Tipo da Conta',
                'required' => false,
            ])
            ->add('naturalPerson', ChoiceType::class, [
                'label' => 'Pessoal Física ou Jurídica',
                'choices' => [
                    'Física' => true,
                    'Jurídica' => false,
                ]
            ])
            ->add('holderName', TextType::class, [
                'label' => 'Títular da Conta',
                'required' => false,
            ])
            ->add('holderDocumentNumber', TextType::class, [
                'label' => 'Documento do Títular da Conta',
                'required' => false,
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Informações Adicionais',
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
