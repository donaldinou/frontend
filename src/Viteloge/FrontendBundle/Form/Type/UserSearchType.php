<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Doctrine\ORM\EntityRepository;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Component\Enum\TypeEnum;
    use Viteloge\CoreBundle\Component\Enum\RoomEnum;

    class UserSearchType extends AbstractType {

        /*protected $em;

        public function __construct($em) {
            $this->em = $em;
        }*/

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $transactionChoices = EnumTransactionType::getValues();
            $transactionDefault = EnumTransactionType::getDefault();
            $typeEnum = new TypeEnum();
            $roomEnum = new RoomEnum();
            $builder
                ->add('transaction', 'choice', array(
                    'choices' => $transactionChoices,
                    'expanded' => true,
                    'multiple' => false,
                    'preferred_choices' => array(),
                    'data' => $transactionDefault,
                ))
                ->add('inseeCity', 'search', array(
                    'data_class' => 'Acreat\InseeBundle\Entity\InseeCity'
                ))
                ->add('type', 'choice', array(
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => false,
                    'placeholder' => 'ad.type.what',
                    'preferred_choices' => array(),
                    'data' => null
                ))
                ->add('rooms', 'choice', array(
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true
                ))
                ->add('keywords', 'text', array(
                    'label' => 'search.keywords',
                    'required' => false
                ))
                ->add('budgetMin', 'money', array(
                    'label' => 'search.price_min',
                    'required' => false,
                    'precision' => 0
                ))
                ->add('budgetMax', 'money', array(
                    'label' => 'search.price_max',
                    'required' => false,
                    'precision' => 0
                ))
                ->add( 'radius', 'choice', array(
                    'label' => 'search.rayon',
                    'choices' => array( "0" => '-', "5" => '5 km', "10" => '10 km', "20" => '20 km', "30" => '30 km' ),
                    'required' => false,
                    'empty_value' => false
                ))
                ->add( 'helpEnabled', null, array(
                    'label' => 'search.help',
                    'required' => false
                ))
                ->add('save', 'submit');

                $formModifier = function (FormInterface $form, $text = null) {
                    //$positions = null === $sport ? array() : $sport->getAvailablePositions();
                    $cities = null;
                    $form->add('inseeCity', 'entity', array(
                        'class'       => 'Acreat\InseeBundle\Entity\InseeCity',
                        'placeholder' => '',
                        'choices'     => $cities,
                    ));
                };

                /*$builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function (FormEvent $event) use ($formModifier) {
                        $form = $event->getForm();
                        $data = $event->getData();
                        $formModifier($form, '');
                    }
                );*/

                //$InseeCityRepository = $this->em->getRepository( 'Acreat\InseeBundle\Entity\InseeCity' );
                //$builder->get('inseeCity')->addEventListener(
                $builder->addEventListener(
                    FormEvents::PRE_SET_DATA,
                    function (FormEvent $event) {
                        $form = $event->getForm();
                        $data = $event->getData();
                        if ($data) {
                            $inseeCityValue = $data->getInseeCity();
                            //var_dump($form);
                            //$event->setData($inseeCity);
                            $formOptions = array(
                                'class' => 'Acreat\InseeBundle\Entity\InseeCity',
                                'property' => 'name',
                                'placeholder' => '',
                                'query_builder' => function (\Acreat\InseeBundle\Repository\InseeCityRepository $repository) use ($inseeCityValue) {
                                    return $repository->createQueryBuilder('ic')->where('ic.id = :id')->setParameter('id', $inseeCityValue);
                                },
                            );
                            $form->remove('inseeCity');
                            $form->add('inseeCity', 'entity', $formOptions);
                        }
                    }
                );

                /*$builder->get('inseeCity')->addEventListener(
                    FormEvents::POST_SUBMIT,
                    function (FormEvent $event) use ($formModifier) {
                        // It's important here to fetch $event->getForm()->getData(), as
                        // $event->getData() will get you the client data (that is, the ID)
                        $inseeCity = $event->getForm()->getData();
                        var_dump($inseeCity);die;

                        // since we've added the listener to the child, we'll have to pass on
                        // the parent to the callback functions!
                        $formModifier($event->getForm()->getParent(), $inseeCity);
                    }
                );*/
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\UserSearch',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_usersearch';
        }

    }

}
