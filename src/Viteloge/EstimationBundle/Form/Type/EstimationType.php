<?php

namespace Viteloge\EstimationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;

use Viteloge\EstimationBundle\Entity\Estimation;


class EstimationType extends MyTypeWithBoolean
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder
            // general
            ->add(
                'numero',
                'text',
                array( 'required' => false, 'attr' => array( 'placeholder' => 'estimation.label.numero' ) )
            )
            ->add(
                'type_voie',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$TYPES_VOIE,
                    'configs' => array( 'width' => '100%' ),
                    'label' => 'estimation.label.type_voie'
                )
            )
            ->add(
                'voie',
                'text',
                array(
                    'required' => 'required',
                     'constraints' => array(
                        new Assert\NotBlank()
                     ),
                    'attr' => array( 'placeholder' => 'estimation.label.voie' ) )
            )
            ->add(
                'codepostal',
                'text',
                array( 'attr' => array( 'placeholder' => 'estimation.label.codepostal') )
            )
            ->add(
                'ville',
                'genemu_jqueryselect2_hidden',
                array(
                    'required' => true,
                    'label' => 'estimation.label.ville',
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\NotNull()
                    )
                )
            )
            // caracteristics
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
                'nb_sdb',
                'integer',
                array( 'required' => false, 'label' => 'estimation.label.nb_sdb' )
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
                'surface_terrain',
                'integer',
                array( 'required' => false, 'label' => 'estimation.label.surface_terrain' )  )
            ->add(
                'exposition',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$EXPOSITIONS,
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.exposition'
                )
            )
            ->add(
                'etage',
                'integer',
                array( 'required' => false, 'label' => 'estimation.label.etage' )
            )
            ->add(
                'nb_etages',
                'integer', array( 'required' => false, 'label' => 'estimation.label.nb_etages' )
            )
            ->add(
                'nb_niveaux',
                'integer', array( 'required' => false, 'label' => 'estimation.label.nb_niveaux' )
            )
            ->add(
                'annee_construction',
                'integer', array( 'required' => false, 'label' => 'estimation.label.annee_construction' )
            )
            // details
            ->add(
                'ascenseur',
                'choice',
                $this->makeBool( 'estimation.label.ascenseur' )
            )
            ->add(
                'balcon',
                'choice',
                $this->makeBool( 'estimation.label.balcon' )
            )
            ->add(
                'terrasse',
                'choice',
                $this->makeBool( 'estimation.label.terrasse' )
            )
            ->add(
                'parking',
                'integer',
                array( 'required' => false, 'label' => 'estimation.label.parking' )
            )
            ->add(
                'garage',
                'integer',
                array( 'required' => false, 'label' => 'estimation.label.garage' )
            )
            ->add(
                'vue',
                'checkbox',
                array( 'required' => false, 'label' => 'estimation.label.vue' )
            )
            ->add(
                'vue_detail',
                'text',
                array( 'required' => false, 'label' => 'estimation.label.vue_detail' )
            )
            // situation bien
            ->add(
                'etat',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$ETATS,
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.etat'
                )
            )
            ->add(
                'usage',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$USAGES,
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.usage'
                )
            )
            // situation proprio

            ->add(
                'proprietaire',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$TYPES_PROPRIO,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.proprio',
                    'empty_value' => "estimation.value.proprio",
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'delai',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => Estimation::$DELAIS,
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.delai'
                )
            )
            ->add(
                'demande_agence',
                'choice',
                $this->makeBool( 'estimation.label.demande_agence', true )
            )

            // owner details

            ->add(
                'mail',
                'email',
                array( 'required' => true, 'label' => 'estimation.label.mail' )
            )
            ->add(
                'save',
                'submit',
                array('label' => 'estimation.label.save')
            )
        ;


        $builder->addEventListener( FormEvents::PRE_SET_DATA, array( $this, 'checkAndSetPersonalData' ) );
        $builder->addEventListener( FormEvents::PRE_SUBMIT, array( $this, 'checkAndSetPersonalData' ) );
        
    }

    public function getName()
    {
        return 'estimation';
    }

    public function checkAndSetPersonalData( FormEvent $event ) {
        $form = $event->getForm();
        $estimation = $event->getData();

        if ( is_array( $estimation ) ) {
            $constraints = 1 ==$estimation['demande_agence'];
        } else {
            $constraints = false;
        }
        
        $form
            ->add(
                'nom',
                'text',
                array(
                    'label' => 'estimation.label.nom',
                    'disabled' => ! $constraints,
                    'constraints' => ($constraints ? array( new Assert\NotBlank() ) : array())
                )
            )
            ->add(
                'prenom',
                'text',
                array(
                    'label' => 'estimation.label.prenom',
                    'disabled' => ! $constraints,
                    'constraints' =>( $constraints ? array( new Assert\NotBlank() ) : array())
                )
            )
            ->add(
                'tel',
                'text',
                array(
                    'label' => 'estimation.label.tel',
                    'disabled' => ! $constraints,
                    'constraints' => $constraints ? array( new Assert\NotBlank() ) : array()
                )
            )
        ;
    }
}
    
