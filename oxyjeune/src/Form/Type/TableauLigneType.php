<?php

namespace App\Form\Type;

use App\Entity\TableauLigne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauLigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('paiementXFois', ChoiceType::class, array(
                'label' => 'Paiement en',
                'choices'  => [
                '1x' => 1,
                '2x' => 2,
                '3x' => 3,
                '4x' => 4,
                '5x' => 5,
                '6x' => 6,
                '7x' => 7,
            ],))
            ->add('tableauPaiements', CollectionType::class, array(
                'entry_type' => TableauPaiementType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('payee', ChoiceType::class, array(
                'label' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TableauLigne::class,
            "allow_extra_fields" => true,
        ]);
    }
}
