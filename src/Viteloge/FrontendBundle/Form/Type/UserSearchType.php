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
                    'label' => 'usersearch.transaction',
                    'choices' => $transactionEnum->choices(),
                    'expanded' => true,
                    'multiple' => false,
                    'preferred_choices' => array()
                ))
                ->add('inseeCity', 'text', array(
                    'label' => 'usersearch.inseecity',
                    'data_class' => 'Acreat\InseeBundle\Entity\InseeCity',
                    'required' => true,
                    'empty_data' => null
                ))
                ->add('type', 'choice', array(
                    'label' => 'usersearch.type',
                    'choices' => $typeEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'preferred_choices' => array(),
                ))
                ->add('rooms', 'choice', array(
                    'label' => 'usersearch.rooms',
                    'choices' => $roomEnum->choices(),
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'preferred_choices' => array(),
                    'empty_value' => 'usersearch.empty_value'
                ))
                ->add('keywords', 'text', array(
                    'label' => 'usersearch.keywords',
                    'required' => false
                ))
                ->add('budgetMin', 'money', array(
                    'label' => 'usersearch.budgetMin',
                    'required' => false,
                    'precision' => 0
                ))
                ->add('budgetMax', 'money', array(
                    'label' => 'usersearch.budgetMax',
                    'required' => false,
                    'precision' => 0
                ))
                ->add( 'radius', 'choice', array(
                    'label' => 'usersearch.radius',
                    'choices' => $distanceEnum->choices(),
                    'required' => true
                ))
                ->add( 'helpEnabled', null, array(
                    'label' => 'usersearch.help',
                    'required' => false
                ))
                ->add('mailEnabled', 'checkbox', array(
                    'label' => 'usersearch.mailenabled',
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
                    'label' => 'usersearch.inseecity',
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

        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\UserSearch',
                'intention' => 'task_form',
            ));
        }

        public function getName() {
            return 'viteloge_frontend_usersearch';
        }

    }

}
