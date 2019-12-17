<?php

namespace App\Form\Type;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CreationPlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array('label' => 'Date'))
            ->add('iteration', IntegerType::class, array('label' => 'Nombres de personnes par tranche horaire : '))
            ->add('duree', IntegerType::class, array('label' => 'Durée des tranches horaires'))
            ->add('heures', CollectionType::class, [
                'entry_type' => HeurePlanningType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
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