<?php

namespace Viteloge\EstimationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;


class ContactEstimationType extends MyTypeWithBoolean
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastname',
                'text',
                array(
                    'label' => 'estimation.label.nom',
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'firstname',
                'text',
                array(
                    'label' => 'estimation.label.prenom',
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'phone',
                'text',
                array(
                    'label' => 'estimation.label.tel',
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'agencyRequest',
                'choice',
                $this->makeBool( 'estimation.label.demande_agence', true )
            )
            ->add(
                'save',
                'submit',
                array('label' => 'estimation.label.contact_save')
            )
        ;

    }
    public function getName()
    {
        return 'contact_estimation';
    }

}
