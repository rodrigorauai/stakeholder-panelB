<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postalCode', TextType::class, ['label' => 'CEP',])
            ->add('street', TextType::class, ['label' => 'Logradouro',])
            ->add('number', TextType::class, ['label' => 'Número',])
            ->add('complement', TextType::class, ['label' => 'Complemento', 'required' => false,])
            ->add('district', TextType::class, ['label' => 'Bairro',])
            ->add('city', TextType::class, ['label' => 'Cidade',])
            ->add('state', ChoiceType::class, ['label' => 'Estado',
                'choices' => [
                    'Acre' => 'AC',
                    'Alagoas' => 'AL',
                    'Amapá' => 'AP',
                    'Amazonas' => 'AM',
                    'Bahia' => 'BA',
                    'Ceará' => 'CE',
                    'Distrito Federal' => 'DF',
                    'Espírito Santo' => 'ES',
                    'Goiás' => 'GO',
                    'Maranhão' => 'MA',
                    'Mato Grosso' => 'MT',
                    'Mato Grosso do Sul' => 'MS',
                    'Minas Gerais' => 'MG',
                    'Pará' => 'PA',
                    'Paraíba' => 'PB',
                    'Paraná' => 'PR',
                    'Pernambuco' => 'PE',
                    'Piauí' => 'PI',
                    'Rio de Janeiro' => 'RJ',
                    'Rio Grande do Norte' => 'RN',
                    'Rio Grande do Sul' => 'RS',
                    'Rondônia' => 'RO',
                    'Roraima' => 'RR',
                    'Santa Catarina' => 'SC',
                    'São Paulo' => 'SP',
                    'Sergipe' => 'SE',
                    'Tocantins' => 'TO',
                ],])
            ->add('country', ChoiceType::class, ['label' => 'País', 'choices' =>
                ['Brasil' => 'Brazil',]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'empty_data' => function (FormInterface $form) {
                        $address = new Address(
                            $form->get('postalCode')->getData(),
                            $form->get('street')->getData(),
                            $form->get('number')->getData(),
                            $form->get('complement')->getData(),
                            $form->get('district')->getData(),
                            $form->get('city')->getData(),
                            $form->get('state')->getData(),
                            $form->get('country')->getData()
                        );

                return $address;
            },
        ]);
    }
}
