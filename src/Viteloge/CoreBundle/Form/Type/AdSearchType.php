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
                    'choices' => $transactionEnum->choices(),
                    'expanded' => true,
                    'multiple' => false,
                    'preferred_choices' => array()
                ))
                ->add('where', 'choice', array(
                    'choices' => array(),
                    'expanded' => false,
                    'multiple' => true,
                    'placeholder' => 'ad.where',
                    'preferred_choices' => array()
                ))
                ->add('what', 'choice', array(
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'placeholder' => 'ad.what',
                    'preferred_choices' => array()
                ))
                ->add('rooms', 'choice', array(
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'placeholder' => 'ad.rooms.number',
                    'preferred_choices' => array()
                ))
                ->add('keywords', 'text', array(
                    'label' => 'ad.keywords',
                    'required' => false
                ))
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
                ->add('radius', 'choice', array(
                    'label' => 'ad.radius',
                    'choices' => $distanceEnum->choices(),
                    'required' => false,
                    'empty_value' => false
                ))
                ->add('search', 'submit')
            ;

            $formModifier = function (FormInterface $form, $cities) {
                $choices = (empty($cities)) ? array() : $cities;
                $form->add('where', 'entity', array(
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
                    'placeholder' => 'ad.where',
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
                    $where = $data->getWhere();
                    $cities = $this->em->getRepository('AcreatInseeBundle:InseeCity')->findById($where);
                    $formModifier($form, $cities);
                }
            );

            $builder->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $cities = $this->em->getRepository('AcreatInseeBundle:InseeCity')->findById($data['where']);
                    $formModifier(
                        $form,
                        $cities/*array_flip(array_map(
                            function($city){
                                return $city->getId();
                            }, $cities
                        ))*/
                    );
                }
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver) {
            parent::setDefaultOptions($resolver);
            $resolver->setDefaults(
                array(
                    'data_class' => 'Viteloge\CoreBundle\SearchEntity\Ad'
                )
            );
        }

        public function getName() {
            return 'viteloge_core_adsearch';
        }

    }

}
