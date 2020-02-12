<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecimalType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'scale' => 2,
            'precision' => 5,
            'attr' => [
                'step' => 0.01,
                'min'  => 0,
                'max'  => 999,
            ],
        ]);
    }

    public function getParent()
    {
        return NumberType::class;
    }
}