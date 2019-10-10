<?php

namespace App\Form;

use App\Repository\TranslateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TranslateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('translate', ChoiceType::class, [
                'label' => 'Idioma',
                'choices' => [
                    'Inglês' => 'USN',
                    'Português' => 'BRL',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TranslateData::class,
            'method' => 'post',
        ]);
    }
}
