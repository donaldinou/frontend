<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\EntityManager;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;
    use Viteloge\CoreBundle\Component\Enum\TypeEnum;
    use Viteloge\CoreBundle\Component\Enum\RoomEnum;
    use Viteloge\CoreBundle\Component\Enum\DistanceEnum;
    use Viteloge\CoreBundle\Component\Enum\UserSearchSourceEnum;

    class UserSearchType extends AbstractType {

        private $tokenStorage;

        private $em;

        public function __construct(TokenStorageInterface $tokenStorage, EntityManager $em) {
            $this->tokenStorage = $tokenStorage;
            $this->em = $em;
        }

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
                    'preferred_choices' => array(),
                    'data' => TransactionEnum::__default,
                ))
                ->add('inseeCity', 'text', array(
                    'data_class' => 'Acreat\InseeBundle\Entity\InseeCity',
                    'required' => true,
                    'empty_data' => null
                ))
                ->add('type', 'choice', array(
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'placeholder' => 'ad.type.what',
                    'preferred_choices' => array(),
                    'data' => null
                ))
                ->add('rooms', 'choice', array(
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'empty_value' => 'usersearch.empty_value'
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
                    'choices' => $distanceEnum->choices(),
                    'required' => true
                ))
                ->add( 'helpEnabled', null, array(
                    'label' => 'search.help',
                    'required' => false
                ))
                ->add('mailEnabled', 'checkbox', array(
                    'label' => 'search.mailenabled',
                    'required' => false
                ))
                ->add('save', 'submit')
            ;

            // grab the user, do a quick sanity check that one exists
            $user = $this->tokenStorage->getToken()->getUser();
            if (!$user) {
                throw new \LogicException(
                    'The UserSearchType cannot be used without an authenticated user!'
                );
            }

            $formModifier = function (FormInterface $form, /*InseeCity*/ $inseeCity) {
                $choices = (empty($inseeCity)) ? array() : array($inseeCity);
                $form->add('inseeCity', 'entity', array(
                    'class' => 'AcreatInseeBundle:InseeCity',
                    'data_class' => null,
                    'property' => 'getNameAndPostalcode',
                    'group_by' => 'inseeDepartment',
                    'choices' => $choices,
                    'expanded' => false,
                    'multiple' => false,
                    'data' => $inseeCity, // not really necessary
                    'required' => true,
                    //'preferred_choices' => array(),
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
                    $inseeCity = $data->getInseeCity();
                    $formModifier($form, $inseeCity);
                }
            );

            $builder->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    if ($form->has('inseeCity')) {
                        $inseeCity = $form->get('inseeCity')->getData();
                        if (!($inseeCity instanceof InseeCity) || $inseeCity->getId() != $data['inseeCity']) {
                            $inseeCity = $this->em->getRepository('AcreatInseeBundle:InseeCity')->findOneById($data['inseeCity']);
                            $formModifier($form, $inseeCity);
                        }
                    }
                }
            );

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($user) {
                    $data = $event->getForm()->getData();
                    $data->setCivility($user->getCivility());
                    $data->setLastname($user->getLastname());
                    $data->setFirstname($user->getFirstname());
                    $data->setMail($user->getEmail());
                    $data->setSource(UserSearchSourceEnum::WEB);

                    $deletedAt = $data->getDeletedAt();
                    $isMailEnabled = $data->isMailEnabled();
                    if ($isMailEnabled) {
                        $data->setDeletedAt(null);
                    }
                    elseif (!$isMailEnabled && empty($deletedAt)) {
                        $data->setDeletedAt(new \DateTime('now'));
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
