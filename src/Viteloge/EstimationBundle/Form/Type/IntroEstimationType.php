<?php

namespace Viteloge\EstimationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;

use Viteloge\EstimationBundle\Entity\Estimation;


class IntroEstimationType extends MyTypeWithBoolean
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'ville',
                'hidden'
            )
            ->add(
                'type',
                'choice',
                array(
                    'expanded' => true,
                    'choices' => Estimation::$TYPES_BIEN,
                    'label' => 'estimation.label.type'
                )
            )
            ->add(
                'nb_pieces',
                'integer',
                array(
                    'label' => 'estimation.label.nb_pieces',
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Range( array( 'min' => 1, 'max' => 15 ) )
                    )
                )
            )
            ->add(
                'surface_habitable',
                'integer',
                array(
                    'label' => 'estimation.label.surface_habitable',
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Range( array( 'min' => 1, 'max' => 5000 ) )
                    )
                )
            )
            ->add(
                'save',
                'submit',
                array('label' => 'estimation.label.save')
            )
        ;
    }
    public function getName()
    {
        return 'intro_estimation';
    }
    
}
