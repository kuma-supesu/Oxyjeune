<?php

namespace App\Form\Type;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', RadioType::class, array('label' => 'Date'))
            ->add('heure', RadioType::class, array('label' => 'Tranche horaire'))
            ->add('noms', HiddenType::class)
            ->add('iteration', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn')))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}