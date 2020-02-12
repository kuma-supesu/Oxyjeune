<?php

namespace App\Form\Type;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event', TextType::class, array(
                'label' => 'Nom de l\'évènement'
            ))
            ->add('description', TextareaType::class, array(
                'required' => false,
            ))
            ->add('journees', CollectionType::class, array(
                'entry_type' => JourneeType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'prototype_name' => '__journee__'
            ))
            ->add('etat', ChoiceType::class, array(
                'choices'  => [
                'Brouillon' => 0,
                'Publier' => 1,
            ]))
            ->add('save', SubmitType::class, array(
                'attr' => ['class' => 'btn-success btn-lg btn'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
            "allow_extra_fields" => true,
        ]);
    }
}