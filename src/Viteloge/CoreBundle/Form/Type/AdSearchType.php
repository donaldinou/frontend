<?php

namespace Viteloge\CoreBundle\Form\Type {

    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\EntityManager;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\SearchEntity\AdSearch;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;
    use Viteloge\CoreBundle\Component\Enum\TypeEnum;
    use Viteloge\CoreBundle\Component\Enum\RoomEnum;
    use Viteloge\CoreBundle\Component\Enum\DistanceEnum;

    class AdSearchType extends AbstractType {

        private $em;

        public function __construct(EntityManager $em) {
            $this->em = $em;
        }

        /**
         *
         */
        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionEnum = new TransactionEnum();
            $typeEnum = new TypeEnum();
            $roomEnum = new RoomEnum();
            $distanceEnum = new DistanceEnum();
            $builder
                ->add('transaction', 'choice', array(
                    'label' => 'ad.transaction',
                    'choices' => $transactionEnum->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'preferred_choices' => array()
                ))
                ->add('where', 'choice', array(
                    'label' => 'ad.where',
                    'choices' => array(),
                    'expanded' => false,
                    'multiple' => true,
                    'preferred_choices' => array()
                ))
                ->add('what', 'choice', array(
                    'label' => 'ad.what',
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'preferred_choices' => array()
                ))
                ->add('rooms', 'choice', array(
                    'label' => 'ad.rooms',
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'preferred_choices' => array()
                ))
             /*   ->add('keywords', 'text', array(
                    'label' => 'ad.keywords',
                    'required' => false
                ))*/
                ->add('minPrice', 'money', array(
                    'label' => 'ad.price.min',
                    'required' => false,
                    'precision' => 0
                ))
                ->add('maxPrice', 'money', array(
                    'label' => 'ad.price.max',
                    'required' => false,
                    'precision' => 0
                ))
             /*   ->add('radius', 'choice', array(
                    'label' => 'ad.radius',
                    'choices' => $distanceEnum->choices(),
                    'required' => false,
                    'empty_value' => false
                ))*/
                ->add('search', 'submit')
            ;

            $em = $this->em;
            $formModifier = function (FormInterface $form, $cities) {
                $choices = (empty($cities)) ? array() : $cities;
                $form->add('where', 'entity', array(
                    'label' => 'ad.where',
                    'class' => 'AcreatInseeBundle:InseeCity',
                    'data_class' => null,
                    'property' => 'getNameAndPostalcode',
                    'group_by' => 'inseeDepartment',
                    'choices' => $choices,
                    'expanded' => false,
                    'multiple' => true,
                    'data' => $cities, // not really necessary
                    'required' => false,
                    //'preferred_choices' => array(),
                    'empty_value' => '',
                    'empty_data' => null,
                    'mapped' => true
                ));
            };

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier, $em) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $where = $data->getWhere();
                    if (!empty($where)) {
                        $cities = $em->getRepository('AcreatInseeBundle:InseeCity')->findById($where);
                        $formModifier($form, $cities);
                    }
                }
            );

            $builder->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($formModifier, $em) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    if (isset($data['where'])) {
                        $cities = $em->getRepository('AcreatInseeBundle:InseeCity')->findById($data['where']);
                        $formModifier(
                            $form,
                            $cities/*array_flip(array_map(
                                function($city){
                                    return $city->getId();
                                }, $cities
                            ))*/
                        );
                    }
                }
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver) {
            parent::setDefaultOptions($resolver);
            $resolver->setDefaults(
                array(
                    'csrf_protection' => true,
                    'data_class' => 'Viteloge\CoreBundle\SearchEntity\Ad',
                    'intention' => 'task_form',
                )
            );
        }

        public function getName() {
            return 'viteloge_core_adsearch';
        }

    }

}
