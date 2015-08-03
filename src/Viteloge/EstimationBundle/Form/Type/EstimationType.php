<?php

namespace Viteloge\EstimationBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManager;
use Acreat\InseeBundle\Entity\InseeCity;
use Viteloge\CoreBundle\Entity\Estimate;
use Viteloge\EstimationBundle\Component\Enum\PathEnum;
use Viteloge\EstimationBundle\Component\Enum\TypeEnum;
use Viteloge\EstimationBundle\Component\Enum\ExpositionEnum;
use Viteloge\EstimationBundle\Component\Enum\ConditionEnum;
use Viteloge\EstimationBundle\Component\Enum\UsageEnum;
use Viteloge\EstimationBundle\Component\Enum\ApplicantEnum;
use Viteloge\EstimationBundle\Component\Enum\TimeEnum;


class EstimationType extends MyTypeWithBoolean {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $pathEnum = new PathEnum();
        $typeEnum = new TypeEnum();
        $expositionEnum = new ExpositionEnum();
        $conditionEnum = new ConditionEnum();
        $usageEnum = new UsageEnum();
        $applicantEnum = new ApplicantEnum();
        $timeEnum = new TimeEnum();

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
                    'choices' => $pathEnum->choices(),
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
            ->add('inseeCity', 'text', array(
                'label' => 'estimation.label.ville',
                'data_class' => 'Acreat\InseeBundle\Entity\InseeCity',
                'required' => true,
                'empty_data' => null,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\NotNull()
                )
            ))
            // caracteristics
            ->add(
                'type',
                'choice',
                array(
                    'expanded' => true,
                    'choices' => $typeEnum->choices(),
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
                    'choices' => $expositionEnum->choices(),
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
                    'choices' => $conditionEnum->choices(),
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.etat'
                )
            )
            ->add(
                'usage',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => $usageEnum->choices(),
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
                    'choices' => $applicantEnum->choices(),
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.proprio',
                    'empty_value' => '',
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                )
            )
            ->add(
                'delai',
                'genemu_jqueryselect2_choice',
                array(
                    'choices' => $timeEnum->choices(),
                    'required' => false,
                    'configs' => array( 'width' => '100%', 'minimumResultsForSearch' => -1 ),
                    'label' => 'estimation.label.delai'
                )
            )
            ->add(
                'agencyRequest',
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

        $em = $this->em;
        $formModifier = function (FormInterface $form, /*InseeCity*/ $inseeCity) {
            $choices = (empty($inseeCity)) ? array() : array($inseeCity);
            $form->add('inseeCity', 'entity', array(
                'class' => 'AcreatInseeBundle:InseeCity',
                'data_class' => null,
                'property' => 'getNameAndPostalcode',
                'group_by' => 'inseeDepartment',
                'label' => 'estimation.label.ville',
                'choices' => $choices,
                'expanded' => false,
                'multiple' => false,
                'data' => $inseeCity, // not really necessary
                'required' => true,
                'empty_value' => '',
                'empty_data' => null,
                'mapped' => true
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();
                $data = $event->getData();
                $inseeCity = ($data) ? $data->getInseeCity() : null;
                $formModifier($form, $inseeCity);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier, $em) {
                $form = $event->getForm();
                $data = $event->getData();
                if ($form->has('inseeCity')) {
                    $inseeCity = $form->get('inseeCity')->getData();
                    if (!($inseeCity instanceof InseeCity) || $inseeCity->getId() != $data['inseeCity']) {
                        $inseeCity = $em->getRepository('AcreatInseeBundle:InseeCity')->findOneById($data['inseeCity']);
                        $formModifier($form, $inseeCity);
                    }
                }
            }
        );

        $builder->addEventListener( FormEvents::PRE_SET_DATA, array( $this, 'checkAndSetPersonalData' ) );
        $builder->addEventListener( FormEvents::PRE_SUBMIT, array( $this, 'checkAndSetPersonalData' ) );

    }

    public function getName() {
        return 'estimation';
    }

    public function checkAndSetPersonalData( FormEvent $event ) {
        $form = $event->getForm();
        $estimation = $event->getData();

        if ( is_array( $estimation ) ) {
            $constraints = 1 ==$estimation['agencyRequest'];
        } else {
            $constraints = false;
        }

        $form
            ->add(
                'lastname',
                'text',
                array(
                    'label' => 'estimation.label.nom',
                    'disabled' => ! $constraints,
                    'constraints' => ($constraints ? array( new Assert\NotBlank() ) : array())
                )
            )
            ->add(
                'firstname',
                'text',
                array(
                    'label' => 'estimation.label.prenom',
                    'disabled' => ! $constraints,
                    'constraints' =>( $constraints ? array( new Assert\NotBlank() ) : array())
                )
            )
            ->add(
                'phone',
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

