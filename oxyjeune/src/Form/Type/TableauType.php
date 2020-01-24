<?php

namespace App\Form\Type;

use App\Entity\Tableau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('classeur', TextType::class)
            ->add('annee', DateType::class, array(
                'label' => 'Annee',
                'years' => range(date('Y')-10, date('Y')+10),
                'format' => 'yyyy-dd-MM',
            ))
            ->add('tableauLignes', CollectionType::class, array(
                'entry_type' => TableauLigneType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_name' => '__tableau__'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Valider'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tableau::class,
            "allow_extra_fields" => true,
        ]);
    }
}
