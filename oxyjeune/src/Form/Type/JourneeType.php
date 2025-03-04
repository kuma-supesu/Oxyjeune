<?php

namespace App\Form\Type;

use App\Entity\Journee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JourneeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array('widget' => 'choice',
                'years' => range(date('Y'), date('Y')+10),
                'format' => 'dd-MM-yyyy',
                'label' => false,
            ))
            ->add('nombrePersonnes', IntegerType::class, array(
                'attr' => [
                    'style' => 'width: 5em',
                    'placeholder' => '1,2,3...',
                    'min' => 1,
                    'max' => 10
                ]
            ))
            ->add('dureeHeure', IntegerType::class, array(
                'attr' => [
                    'style' => 'width: 5em',
                    'placeholder' => 'H',
                    'min' => 0,
                    'max' => 10
                ],
            ))
            ->add('dureeMinute', IntegerType::class, array(
                'attr' => [
                    'class' => 'ml-2',
                    'style' => 'width: 5em',
                    'placeholder' => 'Min',
                    'min' => 0,
                    'max' => 59
                ],
            ))
            ->add('heures', CollectionType::class, array(
                'entry_type' => HeureType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Journee::class,
            "allow_extra_fields" => true,
        ]);
    }
}
