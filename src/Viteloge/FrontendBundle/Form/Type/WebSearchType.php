<?php

namespace Viteloge\FrontendBundle\Form\Type {

    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Symfony\Component\EventDispatcher\EventDispatcher;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\FormEvent;
    use Doctrine\ORM\EntityManager;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Component\Enum\UserSearchSourceEnum;

    class WebSearchType extends AbstractType {

        private $tokenStorage;

        private $em;

        public function __construct(TokenStorageInterface $tokenStorage, EntityManager $em) {
            $this->tokenStorage = $tokenStorage;
            $this->em = $em;
        }

        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder->add('title', 'text');
            $builder->add('userSearch', new UserSearchType($this->tokenStorage, $this->em));

            // grab the user, do a quick sanity check that one exists
            $user = $this->tokenStorage->getToken()->getUser();
            if (!$user) {
                throw new \LogicException(
                    'The WebSearchType cannot be used without an authenticated user!'
                );
            }

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($user) {
                    $data = $event->getForm()->getData();
                    $data->setUser($user);
                }
            );
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver){
            $resolver->setDefaults(array(
                'data_class' => 'Viteloge\CoreBundle\Entity\WebSearch',
                'cascade_validation' => true
            ));
        }

        public function getName() {
            return 'viteloge_frontend_websearch';
        }

    }

}
