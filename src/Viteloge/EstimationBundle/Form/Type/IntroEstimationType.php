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
use Viteloge\EstimationBundle\Component\Enum\TypeEnum;


class IntroEstimationType extends MyTypeWithBoolean
{

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $typeEnum = new TypeEnum();

        $builder
            ->add('inseeCity', 'hidden', array(
                'label' => 'estimation.label.ville',
                'data_class' => 'Acreat\InseeBundle\Entity\InseeCity',
                'required' => true,
                'empty_data' => null,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\NotNull()
                )
            ))
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
    }

    public function getName() {
        return 'intro_estimation';
    }

}
