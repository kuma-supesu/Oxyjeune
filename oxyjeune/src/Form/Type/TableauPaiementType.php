<?php

namespace App\Form\Type;

use App\Entity\TableauPaiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauPaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVersement', DateType::class, array(
                'label' => 'Date du versement',
                'years' => range(date('Y')-10, date('Y')+10),
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime('now'),
            ))
            ->add('moyenPaiement', ChoiceType::class, array(
                'label' => 'Methode de paiement',
                'choices'  => [
                    'Espece' => 'espece',
                    'Chèque' => 'cheque',
                    'Bon de la CAF' => 'bon caf',
                ],))
            ->add('sommeVersement', NumberType::class, array(
                'label' => 'Somme versée',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TableauPaiement::class,
            "allow_extra_fields" => true,
        ]);
    }
}
